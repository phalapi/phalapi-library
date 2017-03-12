<?php
/**
 * User扩展－用户信息服务
 * @author dogstar 20170312
 */

class Api_User_User_Info extends PhalApi_Api {

    public function getRules()
    {
        return array(
            'getUserInfo' => array(
                'otherUserId' => array('name' => 'other_user_id', 'type' => 'int', 'min' => 1, 'require' => true),
            ),
        );
    }

    /**
     * 用户信息
     */
    public function getUserInfo() {
        $rs = array('code' => 0, 'info' => array(), 'msg' => '');

        DI()->userLite->check(true);

        $domain = new Domain_User_User_Info();
        $info = $domain->getUserInfo($this->otherUserId);

        if (empty($info)) {
            $rs['code'] = 1;
            $rs['msg'] = T('can not get user info');

            DI()->logger->debug('can not get user info', $this->otherUserId);

            return $rs;
        }

        $rs['info'] = $info;

        return $rs;
    }
}
