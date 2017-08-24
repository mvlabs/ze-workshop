<?php

declare(strict_types=1);

use Behat\Behat\Context\Context;
use Behat\Behat\Tester\Exception\PendingException;

class FeatureContext implements Context
{
    /**
     * @Given I am an administrator user
     */
    public function iAmAnAdministratorUser()
    {
        throw new PendingException();
    }

    /**
     * @When I try to access administration functionalities
     */
    public function iTryToAccessAdministrationFunctionalities()
    {
        throw new PendingException();
    }

    /**
     * @Then I am granted access
     */
    public function iAmGrantedAccess()
    {
        throw new PendingException();
    }

    /**
     * @Given I am a non administrator user
     */
    public function iAmANonAdministratorUser()
    {
        throw new PendingException();
    }

    /**
     * @Then I am denied access
     */
    public function iAmDeniedAccess()
    {
        throw new PendingException();
    }

    /**
     * @Given I am a unknown user
     */
    public function iAmAUnknownUser()
    {
        throw new PendingException();
    }

}
