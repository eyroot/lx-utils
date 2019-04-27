<?php

namespace LxTesting\Utils\CodeCleanUp\Task;

use LxTesting\Utils\TestCaseCodeCleanUpTask;
use Lx\Utils\CodeCleanUp\Tools\CollectDefinedConstantsFromPath;

class QuoteUndefinedConstantsInSquareBracketsTest extends TestCaseCodeCleanUpTask
{
    // ex values: 'nested-quotes' or 'heredoc', etc
    private $debugOnlyTestTheseFileNames = array(
        // debug test values here
    );

    public function test()
    {
        foreach ($this->contentTest as $data) {
            if (count($this->debugOnlyTestTheseFileNames) > 0 && !in_array($data['fileName'], $this->debugOnlyTestTheseFileNames, true)) {
                continue;
            }

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
