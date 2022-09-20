<?php

use Stevebauman\Flash\Flash;

/**
 * Generates a session flash message.
 *
 * @param null|string $title
 * @param null|string $message
 *
 * @return null|Flash
 */
if (!function_exists('flash')) {
    function flash($title = null, $message = null)
    {
        $flash = new Flash();

        if (func_num_args() === 0) {
            return $flash;
        }

        $flash->info($title, $message);
    }
}
