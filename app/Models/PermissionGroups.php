<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PermissionGroups extends Model
{
    use HasFactory;

    protected $table = 'permission_groups';

    protected $fillable = ['name'];

    public $timestamps = false;
}
