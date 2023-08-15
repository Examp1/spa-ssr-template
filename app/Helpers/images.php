<?php

if (!function_exists('get_image_uri')) {

    function get_image_uri($path, $template = 'original', $absolute = true, $type = 'image') {

        try {
            if (!empty($path) && in_array(pathinfo($path)['extension'], ['pdf'])) {
                return '/images/pdf-file.jpg';
            }

            if (!empty($path) && in_array(pathinfo($path)['extension'], ['docx','doc'])) {
                return '/images/doc-file.jpg';
            }

            if (!empty($path) && in_array(pathinfo($path)['extension'], ['xlsx'])) {
                return '/images/excel-file.png';
            }
        } catch (Exception $e){
//            dd($path,pathinfo($path),$e->getMessage());
        }


        if($type === 'image'){
            $file = '/images/no-image.png';
        } elseif($type === 'file'){
            $file = '/images/no-file.svg';
        }

        if (!empty($path) && in_array(pathinfo($path)['extension'], ['mp4', 'm4v', '3gp'])) {
            $file = '/images/video-placeholder.png';
        } elseif (!empty($path)) {
            $file = $path;
        }

        if (!empty($path) && pathinfo($path)['extension'] == 'svg') {
            return asset('storage/media/' . trim($file, '/'));
        }

        return route('imagecache', ['template' => $template, 'filename' => trim($file, '/')], $absolute);
    }

}
