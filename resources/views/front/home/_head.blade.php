@meta_tags
<?php
    $font = app(Setting::class)->get('theme_font_style', config('translatable.locale'));
    if(isset(config('theme.fonts')[$font]['link'])){
        echo config('theme.fonts')[$font]['link'];
    }
?>
