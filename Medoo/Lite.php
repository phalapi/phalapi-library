<?php
/**
 * Medoo数据库驱动
 *
 * 参考：http://medoo.lvtao.net/
 * 注意：使用此类请在db配置文件中加上数据库类型
 *
 * 例如：'servers' => array(
 *          'db_demo' => array(                         //服务器标记
 *              'type'      => 'mysql',                 //数据库类型   (mysql|mssql|oracle|pgsql|sqlite|sybase)
 *              'host'      => 'localhost',             //数据库域名
 *              'name'      => 'test',                  //数据库名字
 *              'user'      => 'root',                  //数据库用户名
 *              'password'  => '123456',	            //数据库密码
 *              'port'      => '3306',                  //数据库端口
 *              'charset'   => 'UTF8',                  //数据库字符集
 *          ),
 *       ),
 *
 * 需要导入语言文件：https://git.oschina.net/xiaoxunzhao/freeApi/tree/sqldebug/Language?dir=1&filepath=Language&oid=9f95e2e993578ea98cd45d11df2aa8a2c8473926&sha=d6dc0cbd9b4ba6ce8e49bdc93579cdc1d9ab4710
 *
 * 需要导入错误码文件：https://git.oschina.net/xiaoxunzhao/freeApi/blob/sqldebug/PhalApi/ReturnCode.php?dir=0&filepath=PhalApi%2FReturnCode.php&oid=081656a55fa056a41b13bb16f342bf3beae6fa4e&sha=d6dc0cbd9b4ba6ce8e49bdc93579cdc1d9ab4710
 *
 * @author: xiaoxunzhao 2015-10-25
 */
require_once dirname(__FILE__) . '/Medoo.php';

class Medoo_Lite {
    /**
     * @param $configName string 需要加载的配置文件的文件名称
     * @throws PhalApi_Exception
     */
    public function __construct( $configName ){

       $dbConfig = DI()->config->get( $configName );
       if( empty($dbConfig) ){
           throw new PhalApi_Exception(
               T('NOT_EXISTS', ['DBConfig']) , ReturnCode::NOT_EXISTS
           );
       }
       $tables = $dbConfig['tables'];

       if( empty($tables) ){
           throw new PhalApi_Exception(
               T('NOT_EXISTS', ['tables']) , ReturnCode::NOT_EXISTS
           );
       }
       if( empty($dbConfig['servers']) ){
           throw new PhalApi_Exception(
               T('NOT_EXISTS', ['servers']) , ReturnCode::NOT_EXISTS
           );
       }
       foreach( $tables as $key => $value ){
           if( empty($value['map']) ){
               throw new PhalApi_Exception(
                   T('NOT_EXISTS', ['map']) , ReturnCode::NOT_EXISTS
               );
           }
           if( isset($dbConfig['servers'][$value['map'][0]['db']]) ){
               $className = 'PhalApi_DB_'.strtolower($dbConfig['servers'][$value['map'][0]['db']]['type']);
               $dbConfig['servers'][$value['map'][0]['db']]['type'] = $className;
               if( $key == '__default__' ){
                   DI()->db = new medoo($dbConfig['servers'][$value['map'][0]['db']]);
               }else{
                   DI()->$key = new medoo($dbConfig['servers'][$value['map'][0]['db']]);
               }
           }

       }
   }

}