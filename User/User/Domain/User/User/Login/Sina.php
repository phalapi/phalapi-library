<?php

class Domain_User_User_Login_Sina {

    public function isFirstBind($openId) {
        $model = new Model_User_UserLoginSina();
        return $model->isFirstBind($openId);
    }

    public function getUserIdBySinaOpenId($openId) {
        if (empty($openId)) {
            return array();
        }

        $model = new Model_User_UserLoginSina();
        return $model->getUserIdBySinaOpenId($openId);
    }

    public function bindUser($userId, $openId, $token, $expiresIn) {
        $data = array();
        $data['sina_openid'] = $openId;
        $data['sina_token'] = $token;
        $data['sina_expires_in'] = $expiresIn;
        $data['user_id'] = $userId;

        $model = new Model_User_UserLoginSina();
        return $model->insert($data);
    }
}
