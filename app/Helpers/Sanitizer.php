<?php

namespace App\Helpers;

class Sanitizer
{

    public static function sanitizeHtml(?string $html): ?string
    {
        if (is_null($html)) {
            return null;
        }

        $allowedTags = '<p><br><b><i><u><strong><em><ul><ol><li><a><h1><h2><h3><h4><h5><h6><blockquote><code><pre><span>';

        return strip_tags($html, $allowedTags);
    }

    public static function sanitizeText(?string $text): ?string
    {
        if (is_null($text)) {
            return null;
        }

        return strip_tags($text);
    }

    public static function sanitizeEmail(?string $email): ?string
    {
        if (is_null($email)) {
            return null;
        }

        return filter_var(trim($email), FILTER_SANITIZE_EMAIL);
    }
}
