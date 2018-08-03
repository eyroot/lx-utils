<?php

namespace LxTesting\Utils;

use Lx\Utils\CodeCleanUp\CodeCleanUp;

class TestCaseCodeCleanUpMix extends TestCaseGeneric
{
    /**
     * Content test with original and expected data defined under
     *   LX_TESTING_UTILS_CODECLEANUP_TESTING_DATA_DIR_MIXES/{$mixName}
     * @var array - list of array members with keys 'original' and 'expected' (values)
     */
    protected $contentTest = array();

    /**
     * Task name
     * @var string
     */
    protected $mixName;

    /**
     * {@inheritDoc}
     * @see \PHPUnit\Framework\TestCase::setUp()
     */
    protected function setUp()
    {
        $classNameTest = get_class($this);
        $this->mixName = substr(
            $classNameTest,
            strrpos($classNameTest, '\\') + 1,
            -4
        );

        $pathDataTest = LX_TESTING_UTILS_CODECLEANUP_TESTING_DATA_DIR_MIXES . '/' . $this->mixName;

        foreach (scandir($pathDataTest) as $file) {
            if ('.' != $file && '..' != $file
                && is_file($pathDataTest . '/' . $file)
                && false === strpos($file, '_expected')) {
                    $this->contentTest[] = array(
                        'original' => file_get_contents($pathDataTest . '/' . $file),
                        'expected' => file_get_contents($pathDataTest . '/' .
                            str_replace('.', '_expected.', $file)),
                        'fileName' => substr($file, 0, strpos($file, '.')),
                        'filePath' => $pathDataTest . '/' . $file
                    );
                }
        }
    }

    /**
     * @return \Lx\Utils\CodeCleanUp\CodeCleanUp
     */
    protected function getCodeCleanUpFreshInstance()
    {
        return (new CodeCleanUp())
            ->addFileExtension('php')
            ->enableDryRun();
    }
}
