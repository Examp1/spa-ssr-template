<?php

/** Errors list */

defined('VALIDATION_REQUEST_JSON_EXPECTED') or define('VALIDATION_REQUEST_JSON_EXPECTED', 101);
defined('VALIDATION_REQUIRED_FIELD') or define('VALIDATION_REQUIRED_FIELD', 102);
defined('VALIDATION_MODEL_NOT_FOUND') or define('VALIDATION_MODEL_NOT_FOUND', 103);
defined('VALIDATION_MENU_NOT_FOUND') or define('VALIDATION_MENU_NOT_FOUND', 104);
defined('VALIDATION_DATE_FORMAT_NOT_VALID') or define('VALIDATION_DATE_FORMAT_NOT_VALID', 105);
defined('VALIDATION_TIME_FORMAT_NOT_VALID') or define('VALIDATION_TIME_FORMAT_NOT_VALID', 106);
defined('VALIDATION_EMAIL_NOT_VALID') or define('VALIDATION_EMAIL_NOT_VALID', 107);
defined('VALIDATION_EXCEPTION') or define('VALIDATION_EXCEPTION', 108);
defined('VALIDATION_EMAIL_ALREADY_EXISTS') or define('VALIDATION_EMAIL_ALREADY_EXISTS', 109);
defined('VALIDATION_FORM_NOT_FOUND') or define('VALIDATION_FORM_NOT_FOUND', 110);
defined('VALIDATION_UNAUTHORIZED') or define('VALIDATION_UNAUTHORIZED', 111);
defined('VALIDATION_INVALID_LOGIN_PASSWORD_PAIR') or define('VALIDATION_INVALID_LOGIN_PASSWORD_PAIR', 112);
defined('VALIDATION_EXCEEDED_LIMIT_OF_LOGIN_ATTEMPTS') or define('VALIDATION_EXCEEDED_LIMIT_OF_LOGIN_ATTEMPTS', 113);
defined('VALIDATION_ACCOUNT_NOT_VERIFIED_BY_EMAIL') or define('VALIDATION_ACCOUNT_NOT_VERIFIED_BY_EMAIL', 114);
defined('VALIDATION_ACCOUNT_NOT_VERIFIED_BY_PHONE') or define('VALIDATION_ACCOUNT_NOT_VERIFIED_BY_PHONE', 115);
defined('VALIDATION_TOKEN_NOT_VALID') or define('VALIDATION_TOKEN_NOT_VALID', 116);
defined('VALIDATION_FORM_GROUP_NOT_FOUND') or define('VALIDATION_FORM_GROUP_NOT_FOUND', 117);

return [
    VALIDATION_REQUEST_JSON_EXPECTED  => [
        'message' => 'The requested API method is waiting for json input',
    ],
    VALIDATION_REQUIRED_FIELD         => [
        'message' => 'Required field',
    ],
    VALIDATION_MODEL_NOT_FOUND        => [
        'message' => 'Model not found',
    ],
    VALIDATION_MENU_NOT_FOUND         => [
        'message' => 'Menu not found',
    ],
    VALIDATION_DATE_FORMAT_NOT_VALID  => [
        'message' => 'Date format not valid, we need in the format \'YYYY-MM-DD\'',
    ],
    VALIDATION_TIME_FORMAT_NOT_VALID  => [
        'message' => 'Time format not valid, we need in the format \'HH:MM\'',
    ],
    VALIDATION_EMAIL_NOT_VALID        => [
        'message' => 'Email not valid',
    ],
    VALIDATION_EXCEPTION        => [
        'message' => 'Exception',
    ],
    VALIDATION_EMAIL_ALREADY_EXISTS        => [
        'message' => 'Email already exists',
    ],
    VALIDATION_FORM_NOT_FOUND         => [
        'message' => 'Form not found',
    ],
    VALIDATION_FORM_GROUP_NOT_FOUND         => [
        'message' => 'Form group not found',
    ],
    VALIDATION_UNAUTHORIZED        => [
        'message' => 'Unauthorized',
    ],
    VALIDATION_INVALID_LOGIN_PASSWORD_PAIR        => [
        'message' => 'Invalid login password pair',
    ],
    VALIDATION_EXCEEDED_LIMIT_OF_LOGIN_ATTEMPTS        => [
        'message' => 'Exceeded the limit of login attempts',
    ],
    VALIDATION_ACCOUNT_NOT_VERIFIED_BY_EMAIL        => [
        'message' => 'Account not verified by email',
    ],
    VALIDATION_ACCOUNT_NOT_VERIFIED_BY_PHONE        => [
        'message' => 'Account not verified by phone',
    ],
    VALIDATION_TOKEN_NOT_VALID        => [
        'message' => 'Token not valid',
    ]
];
