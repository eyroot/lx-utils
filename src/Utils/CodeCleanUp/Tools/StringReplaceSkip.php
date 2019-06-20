<?php

namespace Lx\Utils\CodeCleanUp\Tools;

/**
 * Collect strings intended to be ignored by the parser
 * replace them with encoded tokens
 * and decode them after the main string processing done by the tasks
 */
class StringReplaceSkip
{
    /**
     * @var array
     */
    private $groupedStrings = array();

    /**
     * Pairs of encoded and decoded strigs (entire map of pairs)
     * [groupName][encodedValue] = 'original value'
     *
     * @var array
     */
    private $encodingMap = array();

    /**
     * @param string $namespace
     * @param array $strings
     */
    public function __construct($group, $strings)
    {
        $this->groupedStrings[$group] = $strings;
        $this->generateEncodingmap();
    }

    /**
     * Compute the encoded version of each string
     * Store it internally in the class itself
     * Will be used for encoding and decoding the string
     */
    private function generateEncodingmap()
    {
        $randomString = bin2hex(random_bytes(10));
        foreach ($this->groupedStrings as $group => $strings) {
            $this->encodingMap[$group] = array();
            foreach ($strings as $string) {
                $this->encodingMap[$group][] = array(
                    'string_original' => $string,
                    'string_encoded' => md5($group . $randomString . $string)
                );
            }
        }
    }

    /**
     * Encode the $strings found in text
     *
     * @param string $text
     *
     * @return string
     */
    public function textEncode($text)
    {
        foreach ($this->encodingMap as $groupEncoding) {
            foreach ($groupEncoding as $values) {
                $text = str_replace($values['string_original'], $values['string_encoded'], $text);
            }
        }
        return $text;
    }

    /**
     * Decode the $strings found in text
     *
     * @param string $text
     *
     * @return string
     */
    public function textDecode($text)
    {
        foreach ($this->encodingMap as $groupEncoding) {
            foreach ($groupEncoding as $values) {
                $text = str_replace($values['string_encoded'], $values['string_original'], $text);
            }
        }
        return $text;
    }
}
