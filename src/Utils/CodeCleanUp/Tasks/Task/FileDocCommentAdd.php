<?php

namespace Lx\Utils\CodeCleanUp\Tasks\Task;

use Lx\Utils\CodeCleanUp\Tasks\TaskInterface;
use Lx\Utils\CodeCleanUp\Tasks\TaskAbstract;
use Lx\Utils\CodeCleanUp\Tasks\TaskOptions\FileDocCommentAddOption;

class FileDocCommentAdd extends TaskAbstract implements TaskInterface
{
    protected $optionsAvailable = array(
        FileDocCommentAddOption::TEXT
    );

    protected $optionsRequired = array(
        FileDocCommentAddOption::TEXT
    );

    /**
     * {@inheritDoc}
     * @see \Lx\Utils\CodeCleanUp\Tasks\TaskInterface::process()
     */
    public function process($data)
    {
        if (0 === strpos($data, '<?php')) {
            $text = str_replace("\n", "\n * ", $this->getOption(FileDocCommentAddOption::TEXT));
            $docBlock = '<?php

/**
 * ' . $text . '
 */';
            $data = $docBlock . substr($data, 5);
        }

        return $data;
    }
}
