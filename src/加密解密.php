<?php
/**
 * Created by PhpStorm.
 * User: zikobe
 * Date: 2019/4/20
 * Time: 10:42
 */
/**
 * 加密函数
 * @param   string  $str    加密前的字符串
 * @param   string  $key    密钥
 * @return  string  加密后的字符串
 */
function encrypt($str, $key)
{
    $coded = '';
    $keyLength = strlen($key);

    for ($i = 0, $count = strlen($str); $i < $count; $i += $keyLength)
    {
        $coded .= substr($str, $i, $keyLength) ^ $key;
    }

    return str_replace('=', '', base64_encode($coded));
}

/**
 * 解密函数
 * @param   string  $str    加密后的字符串
 * @param   string  $key    密钥
 * @return  string  加密前的字符串
 */
function decrypt($str, $key)
{
    $coded = '';
    $keyLength = strlen($key);
    $str = base64_decode($str);

    for ($i = 0, $count = strlen($str); $i < $count; $i += $keyLength)
    {
        $coded .= substr($str, $i, $keyLength) ^ $key;
    }

    return $coded;
}
