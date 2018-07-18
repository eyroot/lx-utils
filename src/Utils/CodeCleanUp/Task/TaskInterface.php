<?php

namespace Lx\Utils\CodeCleanUp\Task;

interface TaskInterface
{
    /**
     * Process file content
     * @param string $data - full file content
     * @return string - processed file content
     */
    public function process($data);
}
