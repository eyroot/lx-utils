<?php

namespace Lx\Utils\CodeCleanUp\Task;

interface TaskInterface
{
    /**
     * @param string $data
     * @return string
     */
    public function process($data);
}
