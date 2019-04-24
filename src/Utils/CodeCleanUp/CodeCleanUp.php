<?php

namespace Lx\Utils\CodeCleanUp;

use Lx\Utils\UtilsException;
use Lx\Utils\CodeCleanUp\Tools\CollectDefinedConstantsFromPath;

class CodeCleanUp
{
    const TASK_QUOTE_UNDEFINED_CONSTANTS_IN_SQUARE_BRACKETS = 'QuoteUndefinedConstantsInSquareBrackets';

    const TASK_TASK_FILE_DOC_COMMENT_ADD = 'FileDocCommentAdd';

    const TASK_TASK_FILE_DOC_COMMENT_REMOVE = 'FileDocCommentRemove';

    const RUNTIME_DATA_KEY_CONSTANTS = 'defined_constants';

    /**
     * @var string[]
     */
    private $filePaths = array();

    /**
     * @var string[]
     */
    private $fileExtensions = array();

    /**
     * @var string[]
     */
    private $tasks = array();

    /**
     * @var string[] - all files which were changed during run
     */
    private $filesChangedDuringRun = array();

    /**
     * @var string[]
     */
    private $softErrorsDuringRun = array();

    /**
     * The content of files changed
     *   (not really written to disk)
     *   during dry run
     * @var mixed[]
     */
    private $filesChangedContentDryRun = array();

    /**
     * Dry run flag
     * @var bool
     */
    private $dryRun = false;

    /**
     * Runtime data
     * @var array
     */
    private $runTimeData = array();

    /**
     * Enable dry run
     */
    public function enableDryRun()
    {
        $this->dryRun = true;
        return $this;
    }

    /**
     * @param string $path
     */
    public function addFilePath($path)
    {
        $this->filePaths[] = $path;
        return $this;
    }

    /**
     * @param string $extension
     */
    public function addFileExtension($extension)
    {
        $this->fileExtensions[] = $extension;
        return $this;
    }

    /**
     * The option names are defined in Tasks/TaskOptions/{$taskName}Option
     * @param string $task
     * @param array $options - optional, options as name value pairs
     */
    public function addTask($task, $options = array())
    {
        $this->tasks[] = array(
            'task' => $task,
            'options' => $options
        );
        return $this;
    }

    /**
     * Run all scheduled tasks for defined paths and matching extensions
     * @return array - softErrorsDuringRun string[], filesChangedDuringRun string[]
     */
    public function run()
    {
        $this->filesChangedDuringRun = array();
        $this->softErrorsDuringRun = array();
        $this->filesChangedContentDryRun = array();

        foreach ($this->filePaths as $path) {
            $this->computeRunTimeData($path);
            $this->runOnPath($path, $path);
        }

        $result = new CodeCleanUpResult();
        $result->filesChanged = $this->filesChangedDuringRun;
        $result->errors = $this->softErrorsDuringRun;
        $result->filesChangedContentDryRun = $this->filesChangedContentDryRun;

        return $result;
    }

    /**
     * Compute runtime data
     * @param string $path
     * @return void
     */
    private function computeRunTimeData($path)
    {
        $keyPath = $this->computeKeyForPath($path);
        $this->runTimeData[$keyPath] = array();
        $this->runTimeData[$keyPath][self::RUNTIME_DATA_KEY_CONSTANTS] =
            CollectDefinedConstantsFromPath::collect($path);
    }

    /**
     * Normalize key path value
     * @param string $path
     * @return string
     */
    private function computeKeyForPath($path)
    {
        return md5($path);
    }

    /**
     * Run all scheduled tasks for one path, recursively if needed
     * @param string $path
     * @param string $pathRoot
     * @throws UtilsException
     */
    private function runOnPath($path, $pathRoot)
    {
        if (!is_file($path) && !is_dir($path)) {
            throw new UtilsException('Invalid path specified: ['.$path.']');
        }

        if (is_dir($path)) {
            foreach (scandir($path) as $file) {
                if ('.' !== $file && '..' !== $file) {
                    $this->runOnPath($path . '/' . $file, $pathRoot);
                }
            }
        } elseif (is_file($path) && $this->isFileEligible($path)) {
            $this->processFileData($path, $pathRoot);
        }
    }

    /**
     * @param string $path
     * @return bool
     */
    private function isFileEligible($path)
    {
        return in_array(substr($path, strrpos($path, '.') + 1), $this->fileExtensions, true);
    }

    /**
     * Process file data for all defined tasks
     * @param string $path
     * @param string $pathRoot
     * @return bool
     */
    private function processFileData($path, $pathRoot)
    {
        $dataOriginal = file_get_contents($path);
        $data = $dataOriginal;
        foreach ($this->tasks as $task) {
            $className = 'Lx\\Utils\\CodeCleanUp\\Tasks\\Task\\' . $task['task'];
            $object = new $className();
            $object->setOptions($task['options']);
            $object->setDefinedConstants(
                $this->runTimeData[$this->computeKeyForPath($pathRoot)][self::RUNTIME_DATA_KEY_CONSTANTS]
            );
            $data = $object->process($data);
        }
        return $this->saveData($path, $dataOriginal, $data);
    }

    /**
     * Update file contents only if data has been updated
     * @param string $path
     * @param string $dataOriginal
     * @param string $dataProcessed
     * @return bool
     */
    private function saveData($path, $dataOriginal, $dataProcessed)
    {
        if (md5($dataOriginal) != md5($dataProcessed)) {
            if ($this->dryRun) {
                $this->filesChangedContentDryRun[$path] = array(
                    'original' => $dataOriginal,
                    'processed' => $dataProcessed
                );
            } else {
                if (false !== file_put_contents($path, $dataProcessed)) {
                    $this->filesChangedDuringRun[] = $path;
                    return true;
                } else {
                    $this->softErrorsDuringRun[] = 'Could not write new data to path ['.$path.']';
                    return false;
                }
            }
        }
        return true;
    }
}
