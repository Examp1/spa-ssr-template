This is WIP adn wont work on without admin part already installed
# HOW TO
composer.json
~~~
"require": {
        "php": "^8.0",
        ...
        "owlwebdev/ecom": "dev-master"
    },
...
"repositories": [
    {
        "type": "path",
        "url": "packages/owlwebdev/ecom",
        "options": {
            "symlink": true
        }
    }
],
~~~

publish tag ecom-seeder and ecom-files, run migrations
~~~
php artisan vendor:publish
php artisan db:seed --class=EcomSeeder
~~~



add to resources/views/layouts/admin/components/aside.blade.php:~89 (after landings)
~~~
@include('ecom::admin.pieces.aside')
~~~

add to resources/views/layouts/admin/components/aside.blade.php:~304 (settings submenu)
~~~
@include('ecom::admin.pieces.aside_settings')
~~~

add to resources/views/layouts/admin/components/_asider.blade.php:~254 (before slug field)
~~~
@include('ecom::admin.pieces._aside')
~~~

add to app/Models/Settings.php
~~~
    const TAB_CATEGORIES         = 'categories';
    const TAB_PRODUCTS           = 'products';
    const TAB_CHECKOUT           = 'checkout';

    ...

            self::TAB_CATEGORIES      => 'Catalog',
            self::TAB_PRODUCTS        => 'Products',
            self::TAB_CHECKOUT        => 'Checkout',
~~~

change/add to app/Http/Controllers/Admin/SettingsController.php
~~~
public function save(Request $request)
    {
        // access
        if (!auth()->user()->can('setting_' . $request->get('_tab') . '_edit')) {
            return redirect()->back()->with('error', 'У вас нема прав!');
        }
        $defaultLang = config('translatable.locale');
        $default = [];
        $post = $request->except(['_token']);

        if (isset($post['setting_data'])) {
            foreach ($post['setting_data'] as $lang => $data) {
                foreach ($data as $code => $value) {

                    if ($code === "contacts") {
                        $value = array_values($value);
                        if (is_array($value) && count($value)) {
                            foreach ($value as $valueKey => $valueItem) {
                                foreach ($valueItem as $valueKey2 => $valueItem2) {
                                    if (is_array($valueItem2) && count($valueItem2)) {
                                        $value[$valueKey][$valueKey2] = array_values($valueItem2);
                                    }
                                }
                            }
                        }
                    }

                    if ($code === "checkout") {
                        $value['currencies'] = $post['setting_data'][$defaultLang]['checkout']['currencies'];
                        if (is_array($value)) {
                            if (empty($default)) {
                                $default = $value;
                            } else {
                                $value = array_replace_recursive($default, $value);
                            }
                        }
                    }
                    
                    ...
~~~

add to app/Models/Menu.php
~~~
use Owlwebdev\Ecom\Models\Product;
use Owlwebdev\Ecom\Models\Category;
...
    const TYPE_PRODUCT_CATEGORY = 11; //Категорії товарів
    const TYPE_PRODUCT          = 12; //Товари
...
            self::TYPE_PRODUCT_CATEGORY => 'Категорії товарів',
            self::TYPE_PRODUCT          => 'Товари',
...
            self::TYPE_PRODUCT_CATEGORY => Category::getMenuConfig(),
            self::TYPE_PRODUCT          => Product::getMenuConfig(),
...
    public function product()
    {
        return $this->hasOne(Product::class, 'id', 'model_id');
    }

    public function product_category()
    {
        return $this->hasOne(Category::class, 'id', 'model_id');
    }
~~~

add to app/Models/User.php
~~~
    //all
    public function carts()
    {
        return $this->hasMany(Order::class);
    }

    //public orders
    public function orders()
    {
        return $this->carts()->whereIn('order_status_id', Order::PUBLIC_STATUSES);
    }

    public function wishlists()
    {
        return $this->hasMany(Wishlist::class);
    }

    public function discount()
    {
        return $this->hasOne(Discount::class, 'id', 'discount_id')->active()->withDefault(null);
    }
~~~

add to app/Http/Controllers/Admin/UserController.php ~164 (destroy, after $user = User::query()->where('id',$id)->first();)
~~~
        if($user->carts->isNotEmpty()) {

            $msg = ' ';
            foreach ($user->carts as $order) {
                $msg .= '#' . $order->id . ' ';
            }
            return redirect()->back()->with('error', __('Can`t delete, User has orders') . $msg);
        }
~~~

add to app/Modules/Widgets/config/widgets.php
~~~
...
        'categories-w'        => \App\Modules\Widgets\Collections\Catalog\CategoriesWidget::class,
        'product-slider'      => \App\Modules\Widgets\Collections\ProductSlider\ProductSliderWidget::class,
...
~~~
