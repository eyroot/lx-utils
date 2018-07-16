<?php

use PHPUnit\Framework\TestCase;

use Lx\Utils\CodeCleanUp\Task\QuoteUndefinedConstantsInSquareBrackets;

class QuoteUndefinedConstantsInSquareBracketsTest extends TestCase
{
    private $contentTest = array();

    public function setUp()
    {
        $path = __DIR__ . '/../../../../suite-data/tasks/'.
            'QuoteUndefinedConstantsInSquareBrackets';
        foreach (scandir($path) as $file) {
            if ('.' != $file && '..' != $file && is_file($path . '/' . $file) && false === strpos($file, '_expected')) {
                $this->contentTest[] = array(
                    'original' => file_get_contents($path . '/' . $file),
                    'expected' => file_get_contents($path . '/' . str_replace('.', '_expected.', $file))
                );
            }
        }
    }

    public function test()
    {
        $task = new QuoteUndefinedConstantsInSquareBrackets();
        foreach ($this->contentTest as $data) {
            $this->assertEquals($data['expected'], $task->process($data['original']));
        }
    }
}
