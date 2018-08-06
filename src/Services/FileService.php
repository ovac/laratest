<?php

namespace Yab\LaraTest\Services;

class FileService
{
    public function getLine($contents, $line, $multiple = false)
    {
        $pattern = preg_quote($line, '/');
        $pattern = "/^.*$pattern.*\$/m";

        if (preg_match_all($pattern, $contents, $matches)) {
            if ($multiple) {
                return $matches[0];
            }

            return $matches[0][0];
        }

        return '';
    }
}
