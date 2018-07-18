<?php

namespace LxTesting\Utils\CodeCleanUp\Task;

use LxTesting\Utils\TestCaseCodeCleanUpTask;

class QuoteUndefinedConstantsInSquareBracketsTest extends TestCaseCodeCleanUpTask
{
    public function test()
    {
        foreach ($this->contentTest as $data) {
            $this->assertEquals($data['expected'], $this->taskInstance->process($data['original']));
        }
    }
}
