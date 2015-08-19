<?php

/*
/**
 * 组与用户关联模型类
 * @author: hms 2015-8-6
 */

class Model_Auth_Access extends PhalApi_Model_NotORM
{

    protected function getTableName($id)
    {
        return DI()->config->get('app.auth.auth_group_access');
    }

    public function assUser($param)
    {
        $r = $this->getORM()->insert_multi($param);
        return $r === false ? false : true;
    }

    public function delByUid($uid)
    {
        $r = $this->getORM()->where('uid', $uid)->delete();
        return $r === false ? false : true;
    }


}
