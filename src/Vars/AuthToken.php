<?php

namespace App\Vars;

class AuthToken
{
    // const TOKEN_EXP = 86400; // 60 * 60 * 24 = 1 day
    const TOKEN_EXP = 1; // 1 day

    /**
     * https://davidwalsh.name/random_bytes
     * https://stackoverflow.com/questions/18830839/generating-cryptographically-secure-tokens
     * Generate token
     */
    public static function generate()
    {
        return bin2hex(openssl_random_pseudo_bytes(64));
        // # or in php7
        // $token = bin2hex(random_bytes(16));
    }
}
