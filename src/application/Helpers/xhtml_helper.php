<?php

declare(strict_types=1);

/**
 * Convert characters to XHTML entities
 *
 * @param string $string string to convert
 *
 * @return string
 */
function xhentities(string $string): string
{
    return htmlentities($string, ENT_QUOTES | ENT_XHTML, 'UTF-8', true);
}
