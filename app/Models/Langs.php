<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Throwable;

/**
 * Class Langs
 * @package App\Models
 *
 * @property string $code
 * @property string $short_name
 * @property string $name
 * @property string $icon
 * @property boolean $default
 *
 * @property array $getLangCodes
 * @property string $getDefaultLangCode
 * @property array $getLangsWithTitle
 */
class Langs extends Model
{
    protected $table = 'langs';

    protected $guarded = [];

    protected $casts = [
        'default' => 'boolean'
    ];

    public $timestamps = false;

    /**
     * @return array
     */
    public static function getLangCodes(): array
    {
        return array_keys(config('translatable.locale_codes'));
    }

    /**
     * @return string
     */
    public static function getDefaultLangCode()
    {
        return config('translatable.locale');
    }

    /**
     * @return array
     */
    public static function getLangsWithTitle(): array
    {
        return config('translatable.locales');
    }

    /**
     * @return array
     */
    public static function getLangsWithTitleShort(): array
    {
        return config('translatable.locale_codes');
    }
}
