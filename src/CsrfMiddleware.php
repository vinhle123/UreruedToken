<?php

namespace Urerued\UreruedToken;

class CsrfMiddleware
{
    protected $csrfManager;

    public function __construct(CsrfTokenManager $csrfManager)
    {
        $this->csrfManager = $csrfManager;
    }

    /**
     * Handle CSRF token validation for the incoming request.
     * @param array $request The request data.
     * @return bool True if CSRF token is valid, false otherwise.
     */
    public function validate($request)
    {
        // Only check CSRF for POST requests
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $token = isset($request['_csrf_token']) ? $request['_csrf_token'] : null;

            if (!$token || !$this->csrfManager->validateToken($token)) {
                return false; // Invalid CSRF token
            }
        }

        return true; // CSRF token is valid or method is not POST
    }

    public function validateData($request)
    {
       
        $token = isset($request['_csrf_token']) ? $request['_csrf_token'] : null;

        if (!$token || !$this->csrfManager->validateToken($token)) {
            return false; 
        }
        return true; // CSRF token is valid or method is not POST
    }
}
