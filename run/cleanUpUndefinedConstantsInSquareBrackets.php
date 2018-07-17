<?php

require(__DIR__ . '/../vendor/autoload.php');

if (!(isset($_SERVER['argv'][1]) && strlen($_SERVER['argv'][1]) > 0)) {
    echo 'Missing path argument';
    exit;
}

$pathToCleanUp = $_SERVER['argv'][1];

use Lx\Utils\CodeCleanUp\CodeCleanUp;

$result = (new CodeCleanUp())
    ->addFilePath($pathToCleanUp)
    ->addFileExtension('php')
    ->addTask(CodeCleanUp::TASK_QUOTE_UNDEFINED_CONSTANTS_IN_SQUARE_BRACKETS)
    ->run()
;

echo "\n\nChanged files:\n" . implode("\n", $result->filesChanged);

echo "\n\nErrors:\n" . implode("\n", $result->errors);
