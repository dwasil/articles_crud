<?php

namespace App\Application\Service;

/**
 * Class AuthService
 * @package App\Application\Service
 * Check auth with static token
 */
class AuthService
{
    private string $authToken;

    /**
     * AuthService constructor.
     * @param string $authToken
     */
    public function __construct(string $authToken)
    {
        $this->authToken = $authToken;
    }

    /**
     * Check auth token
     * @param string $requestToken
     * @return bool
     */
    public function checkAuth(string $requestToken): bool
    {
        return $requestToken === $this->authToken;
    }
}