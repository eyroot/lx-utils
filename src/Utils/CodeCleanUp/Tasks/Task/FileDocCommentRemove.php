<?php

namespace Lx\Utils\CodeCleanUp\Tasks\Task;

use Lx\Utils\CodeCleanUp\Tasks\TaskInterface;
use Lx\Utils\CodeCleanUp\Tasks\TaskAbstract;

class FileDocCommentRemove extends TaskAbstract implements TaskInterface
{
    /**
     * {@inheritDoc}
     * @see \Lx\Utils\CodeCleanUp\Tasks\TaskInterface::process()
     */
    public function process($data)
    {
        $spacerChars = ' ' . chr(9) . chr(10) . chr(11);
        $spacer = '[' . $spacerChars . ']*';

        // remove the doc block,
        //   spaces and tabs after last closing */
        //   and one new line after the block
        return preg_replace(
            '`(^<\?php' . $spacer . ')(/\*\*.+\*/[ '.chr(9).']*['.chr(10).chr(11).']{1})(.*)$`Us',
            '$1$3',
            $data
        );
    }
}
