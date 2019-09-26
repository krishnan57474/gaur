<?php

declare(strict_types=1);

/**
 * Convert characters to HTML5 entities
 *
 * @param string $string string to convert
 *
 * @return string
 */
function hentities(string $string): string
{
    return htmlentities($string, ENT_QUOTES | ENT_HTML5, 'UTF-8', true);
}
