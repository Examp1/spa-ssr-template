<?php

/**
 * Return name for attribute name html field
 */
if (!function_exists('constructor_field_name')) {

    function constructor_field_name($key, $name) {
        $fieldName = config('constructor.fields_name', null);

        $normalizeName = array_map(function ($item) {
            return '[' . $item . ']';
        }, explode('.', $name));

        if ($fieldName && is_string($name) && is_array($normalizeName)) {
            return $fieldName . '[' . $key . ']' . implode('', $normalizeName);
        }

        return (string) $key . '[' . $name . ']';
    }

}

/**
 * Return name for old and error functions in form with dot
 */
if (!function_exists('constructor_field_name_dot')) {

    function constructor_field_name_dot($key, $name) {
        $fieldName = config('constructor.fields_name', null);

        if ($fieldName && is_string($name)) {
            return $fieldName . '.' . $key . '.' . $name;
        }

        return (string) $key . '.' . $name;
    }

}
