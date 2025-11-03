<?php

if (!function_exists('formatHTML')) {
    function formatHTML(string $html): string
    {
        // Remove existing whitespace between tags
        $html = preg_replace('/>\s+</', '><', $html);

        // Define self-closing tags
        $selfClosing = [
            'area',
            'base',
            'br',
            'col',
            'embed',
            'hr',
            'img',
            'input',
            'link',
            'meta',
            'param',
            'source',
            'track',
            'wbr'
        ];

        if ($html  === null || trim($html) === '') {
            return '';
        }

        $formatted = '';
        $indent = 0;
        $indentString = '    '; // 4 spaces, you can change to "\t" for tabs

        // Split HTML into tags and text content
        $tokens = preg_split('/(<[^>]+>)/', $html, -1, PREG_SPLIT_DELIM_CAPTURE | PREG_SPLIT_NO_EMPTY);

        if ($tokens === false) {
            return trim($html);
        }

        foreach ($tokens as $token) {
            $token = trim($token);
            if (empty($token)) continue;

            // Check if token is a tag
            if (preg_match('/^</', $token)) {
                // Closing tag
                if (preg_match('/^<\//', $token)) {
                    $indent--;
                    $formatted .= str_repeat($indentString, max(0, $indent)) . $token . "\n";
                }
                // Self-closing tag or tag that ends with />
                elseif (preg_match('/\/>$/', $token)) {
                    $formatted .= str_repeat($indentString, $indent) . $token . "\n";
                }
                // Check if it's a self-closing tag without />
                elseif (preg_match('/<(' . implode('|', $selfClosing) . ')[\s>]/i', $token)) {
                    $formatted .= str_repeat($indentString, $indent) . $token . "\n";
                }
                // Opening tag
                else {
                    $formatted .= str_repeat($indentString, $indent) . $token . "\n";
                    $indent++;
                }
            }
            // Text content
            else {
                $formatted .= str_repeat($indentString, $indent) . $token . "\n";
            }
        }

        return trim($formatted);
    }
}
