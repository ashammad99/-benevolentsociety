<?php

namespace App\Support;

trait SanitizesRichText
{
    /**
     * Strip a rich-text HTML string down to the formatting the admin
     * contenteditable toolbars can actually produce, before it's stored or
     * validated. Prevents a trusted-but-not-fully-trusted admin/editor
     * account from storing arbitrary HTML/JS that later executes unescaped
     * wherever the field is rendered with {!! !!}.
     */
    private function sanitizeRichText(?string $html): ?string
    {
        if ($html === null) {
            return null;
        }

        $allowedTags = '<p><div><span><b><i><u><strong><em><ul><ol><li><br>';
        $stripped = strip_tags($html, $allowedTags);

        // None of the allowed tags need attributes for bold/italic/underline/
        // lists, so drop every attribute from whatever tags survive — this is
        // what actually closes off onclick=, style=, src=, href=, etc.
        return preg_replace('/<([a-z]+)[^>]*>/i', '<$1>', $stripped);
    }
}
