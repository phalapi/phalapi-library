<?php

/*
/**
 * 规则模型类
 * @author: hms 2015-8-6
 */

class Model_Auth_Rule extends PhalApi_Model_NotORM
{

    protected function getTableName($id)
    {
        return DI()->config->get('app.auth.auth_rule');
    }

    /**获取列表
     * @param $param
     * @return mixed
     */
    public function getList($param)
    {
        $r = $this->getORM()->select($param['field'])->where('title LIKE ?', "%" . $param['keyWord'] . "%")
            ->or('name LIKE ?', "%" . $param['keyWord'] . "%")
            ->limit($param['limitPage'], $param['limitCount'])
            ->order($param['order'])
            ->fetchAll();
        return $r;
    }

    public function getInfo($id)
    {
        $r = $this->getORM()->where('id', $id)->fetchOne();
        return $r;
    }

    /**获取总数
     * @param $keyWord
     * @return mixed
     */
    public function getCount($keyWord)
    {
        $r = $this->getORM()->where('title LIKE ? ', "%" . $keyWord . "%")
            ->or('name LIKE ?', "%" . $keyWord . "%")
            ->count();
        return $r;
    }

    /**添加规则
     * @param $param
     * @return bool
     */
    public function addRule($param)
    {
        $rom = $this->getORM();
        $rom->insert($param);
        return empty($rom->insert_id()) ? false : true;
    }

    /**修改规则
     * @param $id
     * @param $info
     * @return bool
     */
    public function editRule($id, $info)
    {
        $r = $this->getORM()->where('id', $id)->update($info);
        return $r === false ? false : true;
    }

    /** 删除规则
     * @param $ids
     * @return bool
     */
    public function delRule($ids)
    {
        $r = $this->getORM()->where('id', $ids)->delete();
        return $r === false ? false : true;
    }

    /**
     * 检测规则标识是否重复
     * @param string $name
     * @param int $id
     * @return boolean
     */
    public function checkRepeat($name, $id = 0)
    {
        $r = $this->getORM()->select('id')->where('name', $name)->where('id != ?', $id)->fetchOne();
        return !empty($r) ? true : false;
    }
    
    public function getRulesInGroups($gids){
        $rules = $this->getORM()->select('`add_condition`,`name`')
        ->where(array('id' => $gids, 'status' => 1))
        ->fetchAll();
        return $rules;
    }
    
    public function getRulesInGroupsCache($gids) {
      $rules = DI()->cache->get( 'rulesInGroups'); //缓存读取
        if ($rules == null) {
            $rules=self::getRulesInGroups($gids);
            DI()->cache->set('rulesInGroups', $rules);
        }
        return $rules;
    }


}
