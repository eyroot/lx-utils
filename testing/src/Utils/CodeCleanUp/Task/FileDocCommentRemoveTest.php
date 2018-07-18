<?php

namespace LxTesting\Utils\CodeCleanUp\Task;

use LxTesting\Utils\TestCaseCodeCleanUpTask;

class FileDocCommentRemoveTest extends TestCaseCodeCleanUpTask
{
    public function test()
    {
        foreach ($this->contentTest as $data) {
            $this->assertEquals(
                $data['expected'],
                $this->taskInstance->process($data['original']),
                'Checking file [' . $data['fileName'] . ']'
            );
        }
    }
}
