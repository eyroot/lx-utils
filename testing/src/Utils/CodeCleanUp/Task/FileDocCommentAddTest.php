<?php

namespace LxTesting\Utils\CodeCleanUp\Task;

use LxTesting\Utils\TestCaseCodeCleanUpTask;
use Lx\Utils\CodeCleanUp\Tasks\TaskOptions\FileDocCommentAddOption;

class FileDocCommentAddTest extends TestCaseCodeCleanUpTask
{
    /**
     * Options to pass to the task's process() method
     *   defined for each file name in the test directory
     * @var array
     */
    private $processOptions = array(
        'file1' => array(
            FileDocCommentAddOption::TEXT => 'I am a simple PHP file with a descriptive File Doc Comment'
        ),
        'file2' => array(
            FileDocCommentAddOption::TEXT => 'The File Doc Comment is here as well'
        ),
        'file3' => array(
            FileDocCommentAddOption::TEXT => 'Sample text'
        ),
        'file4' => array(
            FileDocCommentAddOption::TEXT => 'Doc block text'
        ),
        'file5' => array(
            FileDocCommentAddOption::TEXT => 'Sample text'
        ),
        'file6' => array(
            FileDocCommentAddOption::TEXT => 'And the forgotten
multiline
comment,
which is naturally supported as well'
        )
    );

    public function test()
    {
        foreach ($this->contentTest as $data) {
            $this->taskInstance->setOptions(
                $this->processOptions[$data['fileName']]
            );
            $this->assertEquals(
                $data['expected'],
                $this->taskInstance->process($data['original'])
            );
        }
    }
}
