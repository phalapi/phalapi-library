<?php

/**
 * Class Api_Auth_Rule 规则接口服务类
 * @author: hms 2015-6-8
 */
class Api_Auth_Rule extends PhalApi_Api
{

    private static $Domain = null;

    public function __construct()
    {
        if (self::$Domain == null) {
            self::$Domain = new Domain_Auth_Rule();
        }
    }

    public function getRules()
    {
        return array(
            'getList' => array(
                'keyWord' => array('name' => 'keyword', 'type' => 'string', 'default' => '', 'desc' => '关键词'),
                'field' => array('name' => 'field', 'type' => 'string', 'default' => '*', 'desc' => '返回字段'),
                'limitPage' => array('name' => 'limit_page', 'type' => 'int', 'default' => '0', 'desc' => '分页页码'),
                'limitCount' => array('name' => 'limit_count', 'type' => 'int', 'default' => '20', 'desc' => '单页记录条数，默认为20'),
                'order' => array('name' => 'order', 'type' => 'string', 'default' => '', 'desc' => '排序参数，如：xx ASC,xx DESC')
            ),
            'getInfo' => array(
                'id' => array('name' => 'id', 'type' => 'int', 'require' => true, 'min' => 1, 'desc' => '规则id')
            ),
            'add' => array(
                'name' => array('name' => 'name', 'type' => 'string', 'require' => true, 'desc' => '规则标识'),
                'title' => array('name' => 'title', 'type' => 'string', 'default' => '', 'desc' => '规则描述'),
                'type' => array('name' => 'type', 'type' => 'int', 'default' => 1, 'desc' => '类型，1.Api，2.url'),
                'status' => array('name' => 'status', 'type' => 'int', 'default' => 1, 'desc' => '状态，1.正常，0.禁用'),
                'add_condition' => array('name' => 'condition', 'type' => 'string', 'default' => '', 'desc' => '附加条件')
            ),
            'edit' => array(
                'id' => array('name' => 'id', 'type' => 'int', 'require' => true, 'min' => 1, 'desc' => '修改的规则id'),
                'name' => array('name' => 'name', 'type' => 'string', 'require' => true, 'desc' => '规则标识'),
                'title' => array('name' => 'title', 'type' => 'string', 'default' => '', 'desc' => '规则描述'),
                'type' => array('name' => 'type', 'type' => 'int', 'default' => 1, 'desc' => '类型，1.Api，2.url'),
                'status' => array('name' => 'status', 'type' => 'int', 'default' => 1, 'desc' => '状态，1.正常，0.禁用'),
                'add_condition' => array('name' => 'condition', 'type' => 'string', 'desc' => '附加条件')
            ),
            'del' => array(
                'ids' => array('name' => 'ids', 'type' => 'string', 'require' => true, 'default' =>'', 'min' => 1, 'desc' => '规则id，逗号隔开多个')
            )
        );


    }

    /**
     * 获取规则列表
     * @return int code 业务代码
     * @return object info 规则信息对象
     * @return object info.items 组数据行
     * @return int info.count 数据总数，用于分页
     * @return string msg 业务消息
     */
    public function getList()
    {
        $rs = array('code' => 0, 'info' => array(), 'msg' => '');
        $rs['info'] = self::$Domain->getList($this);
        return $rs;
    }

    /**获取单个规则信息
     * @return int code 业务代码：0.获取成功，1.获取失败
     * @return object info 规则信息对象,获取失败为空
     * @return string msg 业务消息
     */
    public function getInfo()
    {
        $rs = array('code' => 0, 'info' => array(), 'msg' => '');
        $r = self::$Domain->getInfo($this->id);
        if (is_array($r)) {
            $rs['info'] = $r;
        } else {
            $rs['code'] = 1;
            $rs['msg'] = T('data get failed');
        }
        return $rs;

    }

    /**
     * 创建规则
     * @return int code 业务代码：0.操作成功，1.操作失败，2.规则标识重复
     * @return string msg 业务消息
     */
    public function add()
    {
        $rs = array('code' => 0, 'msg' => '');
        $r = self::$Domain->addRule($this);
        if ($r == 0) {
            $rs['msg'] = T('success');
        } else if ($r == 1) {
            $rs['msg'] = T('failed');
        } else if ($r == 2) {
            $rs['msg'] = T('rule name repeat');
        }
        $rs['code'] = $r;
        return $rs;

    }

    /**
     * 修改规则
     * @return int code 业务代码：0.操作成功，1.操作失败，2.规则重复
     * @return string msg 业务消息
     */
    public function edit()
    {
        $rs = array('code' => 0, 'msg' => '');
        $r = self::$Domain->editRule($this);
        if ($r == 0) {
            $rs['msg'] = T('success');
        } else if ($r == 1) {
            $rs['msg'] = T('failed');
        } else if ($r == 2) {
            $rs['msg'] = T('rule name repeat');
        }
        $rs['code'] = $r;
        return $rs;

    }

    /**
     * 删除规则
     * @return int code 业务代码：0.操作成功，1.操作失败
     * @return string msg 业务消息
     */
    public function del()
    {
        $rs = array('code' => 0, 'msg' => '');
        $r = self::$Domain->delRule($this->ids);
        if ($r == 0) {
            $rs['msg'] = T('success');
        } else {
            $rs['msg'] = T('failed');
        }
        $rs['code'] = $r;
        return $rs;
    }
}