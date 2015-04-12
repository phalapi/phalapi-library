<?php

class Domain_User_User_Login_Weixin {

    public function isFirstBind($openId) {
        $model = new Model_User_UserLoginWeixin();
        return $model->isFirstBind($openId);
    }

    public function getUserIdByWxOpenId($openId) {
        if (empty($openId)) {
            return array();
        }

        $model = new Model_User_UserLoginWeixin();
        return $model->getUserIdByWxOpenId($openId);
    }

    public function bindUser($userId, $openId, $token, $expiresIn) {
        $data = array();
        $data['wx_openid'] = $openId;
        $data['wx_token'] = $token;
        $data['wx_expires_in'] = $expiresIn;
        $data['user_id'] = $userId;

        $model = new Model_User_UserLoginWeixin();
        return $model->insert($data);
    }
}
