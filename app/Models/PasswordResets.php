<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class PasswordResets
 * @package App
 *
 * @property string $field
 * @property string $code
 * @property int $type
 */
class PasswordResets extends Model
{
    protected $table = 'password_resets';

    const TYPE_EMAIL = 1;
    const TYPE_PHONE = 2;

    protected $guarded = [];
}
