<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Settings;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Cache;

class SettingsController extends Controller
{
    public function index($tab)
    {
        // access
        if (!auth()->user()->can('setting_' . $tab . '_view')) abort(404);

        $model = Settings::query()
            ->select([
                'code', 'value', 'lang'
            ])
            ->get()
            ->groupBy('lang')->transform(function ($item, $k) {
                return $item->groupBy('code');
            })
            ->toArray();

        $localizations = config('translatable.locales');

        return view('admin.settings.index', [
            'data'          => $model,
            'tab'           => $tab,
            'localizations' => $localizations
        ]);
    }

    public function save(Request $request)
    {
        // access
        if (!auth()->user()->can('setting_' . $request->get('_tab') . '_edit')) {
            return redirect()->back()->with('error', 'У вас немає прав на виконання цієї дії!');
        }

        $post = $request->except(['_token']);

        if (isset($post['setting_data'])) {
            foreach ($post['setting_data'] as $lang => $data) {
                foreach ($data as $code => $value) {

                    if ($code === "contacts") {
                        $value = array_values($value);
                        if (is_array($value) && count($value)) {
                            foreach ($value as $valueKey => &$valueItem) {

                                if (!$valueItem['name']) {
                                    return redirect()->back()->with('error', "Назва - обов'язкове поле!");
                                } else {
                                    $valueItem['slug'] = Str::slug($valueItem['name']);
                                }

                                foreach ($valueItem as $valueKey2 => $valueItem2) {
                                    if (is_array($valueItem2) && count($valueItem2)) {
                                        $value[$valueKey][$valueKey2] = array_values($valueItem2);
                                    }
                                }
                            }
                        }
                    }

                    $item = Settings::firstOrNew([
                        'code' => $code,
                        'lang' => $lang
                    ]);

                    $newVal = $value;

                    if (is_array($value)) {
                        $newVal = json_encode($value, JSON_UNESCAPED_UNICODE);
                    }

                    $item->value = $newVal;
                    $item->save();
                }
            }
        }

        Cache::flush();

        return redirect()->back()->with('success', 'Налаштування успішно оновлено!');
    }

    public function addContactBlock(Request $request)
    {
        $contact = View::make('admin.settings.contacts._contact_item', [
            'lang'              => $request->get('lang'),
            'countContactBlock' => $request->get('countContactBlock'),
            'contact'           => []
        ])->render();

        return $contact;
    }

    public function removeContactBlock(Request $request)
    {
        $setting = Settings::query()
            ->where('lang', $request->get('lang'))
            ->where('code', 'contacts')
            ->first();

        if ($setting) {
            $val = json_decode($setting->value, true);

            if (isset($val[$request->get('count_contact_block')])) {
                // Удаляем блок
                unset($val[$request->get('count_contact_block')]);
                $val = array_values($val);

                // если остались блоки
                if (count($val)) {
                    $flagIsMain = false;
                    foreach ($val as $item) {
                        if (isset($item['is_main']) && $item['is_main']) {
                            $flagIsMain = true;
                        }
                    }

                    if (!$flagIsMain) {
                        $val[0]['is_main'] = '1';
                    }
                }

                $setting->value = json_encode($val, JSON_UNESCAPED_UNICODE);
                $setting->save();

                Cache::flush();

                return redirect()->back()->with('success', 'Блок успішно вилучено!');
            }
        }

        return redirect()->back()->with('error', 'Помилка!');
    }
}
