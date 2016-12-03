<?php
/**
 *   加密工具类
 *
 * User: jiehua
 * Date: 16/3/30
 * Time: 下午3:25
 */

/**
 * 加密方法
 *
 * @param string $str
 * @param string $secret_key
 *
 * @return string
 */
 function encrypt($str,$secret_key){
    //AES, 128 模式加密数据 CBC
    $secret_key = base64_decode($secret_key);
    $str = trim($str);
    $str = addPKCS7Padding($str);
    $iv = mcrypt_create_iv(mcrypt_get_iv_size(MCRYPT_RIJNDAEL_128,MCRYPT_MODE_CBC),1);
    $encrypt_str =  mcrypt_encrypt(MCRYPT_RIJNDAEL_128, $secret_key, $str, MCRYPT_MODE_CBC);
    return base64_encode($encrypt_str);
}

/**
 * 解密方法
 *
 * @param string $str
 * @param string $secret_key
 *
 * @return string
 */
 function decrypt($str,$secret_key){
    //AES, 128 模式加密数据 CBC
    $str = base64_decode($str);
    $secret_key = base64_decode($secret_key);
    $iv = mcrypt_create_iv(mcrypt_get_iv_size(MCRYPT_RIJNDAEL_128,MCRYPT_MODE_CBC),1);
    $encrypt_str =  mcrypt_decrypt(MCRYPT_RIJNDAEL_128, $secret_key, $str, MCRYPT_MODE_CBC);
    $encrypt_str = trim($encrypt_str);

    $encrypt_str = stripPKSC7Padding($encrypt_str);
    return $encrypt_str;

}

/**
 * 填充算法
 * @param string $source
 * @return string
 */
function addPKCS7Padding($source){
    $source = trim($source);
    $block = mcrypt_get_block_size(MCRYPT_RIJNDAEL_128, MCRYPT_MODE_CBC);

    $pad = $block - (strlen($source) % $block);
    if ($pad <= $block) {
        $char = chr($pad);
        $source .= str_repeat($char, $pad);
    }
    return $source;
}
/**
 * 移去填充算法
 * @param string $source
 * @return string
 */
function stripPKSC7Padding($source){
    $source = trim($source);
    $char = substr($source, -1);
    $num = ord($char);
    if($num==62)return $source;
    $source = substr($source,0,-$num);
    return $source;
}