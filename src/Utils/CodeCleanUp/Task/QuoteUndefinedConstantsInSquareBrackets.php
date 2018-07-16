<?php

namespace Lx\Utils\CodeCleanUp\Task;

class QuoteUndefinedConstantsInSquareBrackets implements TaskInterface
{
    /**
     * {@inheritDoc}
     * @see \Lx\Utils\CodeCleanUp\Task\TaskInterface::process()
     */
    public function process($data)
    {
        $pattern = '`(\$[a-zA-Z0-9_]+[ '.chr(9).']*\[[ '.chr(9).']*)([a-zA-Z0-9_]+)([ '.chr(9).']*\])`';

        return preg_replace_callback($pattern, function($matches) {
            if (trim($matches[2]) === trim(preg_replace('`[^0-9]`', '', $matches[2]))) {
                return $matches[0];
            }
            return $matches[1] . "'" . $matches[2] . "'" . $matches[3];
        }, $data);
    }
}
