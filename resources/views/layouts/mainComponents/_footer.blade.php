<?php
$footerMenu = Menu::get('Footer');

$currentLang = app()->getLocale();
$links = [];
try {
    $lnk = app(Setting::class)->get('links');

    if ($lnk) {
        $links = json_decode($lnk, true);
    }
} catch (Exception $e) {
}
?>

<footer class="bgft" style="background-image: url(/img/recomendblack.jpg);">
    <div class="container">
        @foreach ($footerMenu as $item)
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

                if ($item['type'] == \App\Models\Menu::TYPE_PRODUCT_CATEGORY) {
                    $url = \App\Models\Menu::getTypesModel()[$item['type']]['url_prefix'] . $modelRel['path'];
                } elseif ($item['type'] == \App\Models\Menu::TYPE_ARBITRARY) {
                    $url = 'javascript:void(0)';
                } else {
                    $url = \App\Models\Menu::getTypesModel()[$item['type']]['url_prefix'] . $modelRel['slug'];
                }

                //                            $url = \App\Models\Menu::getTypesModel()[$item['type']]['url_prefix'] . $modelRel['path'];

                if ($currentLang !== config('translatable.locale')) {
                    $url = '/' . $currentLang . $url;
                }

                $name = $modelRel[\App\Models\Menu::getTypesModel()[$item['type']]['name']];

                foreach ($item['translations'] as $trans) {
                    if ($trans['lang'] === $currentLang) {
                        $name = $trans['name'];
                    }
                }
            }
            ?>
            <div class="lnksBlock">
                <a href="{{ $url ?? 'javascript:void(0)' }}" class="caption">{{ $name }}</a>
                @foreach ($item['children'] as $item2)
                    <?php
                    if ($item2['type'] == \App\Models\Menu::TYPE_ARBITRARY) {
                        $name = $item2['name'];

                        foreach ($item2['translations'] as $trans) {
                            if ($trans['lang'] === $currentLang) {
                                $name = $trans['name'];
                                $url = $trans['url'];
                            }
                        }
                    } else {
                        $modelRel = $item2[\App\Models\Menu::getTypesModel()[$item2['type']]['rel']];

                        if ($item2['type'] == \App\Models\Menu::TYPE_PRODUCT_CATEGORY) {
                            $url = \App\Models\Menu::getTypesModel()[$item2['type']]['url_prefix'] . $modelRel['path'];
                        } elseif ($item2['type'] == \App\Models\Menu::TYPE_ARBITRARY) {
                            $url = 'javascript:void(0)';
                        } else {
                            $url = \App\Models\Menu::getTypesModel()[$item2['type']]['url_prefix'] . $modelRel['slug'];
                        }

                        if ($currentLang !== config('translatable.locale')) {
                            $url = '/' . $currentLang . $url;
                        }

                        $name = $modelRel[\App\Models\Menu::getTypesModel()[$item2['type']]['name']];

                        foreach ($item2['translations'] as $trans) {
                            if ($trans['lang'] === $currentLang) {
                                $name = $trans['name'];
                            }
                        }
                    }
                    ?>

                    <a href="{{ $url }}" class="c">{{ $name }}</a>
                @endforeach
            </div>
        @endforeach
        <div class="socBlock">
            <div class="socwrp">
                @if (count($links))
                    @foreach ($links as $item)
                        <a href="{{ $item['link'] }}" target="_blank">
                            <img src="{{ get_image_uri($item['image']) }}" alt="">
                        </a>
                    @endforeach
                @endif
            </div>
            <img src="/img/logo.svg" alt="">
        </div>
    </div>
</footer>
