<?php

use PHPUnit\Framework\TestCase;

use Lx\Utils\CodeCleanUp\CodeCleanUp;

class CodeCleanUpTest extends TestCase
{
    private $dirTesting = null;

    private $dirs = array(
        'dir1',
        'dir1/dir2',
        'dir1/dir2/dir3',
        'dir1/dir2/dir4',
        'dir1/dir5',
        'dir6',
        'dir6/dir7'
    );
    private $files = array(
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
    private $fileContents = array(
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
        // prerequisites
        $dirParent = __DIR__ . '/../../../tmp';
        if (!is_dir($dirParent)) {
            throw new \Exception('Testing directory [' .$dirParent. '] must exist!');
        }
        $this->dirTesting = $dirParent . '/testing';

        // cleanup
        $this->cleanUpTestDirectory();
    }

    private function cleanUpTestDirectory()
    {
        shell_exec('rm -rf ' . $this->dirTesting . ' && mkdir ' . $this->dirTesting);
    }

    private function createTestingNestedDirectoriesAndFiles()
    {
        // create nested dir structure with files & content for testing
        foreach ($this->dirs as $dirPath) {
            mkdir($this->dirTesting . '/' . $dirPath);
        }
        foreach ($this->files as $filePath) {
            file_put_contents($this->dirTesting . '/' . $filePath,
                $this->fileContents['content1']['original']);
        }
    }

    public function testNestedDirectoriesAndFiles()
    {
        // create dirs and files
        $this->createTestingNestedDirectoriesAndFiles();

        // clean up
        $result = (new CodeCleanUp())
            ->addFilePath($this->dirTesting)
            ->addFileExtension('php')
            ->addTask(CodeCleanUp::TASK_QUOTE_UNDEFINED_CONSTANTS_IN_SQUARE_BRACKETS)
            ->run()
        ;

        // check results
        foreach ($this->files as $filePath) {
            $this->assertEquals(
                $this->fileContents['content1']['expected'],
                file_get_contents($this->dirTesting . '/' . $filePath)
            );
        }

        $this->assertEquals(count($this->files), count($result->filesChanged));
        $this->assertEquals(0, count($result->errors));
    }

    public function testSingleFile()
    {
        // create file
        file_put_contents($this->dirTesting . '/file1.php', $this->fileContents['content1']['original']);

        // clean up
        $result = (new CodeCleanUp())
            ->addFilePath($this->dirTesting . '/file1.php')
            ->addFileExtension('php')
            ->addTask(CodeCleanUp::TASK_QUOTE_UNDEFINED_CONSTANTS_IN_SQUARE_BRACKETS)
            ->run()
        ;

        // check results
        $this->assertEquals(
            $this->fileContents['content1']['expected'],
            file_get_contents($this->dirTesting . '/file1.php')
        );
        $this->assertEquals(1, count($result->filesChanged));
        $this->assertEquals(0, count($result->errors));
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
