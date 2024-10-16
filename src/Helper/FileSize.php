<?php

declare(strict_types=1);

namespace App\Helper;

class FileSize
{
    public static function humanize(
        int $bytes,
        int $decimals = 2,
        array $sizes = ['B', 'KB', 'MB', 'GB', 'TB', 'PB', 'EB', 'ZB', 'YB']
    ): string {
        $power = 0;
        $temp  = $bytes;
        $count = \count($sizes);
        for ($i = 0; $i < $count; $i++) {
            if ($temp < 1024) {
                break;
            } else {
                $temp /= 1024;
                $power++;
            }
        }

        if (isset($sizes[$power])) {
            $humanize = \sprintf("%.{$decimals}f", ($bytes / \pow(1024, $power))) . $sizes[$power];
        } else {
            $humanize = \sprintf("%d", $bytes) . 'B';
        }

        return $humanize;
    }
}
