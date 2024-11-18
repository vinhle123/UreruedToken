<?php

namespace Urerued\UreruedToken;

class TokenManager
{
    const TOKEN_KEY = 'token_key';

    private $excludedActions = [];

    public function __construct(array $excludedActions = [])
    {
        $this->excludedActions = $excludedActions;
    }

    public function createToken()
    {
        $token = uniqid(bin2hex(random_bytes(32)), true);
        $_SESSION[self::TOKEN_KEY] = $token;
        return $token;
    }

    public function getToken()
    {
        return $_SESSION[self::TOKEN_KEY] ?? null;
    }

    public function verifyToken($token)
    {
        $storedToken = $this->getToken();
        return $storedToken && $storedToken === $token;
    }

    public function checkCsrf($controller)
    {
        $action = $controller->getRequest()->getParam('action');
        
        if (in_array($action, $this->excludedActions)) {
            return true;
        }

        if ($controller->getRequest()->is('post')) {
            $tokenFromRequest = $controller->getRequest()->getData('_csrfToken');
            if (!$this->verifyToken($tokenFromRequest)) {
                throw new \Exception('CSRF token mismatch');
            }
        }
        
        return true;
    }

    public function deleteToken()
    {
        unset($_SESSION[self::TOKEN_KEY]);
    }
}
