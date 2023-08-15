<?php

if (!function_exists('get_file_uri')) {

    function get_file_uri($file) {
        return !is_null($file)
            ? asset('/storage/files/' . trim($file, '/'))
            : null;
    }

}
