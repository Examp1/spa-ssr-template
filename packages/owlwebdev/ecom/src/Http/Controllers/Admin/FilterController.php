<?php

namespace Owlwebdev\Ecom\Http\Controllers\Admin;

use Owlwebdev\Ecom\Models\Filter;
use Owlwebdev\Ecom\Models\Attribute;
use Owlwebdev\Ecom\Models\AttributeGroup;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

class FilterController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // access
        if (!auth()->user()->can('filters_view')) abort(404);

        $model = AttributeGroup::query()
            ->orderBy('order')
            ->orderByTranslation('name')
            ->get()->toArray();

        $localizations = config('translatable.locales');

        return view('ecom::admin.filters.index', [
            'model'          => $model,
            'localizations' => $localizations
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \Owlwebdev\Ecom\Models\Filter  $filter
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $attribute_group = AttributeGroup::findOrFail($id);

        $localizations = config('translatable.locales');
        $locale = app()->getLocale();

        $attributes = $attribute_group->attributes()
            ->leftJoin('filters', function ($join) use ($attribute_group) {
                $join->on('attributes.id', '=', 'filters.attribute_id');
                $join->on('filters.attribute_group_id', '=', DB::raw("'" . $attribute_group->id . "'"));
            })
            ->leftJoin('attribute_translations', 'attributes.id', '=', 'attribute_translations.attribute_id')
            ->where('lang', $locale)
            ->select([
                'attributes.*',
                'filters.display',
                'filters.expanded',
                'filters.logic',
                'filters.is_main',
                'filters.parent_id',
                'filters.order',
                'filters.id as filter_id',
                'attribute_translations.name AS name',
            ])
            ->orderBy('filters.order')
            ->get();

        return view('ecom::admin.filters.edit', [
            'models' => $attributes,
            'types' => Filter::getDisplayTypes(),
            'attribute_group' => $attribute_group,
            'localizations' => $localizations
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Owlwebdev\Ecom\Models\Filter  $filter
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, int $id)
    {
        if (!empty($request->post())) {
            $post = $request->post();

            if (!empty($post['slug'])) {
                foreach ($post['slug'] as $filter_id => $slug) {

                    Filter::query()->updateOrCreate([
                        'attribute_id'       => $post['attribute_id'][$filter_id],
                        'attribute_group_id' => $id,
                    ], [
                        'attribute_id'       => $post['attribute_id'][$filter_id],
                        'attribute_group_id' => $id,
                        'display'            => $post['display'][$filter_id] ?? 'select',
                        'expanded'           => $post['expanded'][$filter_id] ?? 0,
                        'logic'              => $post['logic'][$filter_id] ?? 1,
                        'is_main'            => $post['is_main'][$filter_id] ?? 1,
                        'parent_id'          => $post['parent_id'][$filter_id] ?? 0,
                        'order'              => $post['order'][$filter_id] ?? 0,
                    ]);
                }

                Cache::flush();

                return redirect()->route('filters.index')->with('success', __('Created successfully!'));
            }
        }
    }
}
