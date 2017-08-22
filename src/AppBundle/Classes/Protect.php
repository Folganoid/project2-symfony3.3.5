<?php

namespace AppBundle\Classes;

/**
 * protect entry field
 *
 * Class Protect
 * @package AppBundle\Classes
 */
class Protect
{
    public static function EntrySecure(string $str): string
    {
        return nl2br(htmlspecialchars(trim($str), ENT_QUOTES), false);
    }
}