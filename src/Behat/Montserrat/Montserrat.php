<?php

namespace Behat\Montserrat;

use Symfony\Component\Process\Process;
use Behat\Montserrat\Exception\ExpectationException;

class Montserrat
{
    protected $workingDir = array();

    protected $prependPath;

    /**
     * @var Process
     */
    protected $process;


    /**
     * @param string $workingDir - Working dir for files
     * @param string $prependPath - string to prepend to path
     */
    public function __construct($workingDir, $prependPath)
    {
        $this->workingDir[] = $workingDir;
        $this->prependPath = $prependPath;
    }


    /**
     * Create a dir
     *
     * @param string $dirName
     */
    public function createDir($dirName)
    {
        $this->inCurrentDir(function($mst) use ($dirName) {
            $mst->_mkdir($dirName);
        });
    }

    /**
     * Write to a file
     *
     * @param string $fileName
     * @param string $fileContent
     */
    public function writeFile($fileName, $fileContent)
    {
        $this->createFile($fileName, $fileContent);
    }

    /**
     * Write a fixed size file
     *
     * @param string $fileName
     * @param int $fileSize
     */
    public function writeFixedSizeFile($fileName, $fileSize)
    {
        $this->createFile($fileName, '');
        $this->inCurrentDir(function() use ($fileName, $fileSize) {
            $fh = fopen($fileName, 'w');
            fseek($fh, $fileSize - 1);
            fwrite($fh, "\0", 1);
            fclose($fh);
        });
    }

    /**
     * Check a file's size
     *
     * @param string $fileName
     * @param int $fileSize
     */
    public function checkFileSize($fileName, $fileSize)
    {
        $this->inCurrentDir(function() use ($fileName, $fileSize) {
            if (filesize($fileName) !== $fileSize) {
                throw new ExpectationException("Size of $fileName not equals $fileSize");
            }
        });
    }

    /**
     * Run a simple command
     *
     * @param string $cmd
     */
    public function runSimple($cmd)
    {
        $this->run($cmd);
    }

    /**
     * Tear down the working dir
     *
     */
    public function tearDown()
    {
        $this->workingDir = array_slice($this->workingDir, 0, 1);
        exec("rm -rf " . escapeshellarg($this->currentDir()));
    }

    /**
     * Assert the exit status
     *
     * @param int $expected
     */
    public function assertExitStatus($expected)
    {
        $actual = $this->process->getExitCode();

        if ($actual !== $expected) {
            throw new ExpectationException("$actual did not match expected $expected");
        }
    }

    /**
     * Assert not the exit status
     *
     * @param int $expected
     */
    public function assertNotExitStatus($expected)
    {
        $actual = $this->process->getExitCode();

        if ($actual === $expected) {
            throw new ExpectationException("$actual matched unexpected $expected");
        }
    }

    /**
     * Assert partial output
     *
     * @param string $expected
     * @param string $actual
     */
    public function assertPartialOutput($expected, $type = 'stdout')
    {
        $expected = (string) $expected;
        $actual = $this->getOutputByType($type);

        if (false === strpos($actual, $expected)) {
            throw new ExpectationException("Failed to assert \"$actual\" contains \"$expected\"");
        }
    }

    /**
     * Assert partial output
     *
     * @param string $expected
     * @param string $actual
     */
    public function assertNotPartialOutput($expected, $type = 'stdout')
    {
        $expected = (string) $expected;
        $actual = $this->getOutputByType($type);

        if (false !== strpos($actual, $expected)) {
            throw new ExpectationException("Failed to assert \"$actual\" does not contain \"$expected\"");
        }
    }

    /**
     * In the current directory
     *
     * @param Closure $closure
     */
    public function inCurrentDir(\Closure $closure)
    {
        $old = getcwd();
        $this->_mkdir($this->currentDir());
        chdir($this->currentDir());
        $closure($this);
        chdir($old);
    }

    /**
     * Get the current directory
     *
     * @return string
     */
    public function currentDir()
    {
        return implode(DIRECTORY_SEPARATOR, $this->workingDir);
    }

    /**
     * Change directory
     *
     * @param string $dir
     */
    public function cd($dir)
    {
        $this->workingDir[] = $dir;
        if (!is_dir($this->currentDir())) {
            throw new ExpectationException($this->currentDir() . " is not a directory");
        }
    }

    /**
     * Make dir
     *
     * Note this is public only for use within closures and 5.3, hence the 
     * underscore
     *
     * @param string $dirName
     */
    public function _mkdir($dirName)
    {
        if (is_dir($dirName)) {
            return;
        }

        $success = mkdir($dirName, 0777, true);
        
        if (!$success) {
            throw new ExpectationException("Could not create $path");
        }
    }

    /**
     * Create a file
     *
     * @param string $fileName
     * @param string $fileContent
     */
    protected function createFile($fileName, $fileContent)
    {
        $this->inCurrentDir(function($mst) use ($fileName, $fileContent) {
            $mst->_mkdir(dirname($fileName));

            $success = file_put_contents($fileName, $fileContent);

            if (false === $success) {
                throw new ExpectationException("Could not write to file $fileName");
            }
        });
    }

    /**
     * Run a command
     *
     * @param string $cmd
     */
    protected function run($cmd)
    {
        $path = getenv('PATH');

        if ($this->prependPath) {
            $path = $this->prependPath . PATH_SEPARATOR . $path;
        }

        $process = new Process($cmd, $this->currentDir(), array(
            'PATH' => $path,
        ));

        $process->run();

        $this->process = $process;

        return $process;
    }

    /**
     * Get output by type
     *
     * @param string $type
     * @return string
     */
    protected function getOutputByType($type)
    {
        switch ($type) {
            case 'stderr':
                $actual = $this->process->getErrorOutput();
                break;

            case 'all':
                $actual = $this->process->getOutput() . $this->process->getErrorOutput();
                break;

            case 'stdout':
            default:
                $actual = $this->process->getOutput();
                break;
        }

        return $actual;
    }
}
