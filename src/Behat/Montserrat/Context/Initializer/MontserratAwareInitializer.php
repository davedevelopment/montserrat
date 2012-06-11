<?php

namespace Behat\Montserrat\Context\Initializer;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;

use Behat\Behat\Context\Initializer\InitializerInterface,
    Behat\Behat\Context\ContextInterface,
    Behat\Behat\Event\ScenarioEvent,
    Behat\Behat\Event\OutlineEvent;

use Behat\Montserrat\Montserrat;

use Behat\Montserrat\Context\MontserratAwareInterface;

/**
 * This file is part of montserrat
 *
 * Copyright (c) 2012 Dave Marshall <dave.marshall@atstsolutuions.co.uk>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

class MontserratAwareInitializer implements InitializerInterface, EventSubscriberInterface
{
    private $montserrat;

    /**
     * Initializes initializer.
     *
     * @param Montserrat $montserrat
     */
    public function __construct(Montserrat $montserrat)
    {
        $this->montserrat = $montserrat;
    }

    /**
     * Returns an array of event names this subscriber wants to listen to.
     *
     * The array keys are event names and the value can be:
     *
     *  * The method name to call (priority defaults to 0)
     *  * An array composed of the method name to call and the priority
     *  * An array of arrays composed of the method names to call and respective
     *    priorities, or 0 if unset
     *
     * For instance:
     *
     *  * array('eventName' => 'methodName')
     *  * array('eventName' => array('methodName', $priority))
     *  * array('eventName' => array(array('methodName1', $priority), array('methodName2'))
     *
     * @return array The event names to listen to
     */
    public static function getSubscribedEvents()
    {
        return array(
            'beforeScenario' => 'tearDownWorkingDir',
        );
    }

    /**
     * Checks if initializer supports provided context.
     *
     * @param ContextInterface $context
     *
     * @return Boolean
     */
    public function supports(ContextInterface $context)
    {
        // if context/subcontext implements MinkAwareInterface
        if ($context instanceof MontserratAwareInterface) {
            return true;
        }

        return false;
    }

    /**
     * Initializes provided context.
     *
     * @param ContextInterface $context
     */
    public function initialize(ContextInterface $context)
    {
        $context->setMontserrat($this->montserrat);
    }

    /**
     * Tear down working dir
     *
     */
    public function tearDownWorkingDir()
    {
        $this->montserrat->tearDown();
    }

}
