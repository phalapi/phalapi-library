<?php

class Model_User_UserLoginSina extends PhalApi_Model_NotORM {

    protected function getTableName($id) {
        return 'user_login_sina';
    }

    public function getBindInfo($openId) {
        $rs = $this->getORM()->where('sina_openid', $openId)->fetch();
        return !empty($rs) ? $rs : array();
    }

    public function isFirstBind($openId) {
        $num = $this->getORM()->where('sina_openid', $openId)->count('id');
        return $num == 0 ? true : false;
    }

    public function getUserIdBySinaOpenId($openId) {
        $rs = $this->getORM()->select('user_id')->where('sina_openid', $openId)->fetch();
        return !empty($rs) ? intval($rs['user_id']) : 0;
    }
}
