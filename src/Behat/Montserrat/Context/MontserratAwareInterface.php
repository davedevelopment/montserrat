<?php

namespace Behat\Montserrat\Context;

use Behat\Montserrat\Montserrat;

interface MontserratAwareInterface
{
    /**
     * Sets Montserrat Instance
     *
     * @param Montserrat $montserrat
     */
    public function setMontserrat(Montserrat $montserrat);
}
