<?php

class Auth_Lite
{

    public function __construct($debug = false)
    {
        $this->init($debug);
    }

    protected function init($debug)
    {
        DI()->loader->addDirs('./Library/Auth/Auth');
        PhalApi_Translator::addMessage(API_ROOT . '/Library/Auth');
    }

    /**
     * 检查权限
     * @param name string|array  需要验证的规则列表,支持逗号分隔的权限规则或索引数组
     * @param uid  int           认证用户的id
     * @param string mode        执行check的模式 默认为phalApi模式，也可以支持Url模式
     * @param relation string    如果为 'or' 表示满足任一条规则即通过验证;如果为 'and'则表示需满足所有规则才能通过验证
     * @return boolean           通过验证返回true;失败返回false
     */
    public function check($name, $uid, $type = 1, $mode = 'api', $relation = 'or')
    {
        //判断权限检测开关
        if (!DI()->config->get('app.auth.auth_on'))
            return true;
        //判断是不是免检用户
        if (in_array($uid, (array)DI()->config->get('app.auth.auth_not_check_user')))
            return true;

        $authList = $this->getAuthList($uid, $type); //获取用户需要验证的所有有效规则列表

        if (is_string($name)) {
            $name = strtolower($name);
            if (strpos($name, ',') !== false) {
                $name = explode(',', $name);
            } else {
                $name = array($name);
            }
        }

        $list = array(); //保存验证通过的规则名
        if ($mode == 'url') {
            $req = unserialize(strtolower(serialize($_REQUEST)));
        }

        foreach ($authList as $auth) {
            $query = preg_replace('/^.+\?/U', '', $auth);
            if ($mode == 'url' && $query != $auth) {
                parse_str($query, $param); //解析规则中的param
                $intersect = array_intersect_assoc($req, $param);
                $auth = preg_replace('/\?.*$/U', '', $auth);
                if (in_array($auth, $name) && $intersect == $param) {  //如果节点相符且url参数满足
                    $list[] = $auth;
                }
            } else if ($mode == 'api') {
                if (in_array($auth, $name)) {
                    $list[] = $auth;
                }
            } else if (in_array($auth, $name)) {
                $list[] = $auth;
            }
        }
        if ($relation == 'or' and !empty($list)) {
            return true;
        }
        $diff = array_diff($name, $list);
        if ($relation == 'and' and empty($diff)) {
            return true;
        }
        return false;
    }

    /**
     * 根据用户id获取组,返回值为数组
     * @param  int $uid     用户id
     * @return array       用户所属的组
     */
    public function getGroups($uid)
    {
        static $groups = array();
        if (isset($groups[$uid]))
            return $groups[$uid];
        $groupDomain=new Domain_Auth_Group();
        $user_groups=$groupDomain->getUserInGroups($uid);
        $groups[$uid] = $user_groups ?: array();
        return $groups[$uid];
    }

    /**
     * 获得权限列表
     * @param integer $uid 用户id
     * @param integer $type
     * @return array
     */
    protected function getAuthList($uid, $type)
    {
        static $_authList = array(); //保存用户验证通过的权限列表
        $t = implode(',', (array)$type);
        if (isset($_authList[$uid . $t])) {
            return $_authList[$uid . $t];
        }
        if (DI()->config->get('app.auth.auth_type') == 2 && isset($_SESSION['_AUTH_LIST_' . $uid . $t])) {
            return $_SESSION['_AUTH_LIST_' . $uid . $t];
        }

        //读取用户所属组
        $groups = $this->getGroups($uid);
        $ids = array(); //保存用户所属组设置的所有权限规则id
        foreach ($groups as $g) {
            $ids = array_merge($ids, explode(',', trim($g['rules'], ',')));
        }
        $ids = array_unique($ids);
        if (empty($ids)) {
            $_authList[$uid . $t] = array();
            return array();
        }

        $ruleDomain=new Domain_Auth_Rule();
       $rules= $ruleDomain->getUserInRules($uid,$ids, $type);
        
        //循环规则，判断结果。
        $authList = array();   //
        $userDomain=new Domain_Auth_User();
        foreach ($rules as $rule) {
            if (!empty($rule['condition'])) { //根据addcondition进行验证
                $user = $userDomain->getUserInfo($uid); //获取用户信息,一维数组

                $command = preg_replace('/\{(\w*?)\}/', '$user[\'\\1\']', $rule['add_condition']);
                @(eval('$condition=(' . $command . ');'));
                if ($condition) {
                    $authList[] = strtolower($rule['name']);
                }
            } else {
                //只要存在就记录
                $authList[] = strtolower($rule['name']);
            }
        }
        $_authList[$uid . $t] = $authList;
        if (DI()->config->get('app.auth.auth_type') == 2) {
            //规则列表结果保存到session
            $_SESSION['_AUTH_LIST_' . $uid . $t] = $authList;
        }
        return array_unique($authList);
    }

}
