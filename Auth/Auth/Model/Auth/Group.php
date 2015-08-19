<?php

/*
/**
 * 组模型类
 * @author: hms 2015-8-6
 */

class Model_Auth_Group extends PhalApi_Model_NotORM
{

    protected function getTableName($id)
    {
        return DI()->config->get('app.auth.auth_group');
    }

    /**获取列表
     * @param $param
     * @return mixed
     */
    public function getGroupList($param)
    {
        $r = $this->getORM()->select($param['field'])->where('title LIKE ?', "%" . $param['keyWord'] . "%")
            ->limit($param['limitPage'], $param['limitCount'])
            ->order($param['order'])
            ->fetchAll();
        return $r;
    }

    public function getGroupOne($id)
    {
        $r = $this->getORM()->where('id', $id)->fetchOne();
        return $r;
    }

    /**获取总数
     * @param $keyWord
     * @return mixed
     */
    public function getGroupCount($keyWord)
    {
        $r = $this->getORM()->where('title LIKE ?', "%" . $keyWord . "%")->count();
        return $r;
    }

    /**添加组
     * @param $param
     * @return bool
     */
    public function addGroup($param)
    {
        $rom = $this->getORM();
        $rom->insert($param);
        return empty($rom->insert_id()) ? false : true;
    }

    /**修改组
     * @param $id
     * @param $info
     * @return bool
     */
    public function editGroup($id, $info)
    {
        $r = $this->getORM()->where('id', $id)->update($info);
        return $r === false ? false : true;
    }

    /** 删除组
     * @param $ids
     * @return bool
     */
    public function delGroup($ids)
    {
        $r = $this->getORM()->where('id', $ids)->delete();
        return $r === false ? false : true;
    }

    /**
     * 检测组名称是否重复
     * @param type $title
     * @return boolean
     */
    public function checkRepeat($title, $id = 0)
    {
        $r = $this->getORM()->select('id')->where('title', $title)->where('id != ?', $id)->fetchOne();
        return !empty($r) ? true : false;
    }

    /**设置规则
     * @param $id
     * @param $info
     * @return bool
     */
    public function setRules($id, $info)
    {
        $r = $this->getORM()->where('id', $id)->update($info);
        return $r === false ? false : true;
    }


}
