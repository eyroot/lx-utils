<?php

use LxTesting\Utils\TestCaseGeneric;

use Lx\Utils\CodeCleanUp\CodeCleanUp;

class CodeCleanUpTest extends TestCaseGeneric
{
    /**
     * Testing directory
     * @var string
     */
    private $dirTesting = null;

    /**
     * Nested dirs structure
     * @var array
     */
    private $testNestedDirs = array(
        'dir1',
        'dir1/dir2',
        'dir1/dir2/dir3',
        'dir1/dir2/dir4',
        'dir1/dir5',
        'dir6',
        'dir6/dir7'
    );

    /**
     * Nested files structure
     * @var array
     */
    private $testNestedFiles = array(
        'dir1/file1.php',
        'dir1/dir2/file2.php',
        'dir1/dir2/dir3/file1.php',
        'dir1/dir2/dir3/file3.php',
        'dir1/dir2/dir4/file2.php',
        'dir1/dir2/dir4/file3.php',
        'dir1/dir5/file3.php',
        'dir6/file1.php',
        'dir6/file2.php',
        'dir6/dir7/file2.php',
        'dir6/dir7/file3.php'
    );

    /**
     * Nested files content
     * @var array
     */
    private $testNestedFileContents = array(
        'content1' => array(
            'original' => '<?php
$a [UNDEFINED_CONSTANT] = 1;
',
            'expected' => '<?php
$a [\'UNDEFINED_CONSTANT\'] = 1;
',
        )
    );

    public function setUp()
    {
        // remove dirs and files
        $this->removeNestedDirsAndFiles();

        $this->initDirTestingTmp('code-cleanup');
        $this->dirTesting = $this->getDirTestingTmp('code-cleanup');
    }

    /**
     * Create nested dir structure with files & content for testing
     */
    private function createNestedDirsAndFiles()
    {
        foreach ($this->testNestedDirs as $dirPath) {
            mkdir($this->dirTesting . '/' . $dirPath);
        }
        foreach ($this->testNestedFiles as $filePath) {
            file_put_contents($this->dirTesting . '/' . $filePath,
                $this->testNestedFileContents['content1']['original']);
        }
    }

    /**
     * Remove nested dir structure with files & content for testing
     */
    private function removeNestedDirsAndFiles()
    {
        foreach ($this->testNestedFiles as $filePath) {
            if (is_file($file = $this->dirTesting . '/' . $filePath)) {
                unlink($file);
            }
        }

        $dirs = $this->testNestedDirs;
        usort($dirs, function ($a, $b) {
            return strlen($b) - strlen($a);
        });

        foreach ($dirs as $dirPath) {
            if (is_dir($dir = $this->dirTesting . '/' . $dirPath)) {
                rmdir($dir);
            }
        }
    }

    public function testNestedDirsAndFiles()
    {
        // create dirs and files
        $this->createNestedDirsAndFiles();

        // run some clean up tasks
        $result = (new CodeCleanUp())
            ->addFilePath($this->dirTesting)
            ->addFileExtension('php')
            ->addTask(CodeCleanUp::TASK_QUOTE_UNDEFINED_CONSTANTS_IN_SQUARE_BRACKETS)
            ->run()
        ;

        // check result values
        foreach ($this->testNestedFiles as $filePath) {
            $this->assertEquals(
                $this->testNestedFileContents['content1']['expected'],
                file_get_contents($this->dirTesting . '/' . $filePath)
            );
        }

        // check result statistics
        $this->assertEquals(count($this->testNestedFiles), count($result->filesChanged));
        $this->assertEquals(0, count($result->errors));

        // remove dirs and files
        $this->removeNestedDirsAndFiles();
    }

    public function testSingleFile()
    {
        $testFile = $this->dirTesting . '/file1.php';

        // create file
        file_put_contents(
            $testFile,
            $this->testNestedFileContents['content1']['original']
        );

        // clean up
        $result = (new CodeCleanUp())
            ->addFilePath($testFile)
            ->addFileExtension('php')
            ->addTask(CodeCleanUp::TASK_QUOTE_UNDEFINED_CONSTANTS_IN_SQUARE_BRACKETS)
            ->run()
        ;

        // check results
        $this->assertEquals(
            $this->testNestedFileContents['content1']['expected'],
            file_get_contents($testFile)
        );
        $this->assertEquals(1, count($result->filesChanged));
        $this->assertEquals(0, count($result->errors));

        // remove test file
        unlink($testFile);
    }

    public function testInvalidPathException()
    {
        $this->expectException('Lx\Utils\UtilsException');
        $this->expectExceptionMessage('Invalid path specified');

        (new CodeCleanUp())
            ->addFilePath('path/which/does/not/exist')
            ->addFileExtension('php')
            ->run()
        ;
    }
}
