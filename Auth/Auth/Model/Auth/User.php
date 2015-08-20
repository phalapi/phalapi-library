<?php

/*
/**
 * 用户模型
 * @author: hms 2015-8-6
 */

class Model_Auth_User extends PhalApi_Model_NotORM
{

    protected function getTableName($id)
    {
        return DI()->config->get('app.auth.auth_user');
    }
    
    public   function getUserInfo($uid){
        static $userinfo = array();
        if (!isset($userinfo[$uid])) {
            $userinfo[$uid] = $this->getORM()->where('id', $uid)->fetchOne();
        }
        return $userinfo[$uid];
    }
}
