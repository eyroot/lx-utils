<?php

namespace Lx\Utils\CodeCleanUp\Tasks;

class TaskAbstract
{


    /**
     * List of option names available
     * @var array
     */
    protected $optionsAvailable = array();

    /**
     * List of option names required
     * @var array
     */
    protected $optionsRequired = array();

    /**
     * List of set options
     * @var array
     */
    protected $options = array();

    /**
     * Defined constants
     * @var array
     */
    private $definedConstants = array();

    /**
     * Set options
     * @param array $options - defined in TaskOptions/{$taskName}Option
     */
    public function setOptions($options)
    {
        // check provided option name exists
        foreach (array_keys($options) as $name) {
            if (!in_array($name, $this->optionsAvailable, true)) {
                throw new TaskException('Not supported option name ['.$name.']');
            }
        }

        // check mandatory option names are present
        foreach ($this->optionsRequired as $name) {
            if (!isset($options[$name])) {
                throw new TaskException('Mandatory option name ['.$name.'] not provided');
            }
        }

        $this->options = $options;
    }

    /**
     * Get option value
     * @param string $name
     * @return mixed
     */
    protected function getOption($name)
    {
        if (!isset($this->options[$name])) {
            throw new TaskException('Trying to read option name ['.$name.'] which does not exist');
        }
        return $this->options[$name];
    }

    /**
     * Get defined constants names
     * @return array
     */
    protected function getDefinedConstants()
    {
        return $this->definedConstants;
    }

    /**
     * Set defined constants names
     * @param array $definedConstants
     */
    public function setDefinedConstants($definedConstants)
    {
        $this->definedConstants = $definedConstants;
    }
}
