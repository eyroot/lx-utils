<?php

$array = [];
$array['key'] = 'value';

echo "string without " . "" . " variables";
echo "escaped quote example\"";
echo "another escaped quote example\\\"";


echo "I am an old coding style variable: {$array['key']}, but still a variable";
echo "Another example {$array['key']} here";
echo "Another example {$array['key']}";
echo "{$array['key']} is an example";
echo "{$array['key']}";

function testFunction($a) { return $a . "_x"; }
testFunction("param {$array['key']} abc");

echo "Another use case {$array['key1']} inside a string" . " and on the same line {$array['key2']}.";

echo "Another use
case {$array['key']} inside a
    multiline string";

echo "I am almost properly defined here {$array['key']} and only need a minor adjustment :)";
echo "I am already properly defined here {$array['key']} and must remain as I am";

// multidimensional arrays inside quotes
echo "{$array['key1']['key2']}";
echo "{$array['key1']['key2']['key3']}";
