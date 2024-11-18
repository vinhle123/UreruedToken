<?php

namespace Urerued\UreruedToken;

use Illuminate\Encryption\Encrypter;
use Illuminate\Session\Store;

class CsrfTokenManager
{
    protected $session;
    protected $encrypter;

    public function __construct(Store $session, Encrypter $encrypter)
    {
        $this->session = $session;
        $this->encrypter = $encrypter;
    }

    /**
     * Generate a CSRF token and store it in session.
     * @return string The generated token.
     */
    public function generateToken()
    {
        // Create a random token
        $token = bin2hex(random_bytes(32));

        // Encrypt the token for added security
        $encryptedToken = $this->encrypter->encrypt($token);

        // Store the encrypted token in session
        $this->session->put('_csrf_token', $encryptedToken);

        return $token;
    }

    /**
     * Validate the CSRF token.
     * @param string $token The token to validate.
     * @return bool True if the token is valid, false otherwise.
     */
    public function validateToken($token)
    {
        // Get the encrypted token from session
        $sessionToken = $this->session->get('_csrf_token');

        if (!$sessionToken) {
            return false;
        }

        // Decrypt the token from session and compare with the input token
        try {
            $decryptedToken = $this->encrypter->decrypt($sessionToken);
            return hash_equals($decryptedToken, $token);
        } catch (\Exception $e) {
            return false;
        }
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
