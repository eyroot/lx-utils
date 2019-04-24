<?php

namespace LxTesting\Utils\CodeCleanUp\Task;

use LxTesting\Utils\TestCaseCodeCleanUpTask;
use Lx\Utils\CodeCleanUp\Tools\CollectDefinedConstantsFromPath;

class QuoteUndefinedConstantsInSquareBracketsTest extends TestCaseCodeCleanUpTask
{
    public function test()
    {
        foreach ($this->contentTest as $data) {
            $this->taskInstance->setDefinedConstants(
                CollectDefinedConstantsFromPath::collect($data['filePath'])
            );
            $this->assertEquals(
                $data['expected'],
                $this->taskInstance->process($data['original'])
            );
        }
    }
}
