<?php

use App\Models\Menu;
use App\Modules\Forms\Models\Form;

if (!function_exists('plural_form')) {

    function plural_form($number, $after) {
        $cases = [2, 0, 1, 1, 1, 2];
        return $number . ' ' . $after[($number % 100 > 4 && $number % 100 < 20) ? 2 : $cases[min($number % 10, 5)]];
    }

}

if (!function_exists('convert_array_field_name_to_with_dot')) {

    function convert_array_field_name_to_with_dot(string $name) {
        $modified = preg_replace('/\]\[|\[|\]/', '.', $name);

        return $modified ? rtrim($modified, '.') : $name;
    }

}

if (!function_exists('media_preview_box')) {

    function media_preview_box($name, $path = null, $errors = null, $altName = null, $altValue = null) { ?>
        <div class="media-wrapper" style="width: 126px">
            <div class="text-center">
                <div style="width: 127px;height: 127px;background: #d2d2d2;display: flex;justify-content: center;align-items: center;">
                    <img style="max-width: 100%;max-height: 100%;" src="<?php echo get_image_uri(old(convert_array_field_name_to_with_dot($name), $path ?? '')); ?>" class="image-tag <?php if ($errors && $errors->first(convert_array_field_name_to_with_dot($name))) : ?> border-danger <?php endif; ?>">
                </div>
            </div>

            <input type="hidden" name="<?php echo $name; ?>" value="<?php echo old(convert_array_field_name_to_with_dot($name), $path ?? ''); ?>" class="media-input">

            <input type="hidden" name="<?php echo $altName; ?>" value="<?php echo $altValue ?? '' ?>" class="media-input-alt">

            <?php if ($errors && $errors->first(convert_array_field_name_to_with_dot($name))) : ?>
                <span class="invalid-feedback d-block" role="alert">
                    <strong><?php echo $errors->first(convert_array_field_name_to_with_dot($name)); ?></strong>
                </span>
            <?php endif; ?>

            <div class="mt-1 text-center">
                <button type="button" class="btn btn-outline-info btn-sm choice-media">Вибрати</button>

                <button type="button" class="btn btn-outline-danger btn-sm remove-media" title="Видалити">
                    <i class="fa fa-trash"></i>
                </button>

                <button type="button" class="btn btn-outline-warning btn-sm info-media" title="Інформація">
                    <i class="fa fa-info"></i>
                </button>
            </div>
        </div>
    <?php }

}

if (!function_exists('file_preview_box')) {

    function file_preview_box($name, $path = null, $errors = null)
    { ?>
        <div class="media-wrapper" style="width: 126px">
            <div class="text-center">
                <img
                    src="<?php echo get_image_uri(old(convert_array_field_name_to_with_dot($name), $path ?? ''), 'original', true, 'file'); ?>"
                    class="img-thumbnail image-tag <?php if ($errors && $errors->first(convert_array_field_name_to_with_dot($name))) : ?> border-danger <?php endif; ?>"
                    title="<?= $path ? pathinfo($path)['basename'] : 'Файл не вибрано' ?>"
                    style="background: #d2d2d2; border: none"
                >
            </div>

            <input type="hidden" name="<?php echo $name; ?>"
                   value="<?php echo old(convert_array_field_name_to_with_dot($name), $path ?? ''); ?>"
                   class="file-path-field">

            <?php if ($errors && $errors->first(convert_array_field_name_to_with_dot($name))) : ?>
                <span class="invalid-feedback d-block" role="alert">
                    <strong><?php echo $errors->first(convert_array_field_name_to_with_dot($name)); ?></strong>
                </span>
            <?php endif; ?>

            <div class="mt-1 text-center">
                <button type="button" class="btn btn-outline-info btn-sm choice-file">Вибрати</button>

                <button type="button" class="btn btn-outline-danger btn-sm remove-file" title="Видалити">
                    <i class="fa fa-trash"></i>
                </button>
            </div>
        </div>
    <?php }

}

if (!function_exists('get_interlink')) {

    function get_interlink($item, $lang = null)
    {
        $res = [];

        if (!isset($item['interlink_type']) || !isset($item['interlink_val'])) {
            return [
                'link_type' => 'undefined',
                'link'      => $item['link'] ?? '',
                'public_date' => null
            ];
        }

        try {
            $type = $item['interlink_type'];
            $id = $item['interlink_val'][$type];
        } catch (Exception $e){
            return [
                'link_type' => 'undefined',
                'link'      => $item['link'] ?? '',
                'public_date' => null
            ];
        }

        if (is_null($lang)) $lang = app()->getLocale();

        $url = '';

        if ($type === 'arbitrary') {
            return [
                'link_type' => 'link',
                'link'      => $id
            ];
        } elseif ($type === 'form') {
            $formId = $id;
            $form = Form::query()->where('id', $formId)->first();

            $contentData = $form->getData();

            if (is_array($contentData) && count($contentData)) {
                foreach ($contentData as $qaw => $qwe) {
                    $contentData[$qaw]['type'] = 'form-' . $qwe['type'];
                }
            }

            return [
                'link_type' => 'form',
                'link'      => '',
                'form_id'   => $formId,
                'form_data' => $contentData
            ];
        } else {
            $model = config('menu.entities')[$type]::query()->where('id', $id)->active()->first();

            if ($model) {
                $url = $model->frontLink();

                if($type == "BlogArticles"){
                    $publicDate = \Carbon\Carbon::parse($model->pablic_date)->format('d.m.Y');
                }
            }

            if ($model) {
                if ($lang !== config('translatable.locale')) {
                    $url = '/' . $lang . '/' . $url;
                } else {
                    $url = '/' . $url;
                }
            } else {
                return [
                    'link_type' => 'undefined',
                    'link'      => $item['link'] ?? '',
                    'public_date' => null
                ];
            }
        }

        return [
            'link_type'   => 'link',
            'link'        => str_replace('//', '/', $url),
            'public_date' => $publicDate ?? null
        ];
    }
}

if (!function_exists('isMultiLang')) {

    function isMultiLang(): bool
    {
        return config('translatable.locales') && count(config('translatable.locales')) > 1;
    }
}

if (!function_exists('getSupportedLocales')) {

    function getSupportedLocales(): array
    {
        return config('translatable.locales');
    }
}

if (!function_exists('isCurrentLocale')) {

    function isCurrentLocale(string $lang): bool
    {
        return app()->getLocale() === $lang;
    }
}

if (!function_exists('toFloat')) {

    function toFloat($num)
    {
        $dotPos = strrpos($num, '.');
        $commaPos = strrpos($num, ',');
        $sep = (($dotPos > $commaPos) && $dotPos) ? $dotPos : ((($commaPos > $dotPos) && $commaPos) ? $commaPos : false);

        if (!$sep) {
            return floatval(preg_replace("/[^0-9]/", "", $num));
        }

        return floatval(
            preg_replace("/[^0-9]/", "", substr($num, 0, $sep)) . '.' .
                preg_replace("/[^0-9]/", "", substr($num, $sep + 1, strlen($num)))
        );
    }
}

if (!function_exists('printTruncated')) {

    function printTruncated($maxLength, $html, $isUtf8 = true)
    {
        $printedLength = 0;
        $position = 0;
        $tags = [];
        $string = '';

        // For UTF-8, we need to count multibyte sequences as one character.
        $re = $isUtf8
            ? '{</?([a-z]+)[^>]*>|&#?[a-zA-Z0-9]+;|[\x80-\xFF][\x80-\xBF]*}'
            : '{</?([a-z]+)[^>]*>|&#?[a-zA-Z0-9]+;}';

        while ($printedLength < $maxLength && preg_match($re, $html, $match, PREG_OFFSET_CAPTURE, $position)) {
            list($tag, $tagPosition) = $match[0];

            // Print text leading up to the tag.
            $str = substr($html, $position, $tagPosition - $position);
            if ($printedLength + strlen($str) > $maxLength) {
                $string .= substr($str, 0, $maxLength - $printedLength);
                $printedLength = $maxLength;
                break;
            }

            $string .= $str;
            $printedLength += strlen($str);
            if ($printedLength >= $maxLength) break;

            if ($tag[0] == '&' || ord($tag) >= 0x80) {
                // Pass the entity or UTF-8 multibyte sequence through unchanged.
                $string .= $tag;
                $printedLength++;
            } else {
                // Handle the tag.
                $tagName = $match[1][0];
                if ($tag[1] == '/') {
                    // This is a closing tag.

                    $openingTag = array_pop($tags);
                    assert($openingTag == $tagName); // check that tags are properly nested.

                    $string .= $tag;
                } else if ($tag[strlen($tag) - 2] == '/') {
                    // Self-closing tag.
                    $string .= $tag;
                } else {
                    // Opening tag.
                    $string .= $tag;
                    $tags[] = $tagName;
                }
            }

            // Continue after the tag.
            $position = $tagPosition + strlen($tag);
        }

        // Print any remaining text.
        if ($printedLength < $maxLength && $position < strlen($html))
            $string .= substr($html, $position, $maxLength - $printedLength);

        // Close any open tags.
        while (!empty($tags))
            $string .= sprintf('</%s>', array_pop($tags));

        return $string;
    }
}
