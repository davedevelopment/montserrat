<?php 

namespace Behat\Montserrat\Context;

use Behat\Behat\Context\BehatContext;
use Behat\Gherkin\Node\PyStringNode;
use Behat\Gherkin\Node\TableNode;
use Behat\Montserrat\Montserrat;

class MontserratContext extends BehatContext implements MontserratAwareInterface
{
    protected $montserrat;

    /**
     * Set Montserrat Instance
     *
     * @param Montserrat $montserrat
     */
    public function setMontserrat(Montserrat $montserrat)
    {
        $this->montserrat = $montserrat;
    }

    /**
     * Get Montserrat
     *
     * @return Montserrat $montserrat
     */
    public function getMontserrat()
    {
        return $this->montserrat;
    }


    /**
     * @Given /^a directory named "([^"]*)"$/
     */
    public function aDirectoryName($dirName)
    {
        $this->getMontserrat()->createDir($dirName);
    }

    /**
     * @Given /^a file named "([^"]*)" with:$/
     */
    public function aFileNamedWith($fileName, PyStringNode $fileContent)
    {
        $this->getMontserrat()->writeFile($fileName, $fileContent);
    }

    /**
     * @When /^I run `([^`]*)`$/
     */
    public function iRun($cmd)
    {
        // escaping etc?
        $this->getMontserrat()->runSimple($cmd);
    }

    /**
     * @Then /^the stdout should contain "([^"]*)"$/
     */
    public function theStdoutShouldContainString($string)
    {
        $this->getMontserrat()->assertPartialOutput($string);
    }

    /**
     * @Then /^the stdout should contain:$/
     */
    public function theStdoutShouldContain(PyStringNode $string)
    {
        $this->getMontserrat()->assertPartialOutput($string);
    }

    /**
     * @Then /^the output should contain:$/
     */
    public function theOutputShouldContain(PyStringNode $string)
    {
        $this->getMontserrat()->assertPartialOutput($string, 'all');
    }

    /**
     * @Then /^the output should contain "([^"]*)"$/
     */
    public function theOutputShouldContain2($string)
    {
        $this->getMontserrat()->assertPartialOutput($string, 'all');
    }

    /**
     * @Then /^the output should not contain "([^"]*)"$/
     */
    public function theOutputShouldNotContain($string)
    {
        $this->getMontserrat()->assertNotPartialOutput($string, 'all');
    }

    /**
     * @Then /^the output should not contain:$/
     */
    public function theOutputShouldNotContain2(PyStringNode $string)
    {
        $this->getMontserrat()->assertNotPartialOutput($string, 'all');
    }

    /**
     * @Given /^a (\d+) byte file named "([^"]*)"$/
     */
    public function aByteFileNamed($fileSize, $fileName)
    {
        $this->getMontserrat()->writeFixedSizeFile($fileName, intval($fileSize));
    }

    /**
     * @Given /^an empty file named "([^"]*)"$/
     */
    public function anEmtyFileName($fileName)
    {
        $this->getMontserrat()->writeFile($fileName, "");
    }

    /**
     * @Then /^a (\d+) byte file named "([^"]*)" should exist$/
     */
    public function aByteFileNamedShouldExist($fileSize, $fileName)
    {
        $this->getMontserrat()->checkFileSize($fileName, intval($fileSize));
    }

    /**
     * @Then /^the exit status should (not |)be (\d+)$/
     */
    public function theExitStatusShouldBe($not, $status)
    {
        if ($not) {
            return $this->getMontserrat()->assertNotExitStatus(intval($status));
        }

        $this->getMontserrat()->assertExitStatus(intval($status));
    }

    /**
     * @When /^I cd to "([^"]*)"$/
     */
    public function iCdTo($dir)
    {
        $this->getMontserrat()->cd($dir);
    }

}
