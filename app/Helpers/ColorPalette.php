<?php


namespace App\Helpers;

class ColorPalette
{
    public static array $colors = [
        '#6366f1' => ['name' => 'Indigo',  'bg' => '#eef2ff', 'text' => '#4338ca'],
        '#378ADD' => ['name' => 'Blue',    'bg' => '#E6F1FB', 'text' => '#185FA5'],
        '#639922' => ['name' => 'Green',   'bg' => '#EAF3DE', 'text' => '#3B6D11'],
        '#7F77DD' => ['name' => 'Purple',  'bg' => '#EEEDFE', 'text' => '#534AB7'],
        '#D85A30' => ['name' => 'Coral',   'bg' => '#FAECE7', 'text' => '#993C1D'],
        '#D4537E' => ['name' => 'Pink',    'bg' => '#FBEAF0', 'text' => '#993556'],
        '#BA7517' => ['name' => 'Amber',   'bg' => '#FAEEDA', 'text' => '#854F0B'],
        '#1D9E75' => ['name' => 'Teal',    'bg' => '#E1F5EE', 'text' => '#0F6E56'],
    ];

    public static function all(): array
    {
        return self::$colors;
    }

    public static function bg(string $hex): string
    {
        return self::$colors[$hex]['bg'] ?? '#f3f4f6';
    }

    public static function text(string $hex): string
    {
        return self::$colors[$hex]['text'] ?? '#374151';
    }

    public static function name(string $hex): string
    {
        return self::$colors[$hex]['name'] ?? 'Default';
    }
}