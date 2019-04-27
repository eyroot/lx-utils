<?php

$array = array();

echo <<<HEREDOC
Random text variable $array[key] inside heredoc
string definition
HEREDOC;

echo <<<eod1

Another
heredoc $array[key1][key2] test
here
heredoc {$array[key1]} test
heredoc {$array['key1']} test
here yes

eod1;
