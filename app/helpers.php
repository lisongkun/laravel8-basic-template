<?php


if (!function_exists('admin')) {
    function admin()
    {
        return auth('admin');
    }
}


if (!function_exists('respondWithToken')) {
    /**
     * Get the token array structure.
     *
     * @param string $token
     *
     * @return array
     */
    function respondWithToken($token)
    {
        return [
            'access_token' => $token,
            'token_type' => 'bearer'
        ];
    }
}
