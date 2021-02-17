<?php

declare(strict_types=1);

namespace App\Tests\Unit\Application\Service;

use App\Application\Service\AuthService;
use PHPUnit\Framework\TestCase;

class AuthServiceTest extends TestCase
{
    public function testCheckAuth(): void
    {
        $authToken = '1234567';
        $authService = new AuthService($authToken);

        self::assertEquals(
            true,
            $authService->checkAuth($authToken)
        );

        self::assertEquals(
            false,
            $authService->checkAuth('123')
        );
    }
}