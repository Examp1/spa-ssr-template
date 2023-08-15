<?php
$font = app(Setting::class)->get('theme_font_style', config('translatable.locale'));
if(isset(config('theme.fonts')[$font]['name'])){
    echo '--font: '.config('theme.fonts')[$font]['name'];
}
?>
