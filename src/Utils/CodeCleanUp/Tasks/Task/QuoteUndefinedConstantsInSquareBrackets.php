<?php

namespace Lx\Utils\CodeCleanUp\Tasks\Task;

use Lx\Utils\CodeCleanUp\Tasks\TaskInterface;
use Lx\Utils\CodeCleanUp\Tasks\TaskAbstract;
use Lx\Utils\CodeCleanUp\Tools\StringReplaceSkip;

class QuoteUndefinedConstantsInSquareBrackets extends TaskAbstract implements TaskInterface
{
    /**
     * {@inheritDoc}
     * @see \Lx\Utils\CodeCleanUp\Tasks\TaskInterface::process()
     */
    public function process($data)
    {
        $stringReplaceSkip = new StringReplaceSkip(
            'quotes',
            array(
                '\"'
            )
        );
        $data = $stringReplaceSkip->textEncode($data);

        $definedConstants = $this->getDefinedConstants();

        $spacerChars = ' ' . chr(9);
        $spacer = '[' . $spacerChars . ']*';

        // append single quotes around variables where they are missing
        $pattern = '`(\$[a-zA-Z0-9_]+' . $spacer . ')(\[[\$a-zA-Z0-9_\[\]' . $spacerChars . ']+\])`';
        $text = preg_replace_callback($pattern, function($matches) use ($spacer, $definedConstants) {
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

        // fix - add "{", "}" - array definitions (old-style) inside double quoted strings (nested-quotes)
        $pattern = '`"[^"]*"`U';
        $text = preg_replace_callback($pattern, function($matches) {
            return $this->fixAddCurlyBrackets($matches[0]);
        }, $text);

        // fix - add "{", "}" - array definitions (old-style) inside heredoc strings
        $pattern = '`\<\<\<([a-zA-Z0-9]+)'.chr(10).'.+'.chr(10).'([a-zA-Z0-9]+);`sU';
        $text = preg_replace_callback($pattern, function($matches) {
            if ($matches[1] === $matches[2]) { // only if heredoc tag names match
                return $this->fixAddCurlyBrackets($matches[0]);
            }
            return $matches[0];
        }, $text);

        $text = $stringReplaceSkip->textDecode($text);

        return $text;
    }

    /**
     * Add curly brackets (inside double quoted strings and heredoc definitions)
     *
     * @param string $text
     *
     * @return string
     */
    private function fixAddCurlyBrackets($text)
    {
        $pattern = '`(.{1})(\$[a-zA-Z0-9_]+\[\'.+\'\])(.{1})`';
        return preg_replace_callback($pattern, function($matches) {
            $arrayString = $matches[2];
            if ('{' !== $matches[1] && '}' !== $matches[3]) {
                $arrayString = '{' . $arrayString . '}';
            }
            return $matches[1] . $arrayString . $matches[3];
        }, $text);
    }
}
