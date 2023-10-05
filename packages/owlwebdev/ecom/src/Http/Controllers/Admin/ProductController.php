<?php

namespace Owlwebdev\Ecom\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Owlwebdev\Ecom\Models\Attribute;
use Owlwebdev\Ecom\Models\Category;
use Owlwebdev\Ecom\Models\ProductAttributes;
use Owlwebdev\Ecom\Models\ProductPrices;
use Owlwebdev\Ecom\Models\Product;
use Owlwebdev\Ecom\Models\ProductImages;
use Owlwebdev\Ecom\Models\Review;
use Owlwebdev\Ecom\Models\Related;
use Owlwebdev\Ecom\Models\Similar;
use App\Service\Adapter;
use Carbon\Carbon;
use Cviebrock\EloquentSluggable\Services\SlugService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Log;

class ProductController extends Controller
{
    /**
     * @var Category
     * @var Adapter
     */
    private $category;
    private $adapter;

    public function __construct(Category $category, Adapter $adapter)
    {
        $this->adapter = $adapter;
        $this->category = $category;
        $this->middleware('permission:products_view')->only('index');
        $this->middleware('permission:products_create')->only('create', 'store');
        $this->middleware('permission:products_edit')->only('edit', 'update');
        $this->middleware('permission:products_delete')->only('destroy');
    }

    /**
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function index(Request $request)
    {
        $status = $request->get('status');
        $name = $request->get('name');
        $categories = $request->get('categories');
        $quantity = $request->get('quantity');

        /* Sorting */
        $sort  = 'id';
        $order = 'desc';
        $tableName = 'products';


        if ($request->has('sort') && $request->has('order')) {
            $sort  = $request->get('sort');
            $order = $request->get('order');

            if (in_array($sort, ['name'])) {
                $tableName = 'product_translations';
            }
        }

        $model = Product::query()
            ->leftJoin('product_translations', 'product_translations.product_id', '=', 'products.id')
            ->where('product_translations.lang', $request->get('search_lang', config('translatable.locale')))
            ->select([
                'products.*',
                'product_translations.name',
                'product_translations.meta_title AS mTitle',
                'product_translations.meta_description AS mDescription',
                'product_translations.meta_created_as AS mCreatedAs',
                'product_translations.meta_auto_gen AS mAutoGen',
            ])
            ->when($sort !== 'status', function ($query) {
                return $query->orderBy('products.status', 'desc'); // disabled to end
            })
            ->orderBy($tableName . '.' . $sort, $order)
            ->where(function ($q) use ($status, $name, $categories, $quantity) {
                if ($status != '') {
                    $q->where('products.status', $status);
                }

                if ($name != '') {
                    $q->where('product_translations.name', 'like', '%' . $name . '%');
                }

                if ($quantity != '') {
                    switch ($quantity) {
                        case '0':
                            $q->where('products.quantity', 0);
                            break;
                        case '1':
                            $q->where('products.quantity', '>=', 1);
                            break;
                        case '10':
                            $q->where('products.quantity', '<=', 10);
                            break;
                    }

                }

                if (!empty($categories) && is_array($categories)) {
                    $q->whereHas('categories', function ($q) use ($categories) {
                        $q->whereIn('categories.id', $categories);
                    });
                }
            })
            // ->when(!empty($categories), function ($query) use ($categories) {
            //     $query->whereHas('categories', function ($q) use ($categories) {
            //         $q->whereIn('categories.id', $categories);
            //     });
            // })
            ->paginate(50);

        return view('ecom::admin.products.index', [
            'model' => $model,
            'active_count' => Product::query()->active()->count(),
            'notActive_count' => Product::query()->notActive()->count(),
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $model = new Product();

        $model->order = 0;

        $localizations = config('translatable.locales');

        $categories = $this->category->treeStructure();

        return view('ecom::admin.products.create', [
            'model'         => $model,
            'localizations' => $localizations,
            'categories'    => $categories,
        ]);
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {
        $model = new Product();

        DB::beginTransaction();

        try {
            if (empty($request->input('slug', ''))) {
                $request->merge(array('slug' => SlugService::createSlug(Product::class, 'slug', $request->input('page_data.' . config('translatable.locale') . '.name'))));
            }

            $model->fill($request->all());

            $model->status = $request->get('status') ?? false;
            $model->preorder = $request->get('preorder') ?? 0; //opposite of default
            $model->quantity = $request->get('quantity') ?? 0;
            $model->old_price = $request->get('old_price') ?? null;

            if (!$model->save()) {
                DB::rollBack();
            }


            if (empty($model->code)) {
                $model->code = str_pad($model->id, 6, '0', STR_PAD_LEFT);
            }

            $main_screen = $request->get('main_screen');

            $categories = $request->get('categories');

            if (is_array($categories)) {
                $categories = array_merge($categories, [$model->category_id]);
            } else {
                $categories = [$model->category_id];
            }

            // $model->categories()->detach(); // fix bugged
            $model->categories()->sync($categories);

            $image = '';

            foreach (config('translatable.locales') as $lang => $item) {
                $data = $request->input('page_data.' . $lang, []);

                // image copy
                if (empty($image) && !empty($data['image'])) {
                    $image = $data['image'];
                }

                if (empty($data['image']) && !empty($image)) {
                    $data['image'] = $image;
                }

                $constructorData = $request->get('constructorData');
                $data['meta_auto_gen'] = $data['meta_auto_gen'] ?? false;
                $data['status_lang'] = $data['status_lang'] ?? false;
                $data['main_screen'] = json_encode($main_screen['data'][$lang], JSON_UNESCAPED_UNICODE);
                $model->translateOrNew($lang)->fill(array_merge($data, $constructorData[$lang] ?? []));
                //                $model->translateOrNew($lang)->fill($request->input('page_data.' . $lang, []));

                if (!$model->save()) {
                    DB::rollBack();
                }
            }

            $this->adapter->renderConstructorHTML($model);
        } catch (\Throwable $e) {
            DB::rollBack();
            Log::error($e);
            return redirect()->route('products.index')->with('error', __('Error!') . $e->getMessage());
        }

        DB::commit();
        Cache::flush();

        if ($request->get('save_method') === 'save_and_back') {
            return redirect()->route('products.edit', $model->id)->with('success', __('Success!'));
        } else {
            return redirect()->route('products.edit')->with('success', __('Success!'));
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(int $id)
    {
        $model = Product::query()->where('id', $id)->first();

        $localizations = config('translatable.locales');

        $data = $model->getTranslationsArray();

        foreach ($localizations as $lang => $item) {
            if (isset($data[$lang]['main_screen'])) {
                $data[$lang]['main_screen'] = json_decode($data[$lang]['main_screen'], true);
            }
        }

        $categories = $this->category->treeStructure();

        $images = $model->images()->where('price_id', null)->get();

        $products = Product::where('id', '<>', $model->id)
            ->with('translations')
            ->get();

        $selected_related = Related::query()->where('product_id', $model->id)->pluck('related_id')->toArray();
        $selected_similar = Similar::query()->where('product_id', $model->id)->pluck('similar_id')->toArray();

        $relations = [
            'one' => [
                'name'  => __('Related products'),
                'field' => 'related_products',
                'selected_related' => $selected_related,
            ],
            'two' => [
                'name'  => __('Similar products'),
                'field' => 'similar_products',
                'selected_related' => $selected_similar,
            ],
        ];

        return view('ecom::admin.products.edit', [
            'model'         => $model,
            'data'          => $data,
            'localizations' => $localizations,
            'categories'    => $categories,
            'products'      => $products,
            'relations'     => $relations,
            'images'        => $images,
        ]);
    }

    /**
     * @param $id
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function copy($id)
    {
        $modelFrom = Product::query()
            ->with(['translations', 'prices.images', 'attributes', 'categories', 'images' => function ($q) {
                $q->whereNull('price_id');
            }])
            ->where('id', $id)
            ->first();

        DB::beginTransaction();

        try {
            $model = $modelFrom->replicate();
            $model->created_at = Carbon::now();
            $model->slug = SlugService::createSlug(Product::class, 'slug', 'copy-' . $model->slug);
            $model->status = Product::STATUS_NOT_ACTIVE;

            if (!$model->save()) {
                DB::rollBack();
                return redirect()->back()->with('error', __('Error!'));
            }

            foreach ($model->categories as $rel) {
                $model->categories()->attach($rel);
            }

            foreach ($model->translations as $item) {
                $trans = $item->replicate();
                $trans->product_id = $model->id;
                $trans->name = __('Copy of ') . $trans->name;

                if (!$trans->save()) {
                    DB::rollBack();
                    return redirect()->back()->with('error', __('Error!'));
                }

                $item->load('constructor');

                $constructor = $item->constructor->replicate();
                $constructor->constructorable_id = $trans->id;

                if (!$constructor->save()) {
                    DB::rollBack();
                    return redirect()->back()->with('error', __('Error!'));
                }
            }

            foreach ($model->images as $image) {
                $new = $image->replicate();
                $new->product_id = $model->id;

                if (!$new->save()) {
                    DB::rollBack();
                    return redirect()->back()->with('error', __('Error!'));
                }
            }

            $new_attr_ids = []; //save relation old and new id

            foreach ($model->attributes as $attr) {
                $new = $attr->replicate();
                $new->product_id = $model->id;

                if (!$new->save()) {
                    DB::rollBack();
                    return redirect()->back()->with('error', __('Error!'));
                }

                $new_attr_ids[$attr->id] = $new->id;
            }

            foreach ($model->prices as $price) {
                $new = $price->replicate();
                $new->product_id = $model->id;
                $new->status = ProductPrices::STATUS_NOT_ACTIVE;

                if (!$new->save()) {
                    DB::rollBack();
                    return redirect()->back()->with('error', __('Error!'));
                }

                foreach ($price->attributes as $rel) {
                    if (isset($new_attr_ids[$rel->id])) { // get new id
                        $new->attributes()->attach($new_attr_ids[$rel->id]);
                    }
                }

                foreach ($price->images as $rel) {
                    $image = $rel->replicate();
                    $image->price_id = $new->id;
                    $image->product_id = $model->id;

                    if (!$image->save()) {
                        DB::rollBack();
                        return [
                            'success' => false,
                            'message' => __('Error!'),
                        ];
                    }
                }
            }
        } catch (\Exception $e) {
            Log::error($e);
            DB::rollBack();
        }

        DB::commit();

        return redirect()->route('products.edit', $model->id)->with('success', __('Success!'));
    }

    /**
     * @param Request $request
     * @param $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request, $id)
    {
        /* @var $model Product */
        $model = Product::query()->where('id', $id)->first();

        DB::beginTransaction();

        // try {
            $model->fill($request->all());

            $model->status = $request->get('status') ?? false;
            $model->preorder = $request->get('preorder') ?? 0; //opposite of default
            $model->quantity = $request->get('quantity') ?? 0;
            $model->old_price = $request->get('old_price') ?? null;
            if (empty($model->code)) {
                $model->code = str_pad($model->id, 6, '0', STR_PAD_LEFT);
            }

            if (!$model->save()) {
                DB::rollBack();
                return redirect()->route('products.edit', $model->id)->with('error', __('Error!'));
            }

            $categories = $request->get('categories');

            if (is_array($categories)) {
                $categories = array_merge($categories, [$model->category_id]);
            } else {
                $categories = [$model->category_id];
            }

            // $model->categories()->detach(); // fix bugged
            $model->categories()->sync($categories);

            $model->related()->sync($request->get('related_products'));

            $model->similar()->sync($request->get('similar_products'));

            // $model->deleteTranslations();

            $main_screen = $request->get('main_screen');

            $attributes = $request->get('attr');

            $current_attrs = [];
            $lang_count = count(config('translatable.locales'));
            $attr_value_count = 0;
            $prev_group = '';
            $prev_value = '';
            $prev_slug = '';
            $prev_alt = '';

            if (is_array($attributes) && count($attributes)) {
                foreach ($attributes as $attribute_id => $attr_values) {
                    foreach ($attr_values['val'] as $key => $attr_value) {
                        // attribute value not empty
                        if ($attr_value && $attr_value != 'null') {
                            if (isset($attr_values['group'][$key]) && $attr_values['group'][$key] != 'null') {
                                $group = $attr_values['group'][$key];
                            } else {
                                if ($attr_value_count == 0) {
                                    $group = \Illuminate\Support\Str::slug($attr_value, '-');
                                } else {
                                    $group = $prev_group;
                                }
                            }

                            //fill slug
                            $attr = Attribute::find($attribute_id);

                            if ($attr) {
                                switch ($attr->type) {
                                    case 'image':
                                        $slug = \Illuminate\Support\Str::slug(pathinfo($attr_value, PATHINFO_FILENAME));
                                        break;

                                    default:
                                        $slug = \Illuminate\Support\Str::slug($attr_value, '-');
                                        break;
                                }
                            }

                            $productAttr = $model->attributes()->updateOrCreate(
                                [
                                    'product_id' => $model->id,
                                    'attribute_id' => $attribute_id,
                                    'value' => $attr_value,
                                    'lang' => $attr_values['lang'][$key],
                                ],
                                [
                                    'slug' => $slug,
                                    'group' => $group,
                                    'alt' => isset($attr_values['alt']) ? $attr_values['alt'][$key] : null,
                                ]
                            );

                            $current_attrs[] = $productAttr->id;

                            // remember value from default lang. field
                            if ($attr_value_count == 0) {
                                $prev_group = $productAttr->group;
                                $prev_value = $productAttr->value;
                                $prev_slug = $productAttr->slug;
                                $prev_alt = $productAttr->alt;
                            }

                            // empty attribute value, fill with default lang. value
                        } elseif ($prev_value && $prev_group && $attr_value_count > 0) {
                            $group = $prev_group;
                            $attr_value = $prev_value;
                            $slug = $prev_slug;
                            $alt = $prev_alt;

                            $productAttr = $model->attributes()->updateOrCreate(
                                [
                                    'product_id' => $model->id,
                                    'attribute_id' => $attribute_id,
                                    'value' => $attr_value,
                                    'lang' => $attr_values['lang'][$key],
                                ],
                                [
                                    'slug' => $slug,
                                    'group' => $group,
                                    'alt' => isset($attr_values['alt'][$key]) ? $attr_values['alt'][$key] : $alt,
                                ]
                            );

                            $current_attrs[] = $productAttr->id;
                        }

                        $attr_value_count++;

                        if ($attr_value_count == $lang_count) {
                            $attr_value_count = 0;
                            $prev_group = '';
                            $prev_value = '';
                            $prev_slug = '';
                            $prev_alt = '';
                        }
                    }
                }
            }

            $model->attributes()->whereNotIn('id', $current_attrs)->delete();

            // save images
            $images = request()->get('images');

            $ids = [];
            if (!empty($images)) {
                foreach ($images['id'] as $i => $id) {
                    $img = $model->images()->updateOrCreate(
                        [
                            'id' => $id,
                        ],
                        [
                            'image'      => $images['image'][$i],
                            'order' => $images['order'][$i],
                        ]
                    );

                    $ids[] = $img->id;
                }
            }

            $model->images()->whereNotIn('id', $ids)->where('price_id', null)->delete();

            $image = '';

            foreach (config('translatable.locales') as $lang => $item) {
                $data = $request->input('page_data.' . $lang, []);

                // image copy
                if (empty($image) && !empty($data['image'])) {
                    $image = $data['image'];
                }

                if (empty($data['image']) && !empty($image)) {
                    $data['image'] = $image;
                }

                $data['meta_auto_gen'] = $data['meta_auto_gen'] ?? false;
                $data['status_lang'] = $data['status_lang'] ?? false;
                $data['main_screen'] = json_encode($main_screen['data'][$lang], JSON_UNESCAPED_UNICODE);
                $constructorData = $request->get('constructorData');
                $model->translateOrNew($lang)->fill(array_merge($data, $constructorData[$lang] ?? []));

                if (!$model->save()) {
                    DB::rollBack();
                    return redirect()->route('products.edit', $model->id)->with('error', __('Error!'));
                }
            }

            //set image from options
            $model->updatePriceImage();

            $this->adapter->renderConstructorHTML($model);
        // } catch (\Throwable $e) {
        //     Log::error($e);
        //     DB::rollBack();

        //     return redirect()->route('products.edit', $model->id)->with('error', __('Error!') . $e->getMessage());
        // }

        DB::commit();
        Cache::flush();

        if ($request->get('save_method') === 'save_and_back') {
            return redirect()->route('products.edit', $model->id)->with('success', __('Success!'));
        } else {
            return redirect()->route('products.index')->with('success', __('Success!'));
        }
    }

    /**
     * @param Product $product
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(Product $product)
    {
        $product->deleteTranslations();
        $product->categories()->sync([]);

        ProductAttributes::query()->where('product_id', $product->id)->delete();
        ProductPrices::query()->where('product_id', $product->id)->delete();
        Review::query()->where('product_id', $product->id)->delete();

        $product->delete();

        return redirect()->route('products.index')->with('success', __('Success!'));
    }

    public function deleteSelected(Request $request)
    {
        $ids = json_decode($request->get('ids'), true);

        if (count($ids)) {
            foreach ($ids as $id) {
                $model = Product::query()->where('id', $id)->first();
                $model->deleteTranslations();
                ProductAttributes::query()->where('product_id', $id)->delete();
                ProductPrices::query()->where('product_id', $id)->delete();
                Review::query()->where('product_id', $id)->delete();
                Product::query()->where('id', $id)->delete();
            }

            return redirect()->route('products.index')->with('success', __('Success!'));
        }

        return redirect()->back()->with('error', __('Error! '));
    }

    public function metaGenerate(Request $request)
    {
        $model = Product::query()
            ->active()
            ->with('translations')
            ->get();

        foreach ($model as $item) {
            foreach ($item->translations as $trans) {
                if (($trans->meta_auto_gen || $request->get('rewrite_all') == "true") && $trans->lang == $request->get('template_lang')) {
                    $trans->meta_title = str_replace($trans::META_TAGS, $trans->getMetaKeywords($trans->lang), $request->get('template_title'));
                    $trans->meta_description = str_replace($trans::META_TAGS, $trans->getMetaKeywords($trans->lang), $request->get('template_desc'));
                    $trans->meta_created_as = $trans::META_CREATED_AS_AUTO_GEN;
                    $trans->save();
                }
            }
        }

        return [
            'success' => true,
            'message' => __('Meta data successfully generated!'),
        ];
    }

    public function addPrice(Request $request)
    {
        $priceModel = new ProductPrices();
        $priceModel->product_id = $request->input('product_id');
        $priceModel->status = ProductPrices::STATUS_ACTIVE;
        $priceModel->price = 0;
        $priceModel->count = 0;
        $priceModel->order = 0;
        $priceModel->save();

        $product = $priceModel->product;

        $elem = View::make('ecom::admin.products._price_item', [
            'model' => $product,
            'priceModel' => $priceModel,
            'opened' => true
        ])->render();

        return $elem;
    }

    /**
     * @param $id
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function copyPrice(Request $request)
    {
        $id = $request->input('price_id');
        $modelFrom = ProductPrices::query()
            ->with(['attributes', 'images'])
            ->where('id', $id)
            ->first();

        DB::beginTransaction();

        try {
            $model = $modelFrom->replicate();
            $model->name = __('Copy of ') . $model->name;
            $model->code++;
            $model->status = ProductPrices::STATUS_NOT_ACTIVE;

            if (!$model->save()) {
                DB::rollBack();
                return [
                    'success' => false,
                    'message' => __('Error!'),
                ];
            }

            foreach ($model->attributes as $rel) {
                $model->attributes()->attach($rel);
            }
            $model->load('attributes'); //refresh

            foreach ($model->images as $item) {
                $image = $item->replicate();
                $image->price_id = $model->id;

                if (!$image->save()) {
                    DB::rollBack();
                    return [
                        'success' => false,
                        'message' => __('Error!'),
                    ];
                }
            }
            $model->load('images'); //refresh

        } catch (\Exception $e) {
            Log::error($e);
            DB::rollBack();
            return [
                'success' => false,
                'message' => __('Error!'),
            ];
        }

        DB::commit();

        // update product data
        $product = $model->product;

        $elem = View::make('ecom::admin.products._price_item', [
            'model' => $product,
            'priceModel' => $model,
            'opened' => false
        ])->render();

        return $elem;
    }

    public function savePrice(Request $request)
    {
        $priceModel = ProductPrices::query()->where('id', $request->input('id'))->first();

        $priceModel->fill($request->except(['_token', 'images', 'attributes']));
        $priceModel->code = preg_replace("/[^a-zA-Z0-9]+/", "", $priceModel->code);
        $priceModel->save();

        parse_str($request->input('images'), $price_images);

        $ids = [];
        $image = null;
        $color = null;
        if (isset($price_images['price_images']) && !empty($price_images['price_images'])) {
            $images = $price_images['price_images'];
            foreach ($images['id'] as $i => $id) {
                if ($id) {
                    $priceModel->images()->where('id', $id)->update([
                        'image'      => $images['image'][$i],
                        'order' => $images['order'][$i],
                    ]);
                    $ids[] = $id;
                } else {
                    $record = $priceModel->images()->create([
                        'image'      => $images['image'][$i],
                        'order' => $images['order'][$i],
                        'product_id' => $priceModel->product_id,
                    ]);
                    $ids[] = $record->id;
                }

                // price image from gallery
                // if (empty($image) && !empty($images['image'][$i])) {
                //     $image = $images['image'][$i];
                // }
            }
        }

        $priceModel->images()->whereNotIn('id', $ids)->where('price_id', $priceModel->id)->delete();

        // attributes
        parse_str($request->input('attributes'), $price_attributes);
        $attr_code = ''; // values for code generation

        if (isset($price_attributes['attributes']) && !empty($price_attributes['attributes'])) {
            $attributes = array_values($price_attributes['attributes']);

            foreach ($attributes as $product_attribute_id) {
                $attr = ProductAttributes::find($product_attribute_id);
                $attr_code .= $attr->slug . ' ';
                if ($attr) {
                    // find same attribute values for different locales
                    $same_attr = ProductAttributes::query()->where([
                        ['product_id', $attr->product_id],
                        ['attribute_id', $attr->attribute_id],
                        ['group', $attr->group],
                        ['id', '<>', $attr->id]
                    ])->pluck('id')->toArray();

                    $attributes = array_merge($attributes, $same_attr);

                    if (empty($image) && ($attr->attribute->type == 'image') && !empty($attr->value)) {
                        $image = $attr->value;
                    }

                    if (empty($color) && ($attr->attribute->type == 'color') && !empty($attr->value)) {
                        $color = $attr->value;
                    }
                }
            }
            $priceModel->attributes()->sync($attributes);
        }

        if (empty($priceModel->code)) {
            if (!empty($attr_code)) {
                $priceModel->code = acronym($attr_code);
            } else {
                $priceModel->code = str_pad($priceModel->id, 6, '0', STR_PAD_LEFT);
            }

        }

        $price_codes = ProductPrices::where('id', '<>', $priceModel->id)->where('product_id', $priceModel->product_id)->pluck('code');

        while ($price_codes->contains($priceModel->code)) {
            $priceModel->code++;
        }

        $priceModel->save();

        // update price image/color
        $priceModel->update([
            'image' => $image,
            'color' => $color,
        ]);

        // update product data
        $product = $priceModel->product;

        $product->updatePriceImage();

        $elem = View::make('ecom::admin.products._price_item', [
            'model' => $product,
            'priceModel' => $priceModel,
            'opened' => false
        ])->render();

        return [
            'success' => true,
            'message' => __('Updated successfully!'),
            'elem' => $elem
        ];
    }

    public function removePrice(Request $request)
    {
        $priceModel = ProductPrices::query()->where('id', $request->input('id'))->first();

        $product = $priceModel->product;

        ProductImages::query()->where('price_id', $priceModel->id)->delete();
        $priceModel->delete();

        //update product data
        $product->updatePriceImage();

        return [
            'success' => true,
            'message' => __('Deleted successfully!')
        ];
    }

    public function generateRecommendationProducts()
    {
        $models = Product::query()->whereNull('recommendations')->get();

        foreach ($models as $model) {
            $recommendationIds = [];

            if (isset($model->categories[0]->path)) {
                $categories = $model->categories;

                foreach ($categories as $category) {
                    if (count($recommendationIds) >= 4) break;
                    $products = $category->productsInSubCategories();

                    if ($products) {
                        foreach ($products as $product) {
                            if (count($recommendationIds) >= 4) break;
                            if ($product->id != $model->id && !in_array($product->id, $recommendationIds)) {
                                $recommendationIds[] = $product->id;
                            }
                        }
                    }
                }
            }

            $model->recommendations = json_encode($recommendationIds);
            $model->save();
        }
    }
}
