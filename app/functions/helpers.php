<?php

declare(strict_types=1);

namespace Core\functions;


define('REFRESH_TOKEN', 'refresh-token');
define('JWT_NAME', 'jwt-token');

/**
 * Redirect to a new page.
 *
 * @param string $path
 */
function redirect($path): void
{
    header("Location: /{$path}");
}

// provides a dump & die helper
if (!function_exists('dd')) {
    function dd()
    {
        $args = func_get_args();
        call_user_func_array('dump', $args);

        exit();
    }
}

// provides a dump helper
if (!function_exists('d')) {
    function d()
    {
        $args = func_get_args();
        call_user_func_array('dump', $args);
    }
}

/**
 * provides a hashed string.
 */
if (!function_exists('manoucheHash')) {
    function manoucheHash(string $password, array $options = ['cost' => 8]): string
    {
        return password_hash($password, PASSWORD_BCRYPT, $options);
    }
}

/**
 * Checks if passwords match.
 */
function manoucheCheck(string $plainText, string $hash): bool
{
    return password_verify($plainText, $hash);
}

/**
 * Provides information about the system's mode
 */
if (!function_exists('mode')) {
    function mode(): string
    {
        return $_ENV['MODE'] ?? '';
    }
}

/**
 * Provides information about the system's mode, whether it is in production mode or not.
 */
if (!function_exists('inTesting')) {
    function inTesting()
    {
        $mode = $_ENV['MODE'] ?? '';
        return $mode === 'TEST';
    }
}

/**
 * Provides information about the system's mode, whether it is in production mode or not.
 */
if (!function_exists('isProd')) {
    function isProd()
    {
        $mode = $_ENV['MODE'] ?? '';
        return $mode === 'PRODUCTION';
    }
}

/**
 * Provides information about the system's mode, whether it is in dev mode or not.
 */
if (!function_exists('isDev')) {
    function isDev(): bool
    {
        $mode = $_ENV['MODE'] ?? '';
        return $mode === 'DEV';
    }
}

/**
 * Makes a string camel case
 */
if (!function_exists('toCamelCase')) {
    function toCamelCase(string $input, ?string $sepators = '_'): string
    {
        return str_replace("_", "", lcfirst(ucwords($input, $sepators)));
    }
}

/**
 * Creates a new array applying camel case to keys
 */
if (!function_exists('arrayToCamelCase')) {
    function arrayToCamelCase(array $array): array
    {
        $result = [];
        foreach ($array as $key => $value) {
            $result[toCamelCase($key)] = $value;
        }

        return $result;
    }
}