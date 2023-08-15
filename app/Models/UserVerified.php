<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class UserVerified
 * @package App
 *
 * @property string $field
 * @property string $code
 * @property int $type
 */
class UserVerified extends Model
{
    protected $table = 'user_verified';

    const TYPE_EMAIL = 1;
    const TYPE_PHONE = 2;

    protected $guarded = [];
}
