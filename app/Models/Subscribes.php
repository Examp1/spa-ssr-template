<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Subscribes
 * @package App\Models
 *
 * @property string $email
 */
class Subscribes extends Model
{
    use HasFactory;

    protected $table = 'subscribes';

    protected $fillable = ['email'];
}
