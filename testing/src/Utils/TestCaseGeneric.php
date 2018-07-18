<?php

namespace LxTesting\Utils;

use PHPUnit\Framework\TestCase;

class TestCaseGeneric extends TestCase
{
    /**
     * Array with temporary directories for testing
     *   for each namespace (the namespace name is the key)
     * @var string[]
     */
    private $dirTestingTmp = array();

    /**
     * Init a temporary testing directory for a provided namespace
     *   (this includes cleaning it up: delete its contents)
     * @param string $namespace
     * @return void
     */
    protected function initDirTestingTmp($namespace)
    {
        // set & check container directory exists
        $dirContainer = LX_TESTING_UTILS_CODECLEANUP_TESTING_DATA_DIR_TMP;
        if (!is_dir($dirContainer)) {
            throw new TestException('Testing directory [' . $dirContainer
                . '] must be created exist!');
        }

        // testing directory
        $dirTesting = $dirContainer . '/' . $namespace;

        // cleanup
        if (is_dir($dirTesting)) {
            if(!rmdir($dirTesting)) {
                shell_exec('rm -rf ' . $dirTesting);
            }
        }

        // create directory
        if (!mkdir($dirTesting)) {
            throw new TestException('Could not create testing directory ['
                . $dirTesting . ']');
        }

        // add to list of directories
        $this->dirTestingTmp[$namespace] = $dirTesting;
    }

    /**
     * Get the temporary testing directory for a namespace
     * @param string $namespace
     * @return string
     */
    protected function getDirTestingTmp($namespace)
    {
        return $this->dirTestingTmp[$namespace];
    }
}
