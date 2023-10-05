<?php

namespace Owlwebdev\Ecom\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * Class SearchHistory
 * @package App
 *
 * @property string $input
 * @property string $select
 * @property string $ip
 * @property Carbon $created_at
 * @property Carbon $updated_at
 *
 */
class SearchHistory extends Model
{
    use HasFactory;

    protected $table = 'search_history';

    protected $fillable = ['input', 'select', 'ip'];
}
