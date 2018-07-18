<?php

namespace Lx\Utils\CodeCleanUp\Tasks;

interface TaskInterface
{
    /**
     * Process file content
     * @param string $data - full file content
     * @return string - processed file content
     */
    public function process($data);

    /**
     * @see \Lx\Utils\CodeCleanUp\Tasks\TaskAbstract::setOptions()
     */
    public function setOptions($options);
}
