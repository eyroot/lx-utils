<?php

namespace Lx\Utils\CodeCleanUp;

class CodeCleanUpResult
{
    /**
     * When running in dry mode
     *   the content of the files will
     *   not be changed, but only
     *   made available in
     *   $filesChangedContentDryRun
     * @var string[]
     */
    public $filesChanged = array();

    /**
     * Errors during run
     * @var string[]
     */
    public $errors = array();

    /**
     * The new processed content
     *   for the changed files,
     *   when running in dry mode
     * @var mixed[] - key_file_path -> ['original' => '', 'processed' => '']
     */
    public $filesChangedContentDryRun = array();
}
