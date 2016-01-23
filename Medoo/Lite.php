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
 * @author: xiaoxunzhao 2015-10-25
 */
require_once dirname(__FILE__) . '/Medoo.php';
require_once dirname(__FILE__) . '/ReturnCode.php';

class Medoo_Lite {
    /**
     * @param $configName string 需要加载的配置文件的文件名称
     * @throws PhalApi_Exception
     */
    public function __construct( $configName = NULL ){

        PhalApi_Translator::addMessage(API_ROOT.'/Library/Medoo');

        $configName = $configName?$configName:"dbs";
        $dbConfig = DI()->config->get( $configName );
        if( empty($dbConfig) ){
           throw new PhalApi_Exception(
               T('NOT_EXISTS', array('DBConfig')) , ReturnCode::NOT_EXISTS
           );
       }
        $tables = $dbConfig['tables'];

        if( empty($tables) ){
           throw new PhalApi_Exception(
               T('NOT_EXISTS', array('tables')) , ReturnCode::NOT_EXISTS
           );
       }
        if( empty($dbConfig['servers']) ){
           throw new PhalApi_Exception(
               T('NOT_EXISTS', array('servers')) , ReturnCode::NOT_EXISTS
           );
       }
        foreach( $tables as $key => $value ){
           if( empty($value['map']) ){
               throw new PhalApi_Exception(
                   T('NOT_EXISTS', array('map')) , ReturnCode::NOT_EXISTS
               );
           }
           if( isset($dbConfig['servers'][$value['map'][0]['db']]) ){
            //   代码预留，建议不用开启
            //   $className = 'PhalApi_DB_'.strtolower($dbConfig['servers'][$value['map'][0]['db']]['type']);
            //   $dbConfig['servers'][$value['map'][0]['db']]['type'] = $className;
               if( $key == '__default__' ){
                   DI()->medooLite = new medoo($dbConfig['servers'][$value['map'][0]['db']]);
               }else{
                   DI()->$key = new medoo($dbConfig['servers'][$value['map'][0]['db']]);
               }
           }

       }
   }

}