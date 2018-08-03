<?php

namespace LxTesting\Utils\CodeCleanUp\Mix;

use LxTesting\Utils\TestCaseCodeCleanUpMix;
use Lx\Utils\CodeCleanUp\Tasks\TaskOptions\FileDocCommentAddOption;
use Lx\Utils\CodeCleanUp\CodeCleanUp;

class FileDocCommentRemoveAddTest extends TestCaseCodeCleanUpMix
{
    /**
     * Options to pass to the task's process() method
     *   defined for each file name in the test directory
     * @var array
     */
    private $processOptions = array(
        'file1' => array(
            CodeCleanUp::TASK_TASK_FILE_DOC_COMMENT_REMOVE => array(),
            CodeCleanUp::TASK_TASK_FILE_DOC_COMMENT_ADD => array(
                FileDocCommentAddOption::TEXT => 'A new fresh and really cool
dock block comment!'
            )
        )
    );

    public function test()
    {
        foreach ($this->contentTest as $data) {
            $result = $this->getCodeCleanUpFreshInstance()
                ->addFilePath($data['filePath'])
                ->addTask(
                    CodeCleanUp::TASK_TASK_FILE_DOC_COMMENT_REMOVE,
                    $this->processOptions[$data['fileName']][CodeCleanUp::TASK_TASK_FILE_DOC_COMMENT_REMOVE]
                )
                ->addTask(
                    CodeCleanUp::TASK_TASK_FILE_DOC_COMMENT_ADD,
                    $this->processOptions[$data['fileName']][CodeCleanUp::TASK_TASK_FILE_DOC_COMMENT_ADD]
                )
                ->run()
            ;

            $this->assertEquals(
                $data['expected'],
                $result->filesChangedContentDryRun[$data['filePath']]['processed']
            );
        }
    }
}