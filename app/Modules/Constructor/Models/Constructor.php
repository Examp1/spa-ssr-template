<?php

namespace App\Modules\Constructor\Models;

use Illuminate\Database\Eloquent\Model;

class Constructor extends Model
{
    /**
     * @var string
     */
    protected $table = 'constructors';

    /**
     * @var bool
     */
    public $timestamps = false;

    /**
     * @var array
     */
    protected $fillable = [
        'data',
    ];

    /**
     * @var array
     */
    protected $casts = [
        'data' => 'array',
    ];
}
