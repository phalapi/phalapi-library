<?php

class Domain_User_User_Login_Qq {

    public function isFirstBind($openId) {
        $model = new Model_User_UserLoginQq();
        return $model->isFirstBind($openId);
    }

    public function getUserIdByQqOpenId($openId) {
        if (empty($openId)) {
            return array();
        }

        $model = new Model_User_UserLoginQq();
        return $model->getUserIdByQqOpenId($openId);
    }

    public function bindUser($userId, $openId, $token, $expiresIn) {
        $data = array();
        $data['qq_openid'] = $openId;
        $data['qq_token'] = $token;
        $data['qq_expires_in'] = $expiresIn;
        $data['user_id'] = $userId;

        $model = new Model_User_UserLoginQq();
        return $model->insert($data);
    }
}
