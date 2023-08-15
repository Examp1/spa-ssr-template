<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Settings
 * @package App\Models
 *
 * @property integer $id
 * @property string $name
 * @property string $code
 * @property string $value
 * @property boolean $const
 * @property string $lang
 * @property Carbon $created_at
 * @property Carbon $updated_at
 */
class Settings extends Model
{
    use HasFactory;

    protected $table = 'settings';

    protected $guarded = [];

    protected $casts = [
        'const' => 'boolean'
    ];

    const TAB_MAIN            = 'main';
    const TAB_CONTACTS        = 'contacts';
    const TAB_BLOG            = 'blog';
    const TAB_BLOG_CATEGORIES = 'blogcategories';
    const TAB_PAGE            = 'page';
    const TAB_LANDING         = 'landing';
    const TAB_BLOG_TAGS       = 'blogtags';
    const TAB_THEME = 'theme';

    public static function getTabNames(): array
    {
        return [
            self::TAB_MAIN            => 'General',
            self::TAB_CONTACTS        => 'Contacts',
            self::TAB_BLOG            => 'Blog',
            self::TAB_PAGE            => 'Pages',
            self::TAB_LANDING         => 'Landings',
            self::TAB_BLOG_CATEGORIES => 'Categories',
            self::TAB_BLOG_TAGS       => 'Tags',
            self::TAB_THEME           => 'Тема',
        ];
    }
}
