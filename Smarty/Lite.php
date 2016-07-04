<?php
/**
 * Created by PhpStorm.
 * Author: yidiec
 * CreateTime: 2016/6/27 17:04
 * Description: <请描述这个类是干什么的>
 * Versioncode: 1 <每次修改提交前+1>
 */
require_once(dirname(__FILE__) . '/Smarty/Smarty.class.php');

class Smarty_Lite extends Smarty {

    //模板相对路径
    protected $p_dir = 'View';
    //视图类型
    protected $p_type = 'Default';
    //接口模块名称
    protected $apiClassName = "";
    //接口名称
    protected $action = "";

    public function __construct($templateDir) {

        //获取模块名
        $service = DI()->request->get('service', 'Default.Index');
        list($this->apiClassName, $this->action) = explode('.', $service);

        parent::__construct();
        if (!empty($templateDir)) {
            $this->p_dir = $templateDir;
        }
        if (!empty($apiClassName)) {
            $this->p_type = $apiClassName;
        }
        $dir = array(API_ROOT."/$this->p_dir/$this->p_type/");
        $this->setTemplateDir($dir);
    }

    /**
     * 注入参数
     */
    public function setParams($param = array()) {

        foreach ($param as $k => $v) {
            $this->assign($k, $v);
        }
    }

    /**
     * 渲染模板
     */
    public function show($Api = "") {

        if ($Api != "") {
            list($apiClassName, $action) = explode('.', $Api);

            if ($apiClassName != "" && $action != "" && ($apiClassName != $this->apiClassName || $action != $this->action)) {
                $api          = PhalApi_ApiFactory::generateService();
                $this->action = $action;
                call_user_func(array($api, $action));
            }
        }

        $this->display($this->action . '.tpl');
        exit();
    }

}