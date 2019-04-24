<?php

namespace Lx\Utils\CodeCleanUp\Tasks\Task;

use Lx\Utils\CodeCleanUp\Tasks\TaskInterface;
use Lx\Utils\CodeCleanUp\Tasks\TaskAbstract;

class QuoteUndefinedConstantsInSquareBrackets extends TaskAbstract implements TaskInterface
{
    /**
     * {@inheritDoc}
     * @see \Lx\Utils\CodeCleanUp\Tasks\TaskInterface::process()
     */
    public function process($data)
    {
        $spacerChars = ' ' . chr(9);
        $spacer = '[' . $spacerChars . ']*';
        $pattern = '`(\$[a-zA-Z0-9_]+' . $spacer . ')(\[[\$a-zA-Z0-9_\[\]' . $spacerChars . ']+\])`';
        $definedConstants = $this->getDefinedConstants();

        return preg_replace_callback($pattern, function($matches) use ($spacer, $definedConstants) {
            $pattern = '`(\[' . $spacer . ')([a-zA-Z0-9_]+)(' . $spacer . '\])`';

            $keys = preg_replace_callback($pattern, function($matches) use ($definedConstants) {
                // ignore numeric keys (leave unchanged)
                if (trim($matches[2]) === trim(preg_replace('`[^0-9]`', '', $matches[2]))) {
                    return $matches[0];
                }
                // ignore keys which are variables
                elseif ('$' === substr(trim($matches[2]), 0, 1)) {
                    return $matches[0];
                }
                // ignore unquoted strings which are defined already as constants
                elseif (in_array(trim($matches[2]), $definedConstants, true)) {
                    return $matches[0];
                }
                // quote the string
                return $matches[1] . "'" . $matches[2] . "'" . $matches[3];
            }, $matches[2]);

            return $matches[1] . $keys;
        }, $data);
    }
}
