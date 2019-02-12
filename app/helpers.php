<?php

/**
 * @return null|App\Models\User
 */
function current_user()
{
    static $user = null;

    if ($user === null) {
        $user = JWTAuth::parseToken()->toUser();
    }

    return $user;
}

function current_user_id()
{
    return current_user()->id;
}


/**
 * 将 base64 字符串转为 url 安全的 base64 字符串
 * @param string $base64
 * @return string
 */
function base64_url_safe_encode($base64) {
    $base64 = str_replace('+', '-', $base64);
    $base64 = str_replace('/', '_', $base64);
    $base64 = str_replace('=', '', $base64);

    return $base64;
}
/**
 * 将 url 安全的 base64 字符串转为 base64 字符串
 * @param string $base64
 * @return string
 */
function base64_url_safe_decode($safeBase64) {
    $base64 = str_replace('-', '+', $safeBase64);
    $base64 = str_replace('_', '/', $base64);

    $len = strlen($base64);
    if ($len % 4 == 2) {
        $base64 .= '==';
    } elseif ($len % 4 == 3) {
        $base64 .= '=';
    }

    return $base64;
}
