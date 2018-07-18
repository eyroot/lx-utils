<?php

namespace LxTesting\Utils;

use Lx\Utils\CodeCleanUp\Task\TaskInterface;

class TestCaseCodeCleanUpTask extends TestCaseGeneric
{
    /**
     * Content test with original and expected data defined under
     *   LX_TESTING_UTILS_CODECLEANUP_TESTING_DATA_DIR_TASKS/{$taskName}
     * @var array - list of array members with keys 'original' and 'expected' (values)
     */
    protected $contentTest = array();

    /**
     * The main Task class instance
     *   under namespace Lx\\Utils\\CodeCoverage\\Task
     * @var TaskInterface
     */
    protected $taskInstance;

    /**
     * Task name
     * @var string
     */
    protected $taskName;

    /**
     * {@inheritDoc}
     * @see \PHPUnit\Framework\TestCase::setUp()
     */
    protected function setUp()
    {
        $classNameTest = get_class($this);
        $this->taskName = substr(
            $classNameTest,
            strrpos($classNameTest, '\\') + 1,
            -4
        );

        $className = 'Lx\Utils\CodeCleanUp\Task\\' . $this->taskName;
        $this->taskInstance = new $className();

        $pathDataTest = LX_TESTING_UTILS_CODECLEANUP_TESTING_DATA_DIR_TASKS . '/' . $this->taskName;

        foreach (scandir($pathDataTest) as $file) {
            if ('.' != $file && '..' != $file
                && is_file($pathDataTest . '/' . $file)
                && false === strpos($file, '_expected')) {
                    $this->contentTest[] = array(
                        'original' => file_get_contents($pathDataTest . '/' . $file),
                        'expected' => file_get_contents($pathDataTest . '/' .
                            str_replace('.', '_expected.', $file))
                    );
                }
        }
    }
}
