<?php
/**
 * User扩展 － 登录服务
 * @author dogstar 20170312
 */

class Api_User_User_Login extends PhalApi_Api {

    public function getRules() {
        return array(
            'weixin' => array(
                'openId' => array('name' => 'wx_openid', 'require' => true, 'min' => 1, 'max' => 28),
                'token' => array('name' => 'wx_token', 'require' => true, 'min' => 1, 'max' => 150),
                'expiresIn' => array('name' => 'wx_expires_in', 'require' => true, 'min' => 1, 'max' => 10),
                'nickname' => array('name' => 'name', 'default' => '',),
                'avatar' => array('name' => 'avatar', 'default' => '',),
            ),
            'sina' => array(
                'openId' => array('name' => 'sina_openid', 'require' => true, 'min' => 1, 'max' => 28),
                'token' => array('name' => 'sina_token', 'require' => true, 'min' => 1, 'max' => 150),
                'expiresIn' => array('name' => 'sina_expires_in', 'require' => true, 'min' => 1, 'max' => 10),
                'nickname' => array('name' => 'name', 'default' => '',),
                'avatar' => array('name' => 'avatar', 'default' => '',),
            ),
            'qq' => array(
                'openId' => array('name' => 'qq_openid', 'require' => true, 'min' => 1, 'max' => 28),
                'token' => array('name' => 'qq_token', 'require' => true, 'min' => 1, 'max' => 150),
                'expiresIn' => array('name' => 'qq_expires_in', 'require' => true, 'min' => 1, 'max' => 10),
                'nickname' => array('name' => 'name', 'default' => '',),
                'avatar' => array('name' => 'avatar', 'default' => '',),
            ),
        );
    }

    /**
     * 微信登录
     *
     * - 首次绑定时，会自动创建新用户
     * - 当逻辑有冲突，或者数据库写入失败时，以异常返回
     */
    public function weixin()
    {
        $rs = array('code' => 0, 'info' => array(), 'msg' => '');

        $domain = new Domain_User_User_Login_Weixin();
        $isFirstBind = $domain->isFirstBind($this->openId);

        $userId = 0;
        if ($isFirstBind) {
            $userId = Domain_User_User_Generator::createUserForWeixin($this->openId, $this->nickname, $this->avatar);
            if ($userId <= 0) {
                //异常1：用户创建失败
                DI()->logger->error('failed to create weixin user', array('openId' => $this->openId));
                throw new PhalApi_Exception_InternalServerError(T('failed to create weixin user'));
            }

            $id = $domain->bindUser($userId, $this->openId, $this->token, $this->expiresIn);
            if ($id <= 0) {
                //异常2：绑定微信失败
                DI()->logger->error('failed to bind user with weixin', 
                    array('userid' => $userId, 'openId' => $this->openId));
                throw new PhalApi_Exception_InternalServerError(T('failed to bind user with weixin'));
            }
        } else {
            $userId = $domain->getUserIdByWxOpenId($this->openId);
        }

        if ($userId <= 0) {
            //异常3：微信用户不存在
            DI()->logger->error('weixin user not found', 
                array('userid' => $userId, 'openId' => $this->openId));
            throw new PhalApi_Exception_InternalServerError(T('weixin user not found'));
        }

        $token = Domain_User_User_Session::generate($userId);

        $rs['info']['user_id'] = $userId;
        $rs['info']['token'] = $token;
        $rs['info']['is_new'] = $isFirstBind ? 1 : 0;

        return $rs;
    }

    /**
     * 新浪微博登录
     *
     * - 首次绑定时，会自动创建新用户
     * - 当逻辑有冲突，或者数据库写入失败时，以异常返回
     */
    public function sina()
    {
        $rs = array('code' => 0, 'info' => array(), 'msg' => '');

        $domain = new Domain_User_User_Login_Sina();
        $isFirstBind = $domain->isFirstBind($this->openId);

        $userId = 0;
        if ($isFirstBind) {
            $userId = Domain_User_User_Generator::createUserForSina($this->openId, $this->nickname, $this->avatar);
            if ($userId <= 0) {
                //异常1：用户创建失败
                DI()->logger->error('failed to create sina user', array('openId' => $this->openId));
                throw new PhalApi_Exception_InternalServerError(T('failed to create sina user'));
            }

            $id = $domain->bindUser($userId, $this->openId, $this->token, $this->expiresIn);
            if ($id <= 0) {
                //异常2：绑定新浪微博失败
                DI()->logger->error('failed to bind user with sina', 
                    array('userid' => $userId, 'openId' => $this->openId));
                throw new PhalApi_Exception_InternalServerError(T('failed to bind user with sina'));
            }
        } else {
            $userId = $domain->getUserIdBySinaOpenId($this->openId);
        }

        if ($userId <= 0) {
            //异常3：新浪用户不存在
            DI()->logger->error('sina user not found', 
                array('userid' => $userId, 'openId' => $this->openId));
            throw new PhalApi_Exception_InternalServerError(T('sina user not found'));
        }

        $token = Domain_User_User_Session::generate($userId);

        $rs['info']['user_id'] = $userId;
        $rs['info']['token'] = $token;
        $rs['info']['is_new'] = $isFirstBind ? 1 : 0;

        return $rs;
    }

    /**
     * QQ登录
     *
     * - 首次绑定时，会自动创建新用户
     * - 当逻辑有冲突，或者数据库写入失败时，以异常返回
     */
    public function qq()
    {
        $rs = array('code' => 0, 'info' => array(), 'msg' => '');

        $domain = new Domain_User_User_Login_Qq();
        $isFirstBind = $domain->isFirstBind($this->openId);

        $userId = 0;
        if ($isFirstBind) {
            $userId = Domain_User_User_Generator::createUserForQq($this->openId, $this->nickname, $this->avatar);
            if ($userId <= 0) {
                //异常1：用户创建失败
                DI()->logger->error('failed to create qq user', array('openId' => $this->openId));
                throw new PhalApi_Exception_InternalServerError(T('failed to create qq user'));
            }

            $id = $domain->bindUser($userId, $this->openId, $this->token, $this->expiresIn);
            if ($id <= 0) {
                //异常2：绑定新浪微博失败
                DI()->logger->error('failed to bind user with qq', 
                    array('userid' => $userId, 'openId' => $this->openId));
                throw new PhalApi_Exception_InternalServerError(T('failed to bind user with qq'));
            }
        } else {
            $userId = $domain->getUserIdByQqOpenId($this->openId);
        }

        if ($userId <= 0) {
            //异常3：新浪用户不存在
            DI()->logger->error('qq user not found', 
                array('userid' => $userId, 'openId' => $this->openId));
            throw new PhalApi_Exception_InternalServerError(T('qq user not found'));
        }

        $token = Domain_User_User_Session::generate($userId);

        $rs['info']['user_id'] = $userId;
        $rs['info']['token'] = $token;
        $rs['info']['is_new'] = $isFirstBind ? 1 : 0;

        return $rs;
    }
}





