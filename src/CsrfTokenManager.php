<?php

namespace Urerued\UreruedToken;
use Illuminate\Encryption\Encrypter;
use Illuminate\Session\Store;

class CsrfTokenManager
{
    /**
     * Generate a CSRF token for the session (each request).
     * @return string The generated token.
     */
    public function generateToken()
    {
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }

        // Generate a new random token for each request
        $token = bin2hex(random_bytes(32));

        // Store it in the session to validate it later
        $_SESSION['_csrf_token'] = $token;

        return $token;
    }

    /**
     * Validate the CSRF token.
     * @param string $token The token to validate.
     * @return bool True if the token is valid, false otherwise.
     */
    public function validateToken($token)
    {
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }

        // Check if the token matches the session token
        return isset($_SESSION['_csrf_token']) && hash_equals($_SESSION['_csrf_token'], $token);
    }

    /**
     * Add CSRF token to the form as hidden field.
     * @return string HTML code for the hidden CSRF token field.
     */
    public function csrfField()
    {
        // Generate a new CSRF token every time
        $token = $this->generateToken();
        return '<input type="hidden" name="_csrf_token" value="' . htmlspecialchars($token) . '">';
    }
}
