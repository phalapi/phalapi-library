<?php
/**
 * 七牛接口调用
 * 1、图片文件上传
 *
 * 参考：http://developer.qiniu.com/docs/v6/sdk/php-sdk.html
 *
 * @author: dogstar 2014-10-28
 */

DI()->loader->loadFile('./ThirdParty/qiniu/io.php');
DI()->loader->loadFile('./ThirdParty/qiniu/rs.php');

class Common_QiniuClient
{
    /**
     * 文件上传
     * @param string $filePath 待上传文件的绝对路径
     * @return string 上传成功后的URL，失败时返回空
     */
    public static function uploadFile($filePath)
    {
        $fileUrl = '';

        if (!file_exists($filePath)) {
            return $fileUrl;
        }

        $config = DI()->config->get('app.qiniu');
        $fileName = date('YmdHis_', $_SERVER['REQUEST_TIME']) . md5(Util_Str::randStr(8) . microtime(true));

        Qiniu_SetKeys($config['accessKey'], $config['secretKey']);
        $putPolicy = new Qiniu_RS_PutPolicy($config['space_bucket']);
        $upToken = $putPolicy->Token(null);
        $putExtra = new Qiniu_PutExtra();
        $putExtra->Crc32 = 1;
        list($ret, $err) = Qiniu_PutFile($upToken, $fileName, $filePath, $putExtra);

        if ($err !== null) {
            DI()->logger->debug('failed to upload file to qiniu', 
                array('Err' => $err->Err, 'Reqid' => $err->Reqid, 'Details' => $err->Details, 'Code' => $err->Code));
        } else {
            $fileUrl = $config['space_host'] . '/' . $fileName;
            DI()->logger->debug('succeed to upload file to qiniu', $ret);
        }

        return $fileUrl;
    }
}
