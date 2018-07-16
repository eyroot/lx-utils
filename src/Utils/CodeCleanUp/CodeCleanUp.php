<?php

namespace Lx\Utils\CodeCleanUp;

use Lx\Utils\UtilsException;

class CodeCleanUp
{
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
     * @param string $path
     */
    public function addFilePath($path)
    {
        $this->filePaths[] = $path;
    }

    /**
     * @param string $extension
     */
    public function addFileExtension($extension)
    {
        $this->fileExtensions[] = $extension;
    }

    /**
     * @param string $task
     */
    public function addTask($task)
    {
        $this->tasks[] = $task;
    }

    /**
     * Run all scheduled tasks for defined paths and matching extensions
     */
    public function run()
    {
        $this->filesChangedDuringRun = array();
        $this->softErrorsDuringRun = array();

        foreach ($this->filePaths as $path) {
            $this->runOnPath($path);
        }

        return array(
            'softErrorsDuringRun' => $this->softErrorsDuringRun,
            'filesChangedDuringRun' => $this->filesChangedDuringRun
        );
    }

    private function runOnPath($path)
    {
        if (!is_file($path) && !is_dir($path)) {
            throw new UtilsException('Invalid path specified: ['.$path.']');
        }

        if (is_dir($path)) {
            foreach (scandir($path) as $file) {
                if ('.' !== $file && '..' !== $file) {
                    $this->runOnPath($path . '/' . $file);
                }
            }
        } elseif (is_file($path)) {
            $ext = substr($path, strrpos($path, '.') + 1);
            if (in_array($ext, $this->fileExtensions, true)) {
                $dataOriginal = file_get_contents($path);
                foreach ($this->tasks as $task) {
                    $className = 'Lx\\Utils\\CodeCleanUp\\Task\\' . $task;
                    $object = new $className();
                    $dataProcessed = $object->process($dataOriginal);

                    if (md5($dataOriginal) != md5($dataProcessed)) {
                        @$result = file_put_contents($path, $dataProcessed);

                        if (false === $result) {
                            $this->softErrorsDuringRun[] = 'Could not write new data to path ['.$path.']';
                        } else {
                            $this->filesChangedDuringRun[] = $path;
                        }
                    }
                }
            }
        }
    }
}
