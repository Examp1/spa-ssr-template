<?php

namespace App\Models\Translations;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MenuTranslation extends Model
{
    use HasFactory;

    protected $table = 'menu_translations';

    public $timestamps = false;

    protected $fillable = [
        'name',
        'url'
    ];
}
