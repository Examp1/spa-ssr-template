<?php
$infoMenu = Menu::get('Инфо-страницы');
$currentLang = app()->getLocale();
?>

<div class="topLine">
    <div class="container">
        <div>
            @foreach ($infoMenu as $item)
                <?php
                if ($item['type'] == \App\Models\Menu::TYPE_ARBITRARY) {
                    $name = $item['name'];

                    foreach ($item['translations'] as $trans) {
                        if ($trans['lang'] === $currentLang) {
                            $name = $trans['name'];
                            $url = $trans['url'];
                        }
                    }
                } else {
                    $modelRel = $item[\App\Models\Menu::getTypesModel()[$item['type']]['rel']];

                    if (is_null($modelRel)) {
                        continue;
                    }

                    $url = \App\Models\Menu::getTypesModel()[$item['type']]['url_prefix'] . $modelRel['slug'];

                    if ($currentLang !== config('translatable.locale')) {
                        $url = '/' . $currentLang . '/' . $url;
                    } else {
                        $url = '/' . $url;
                    }

                    $name = $modelRel[\App\Models\Menu::getTypesModel()[$item['type']]['name']];

                    foreach ($item['translations'] as $trans) {
                        if ($trans['lang'] === $currentLang) {
                            $name = $trans['name'];
                        }
                    }
                }
                ?>
                <a href="{{ $url }}">{{ $name }}</a>
            @endforeach
        </div>
        <div>
            <?php $phones = json_decode(app(Setting::class)->get('phones'), true); ?>
            <div>{{ app(Setting::class)->get('contact_center') ?? '' }}
                @isset($phones[1]['number'])
                    <a href="tel:{{ $phones[1]['number'] }}" class="tel">{{ $phones[1]['number'] }}</a>
                @endisset
            </div>
        </div>
    </div>
</div>
