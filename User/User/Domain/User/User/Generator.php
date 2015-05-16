<?php
/**
 * 用户生成器
 *
 * - 可用于自动生成一个新用户
 *
 * @author Aevit, dogstar
 */

class Domain_User_User_Generator {

    /**
     * 为微信用户生成新用户
     * @return int 用户id
     */
    public static function createUserForWeixin($openId, $nickname = '', $avatar = '') {
        return self::createUser('wx_' . md5($openId), $nickname, $avatar);
    }

    /**
     * 为新浪微博用户生成新用户
     * @return int 用户id
     */
    public static function createUserForSina($openId, $nickname = '', $avatar = '') {
        return self::createUser('sina_' . md5($openId), $nickname, $avatar);
    }

    /**
     * 为QQ用户生成新用户
     * @return int 用户id
     */
    public static function createUserForQq($openId, $nickname = '', $avatar = '') {
        return self::createUser('qq_' . md5($openId), $nickname, $avatar);
    }

    /**
     * 生成新用户 - 通用入口
     *
     * @param string $username 用户名
     * @param string $nickname 昵称
     * @param string $avatar 头像链接
     * @return int 用户id
     */
    protected static function createUser($username, $nickname, $avatar) {
        $newUserInfo = array();
        $newUserInfo['username'] = $username;
        $newUserInfo['nickname'] = $nickname;
        $newUserInfo['avatar'] = !empty($avatar) ? $avatar : '';

        $newUserInfo['salt'] = PhalApi_Tool::createRandStr(32);
        $newUserInfo['password'] = '******';
        $newUserInfo['reg_time'] = $_SERVER['REQUEST_TIME'];

        $userModel = new Model_User_User();
        return $userModel->insert($newUserInfo);
    }
}
