<?php

namespace App\Interfaces;

interface ModelSection
{
    public static function getStatuses(): array;

    public function showStatus(): string;

    public function scopeActive($query);

    public static function getMenuConfig(): array;

    public function frontLink(): string;

    public static function backLink($id): string;

    public static function getOptionsHTML($selected = null): string;

    public static function showCardMenu($tag): string;

    public function hasLang($lang): bool;

    public static function sitemapGenerate($changefreq, $priority): string;
}
