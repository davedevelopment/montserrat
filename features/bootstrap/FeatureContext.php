<?php

use Behat\Behat\Context\ClosuredContextInterface,
    Behat\Behat\Context\TranslatedContextInterface,
    Behat\Behat\Context\BehatContext,
    Behat\Behat\Exception\PendingException;
use Behat\Gherkin\Node\PyStringNode,
    Behat\Gherkin\Node\TableNode;

//
// Require 3rd-party libraries here:
//
//   require_once 'PHPUnit/Autoload.php';
//   require_once 'PHPUnit/Framework/Assert/Functions.php';
//

/**
 * Features context.
 */
class FeatureContext extends BehatContext
{
    protected $lastException = false;

    /**
     * Initializes context.
     * Every scenario gets it's own context object.
     *
     * @param   array   $parameters     context parameters (set them up through behat.yml)
     */
    public function __construct(array $parameters)
    {
        // Initialize your context here
        $this->useContext('montserrat', new Behat\Montserrat\Context\MontserratContext());
    }


    /**
     * @beforeScenario
     */
    public function tidyUp()
    {
        $this->lastException = false;
    }

    /**
     * @When /^I do montserrat I cd to "([^"]*)"$/
     */
    public function iDoMontserratICdTo($arg1)
    {
        // would be really nice to do this via the dispatcher
        try {
            $this->getSubContext('montserrat')->iCdTo($arg1);
        } catch (\Exception $e) {
            $this->lastException = $e;
        }
    }

    /**
     * @Then /^montserrat should fail with "([^"]*)"$/
     */
    public function montserratShouldFailWith($arg1)
    {
        if (!$this->lastException) {
            throw new \RunTimeException("No exception thrown");
        }

        if ($this->lastException->getMessage() !== $arg1) {
            throw new \RunTimeException("Failed asserting \"{$this->lastException->getMessage()}\" equals \"$arg1\"");
        }
    }


//
// Place your definition and hook methods here:
//
//    /**
//     * @Given /^I have done something with "([^"]*)"$/
//     */
//    public function iHaveDoneSomethingWith($argument)
//    {
//        doSomethingWith($argument);
//    }
//
}
