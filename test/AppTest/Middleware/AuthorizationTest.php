<?php

declare(strict_types=1);

namespace AppTest\Middleware;

use PHPUnit\Framework\TestCase;

final class AuthorizationTest extends TestCase
{
    public function testAuthorizedUserCanProceed()
    {
    }

    public function testNonAuthorizedUserReceivesA403()
    {
    }

    public function testRequestWithNoUserReturnsA403()
    {
    }
}
