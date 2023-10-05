<?php

namespace Owlwebdev\Ecom\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class Wishlist extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $fillable = [
        'user_id',
        'product_id',
    ];

    public function product()
    {
        return $this->hasOne(Product::class, 'id', 'product_id');
    }

    public static function sync(Request $request)
    {
        $wl = json_decode($request->cookie('wishlist'));

        if (!empty($wl)) {
            $user = auth('api')->user();

            foreach ($wl as $item) {
                $user->wishlists()->firstOrCreate(['product_id' => $item]);
            }

            return true;
        }

        return false;
    }
}
