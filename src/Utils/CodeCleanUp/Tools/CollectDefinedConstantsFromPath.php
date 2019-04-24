<?php

namespace Lx\Utils\CodeCleanUp\Tools;

class CollectDefinedConstantsFromPath
{
    private static $stack = array();

    /**
     * @param string $path
     * @return array - defined constants (names)
     */
    public static function collect($path)
    {
        self::$stack = array();

        self::compute($path);

        // eliminate duplicated entries
        self::$stack = array_values(array_unique(self::$stack));

        return self::$stack;
    }

    /**
     * @param string $path
     * @return void
     */
    private static function compute($path)
    {
        if (is_file($path)) {
            self::$stack = array_merge(self::$stack, self::readConstantsFromText(file_get_contents($path)));
        } elseif (is_dir($path)) {
            foreach (scandir($path) as $file) {
                if ('.' !== $file && '..' !== $file) {
                    self::compute($path . '/' . $file);
                }
            }
        }
    }

    /**
     * @param string $text
     * @return array
     */
    private static function readConstantsFromText($text)
    {
        $spacerChars = ' ' . chr(9);
        $spacer = '[' . $spacerChars . ']*';
        $pattern = '`define'.$spacer.'\('.$spacer.'(\'|")(.+)(\'|")`U';
        /* @var array $matches */
        preg_match_all($pattern, $text, $matches, PREG_PATTERN_ORDER);
        if (isset($matches[2]) && is_array($matches[2])) {
            return $matches[2];
        }
        return array();
    }
}
