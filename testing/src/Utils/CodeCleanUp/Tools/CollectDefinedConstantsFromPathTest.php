<?php

namespace LxTesting\Utils\CodeCleanUp\Mix;

use Lx\Utils\CodeCleanUp\Tools\CollectDefinedConstantsFromPath;
use LxTesting\Utils\TestCaseGeneric;

class CollectDefinedConstantsFromPathTest extends TestCaseGeneric
{
    public function test()
    {
        $this->assertEquals(
            array(
                'CONSTANT1',
                'CONSTANT2',
                'CONSTANT3',
                'CONSTANT4',
                'CONSTANT5',
                'CONSTANT6',
                'CONSTANT7'
            ),
            CollectDefinedConstantsFromPath::collect(LX_TESTING_UTILS_CODECLEANUP_TESTING_DATA_DIR_TOOLS)
        );
    }
}
