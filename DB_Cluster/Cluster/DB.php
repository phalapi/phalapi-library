<?php

/**
 * 集群数抽象据类
 * @author: 喵了_个咪 <wenzhenxi@vip.qq.com> 2015-10-21
 */
abstract class Cluster_DB{

    private $cluster;           //集群类实例
    private $maindb;            //主表实例
    private $cluster_list;      //集群列表
    private $cluster_config;    //集群配置文件
    private $cluster_dblist;    //数据集群实例
    private $where;             //where条件
    private $select;            //select条件
    private $limit;             //limit条件
    private $order;             //order条件

    /**
     * 获取集群实例类
     */
    abstract protected function getCluster();

    /**
     * 获取主数据库表
     */
    abstract protected function getMainDB();

    /**
     * 快速获取Cluster实例，注意每次获取都是新的实例
     */
    protected function getORM(){
        return $this;
    }

    /**
     * 条件
     */
    public function where($k, $v){
        $this->where[$k] = $v;
        return $this;
    }

    /**
     * 查询
     */
    public function select($str = '*'){
        $this->select = $str;
        return $this;
    }

    /**
     * 分页 @TODO 后期跟进
     */
    public function limit($k, $v){
        $this->limit[$k] = $v;
        return $this;
    }

    /**
     * 排序 @TODO 后期跟进
     */
    public function order($str){
        $this->order = $str;
        return $this;
    }

    /**
     * 计数 @TODO 后期跟进
     */
    public function count($str){
    }

    /**
     * 返回单条结果
     */
    public function fetch(){
        $this->getConfing();
        $rs = $this->Logical('fetch');
        $this->clear();
        return $rs;
    }

    /**
     * 返回结果集
     */
    public function fetchAll(){
        $this->getConfing();
        $rs = $this->Logical('fetchAll');
        return $rs;
    }

    /**
     * 插入集群数据
     */
    public function insert($data){
        $this->getConfing();
        $Cluster_Base = new Cluster_Base();
        $main_data    = $Cluster_Base->mian_Insert_List($this->cluster_config, $data);
        //插入主表
        $rs = $this->maindb->insert($main_data);
        if(!$rs){
            throw new PhalApi_Exception_BadRequest('main Insert the error');
        }
        $id = $this->maindb->insert_id();
        //获取操作库
        $db_table                               = $Cluster_Base->distr_DB($this->cluster_list, $this->cluster_config, $this->cluster_dblist, $id);
        $data[$this->cluster_config['id_name']] = $id;
        $rs                                     = $db_table->insert($data);
        if(!$rs){
            throw new PhalApi_Exception_BadRequest('Cluster Insert the error');
        }
        return $rs[$this->cluster_config['id_name']];
    }

    /**
     * 修改
     */
    public function update($data){
        $Cluster_Base = new Cluster_Base();
        $rs           = $this->fetchAll();
        if(!$rs){
            return 0;
        }
        $arr = array();
        foreach($rs as $k => $v){
            $arr[] = $v[$this->cluster_config['id_name']];
        }
        $data_index = array();
        foreach($this->cluster_config['where'] as $v){
            if($Cluster_Base->getIndex($data, $v)){
                $data_index[$v] = $data[$v];
            }
        }
        //修改基础表
        $return = $this->maindb->where($this->cluster_config['id_name'], $arr)->update($data_index);
        if($return === false){
            throw new PhalApi_Exception_BadRequest(T('update mainDB error'));
        }
        //修改集群
        $return = $this->setIdListByInfo($arr, $data);
        if($return === false){
            throw new PhalApi_Exception_BadRequest(T('update ClusterDB error'));
        }
        $this->clear();
        return $return;
    }

    /**
     * 删除
     */
    public function delete(){
        $rs = $this->fetchAll();
        if(!$rs){
            return 0;
        }
        $arr = array();
        foreach($rs as $k => $v){
            $arr[] = $v[$this->cluster_config['id_name']];
        }
        //删除基础表
        $return = $this->maindb->where($this->cluster_config['id_name'], $arr)->delete();
        if($return === false){
            throw new PhalApi_Exception_BadRequest(T('delete maindb error'));
        }
        //删除集群
        $return = $this->delIdListByInfo($arr);
        if($return === false){
            throw new PhalApi_Exception_BadRequest(T('delete ClusterDB error'));
        }
        $this->clear();
        return $return;
    }

    /**
     * 查询逻辑
     */
    private function Logical($str){
        $Cluster_Base = new Cluster_Base();
        list($id, $where_index) = $this->parsing();
        //如果说查询条件包括ID
        if($id !== false){
            if(is_array($id)){
                //过滤where_index
                $id = $this->where_indexFilter($where_index, $id);
                //并且是ID列表 先对ID进行分库分表 然后结合where
                return $this->getIdListByInfo($id, $str);
            }else{
                //如果是单条直接结合where查出结果
                $db_table = $Cluster_Base->distr_DB($this->cluster_list, $this->cluster_config, $this->cluster_dblist, $id);
                return $db_table->select($this->select)->where($this->cluster_config['id_name'], $id)->$str();
            }
        }else{
            //如果没有ID条件
            if($where_index){
                $id = $this->where_indexFilter($where_index, $id);
                return $this->getIdListByInfo($id, $str);
            }else{
                return $this->selectAll($str);
            }
        }
    }

    /**
     * 遍历所有的 表获取结果
     */
    private function selectAll($str){
        $data = array();
        foreach($this->cluster_list as $k => $v){
            foreach($this->cluster_dblist[$k] as $key => $db){
                foreach($v['table_list'] as $tableName){
                    $rs = $db->$tableName;
                    if($this->where){
                        foreach($this->where as $wherek => $wherev){
                            $rs = $rs->where($wherek, $wherev);
                        }
                    }
                    $data = array_merge($data, $rs->$str());
                }
            }
        }
        return $data;
    }

    /**
     * 过滤where_index返回ID
     */
    private function where_indexFilter($where_index, $id){
        $this->getConfing();
        //过滤where_index
        if($where_index){
            $db = $this->maindb;
            foreach($where_index as $wherek => $wherev){
                $db->where($wherek, $wherev);
            }
            if(!$id){
                $id = $db->select($this->cluster_config['id_name'])->fetchAll();
            }else{
                $id = $db->select($this->cluster_config['id_name'])->where($this->cluster_config['id_name'], $id)->fetchAll();
            }
            $arr = array();
            foreach($id as $k => $v){
                $arr[] = $v[$this->cluster_config['id_name']];
            }
            return $arr;
        }else{
            return $id;
        }
    }

    /**
     * 解析 where  id  表缓存键
     */
    private function parsing(){
        $this->getConfing();
        $id          = false;
        $where_index = array();
        foreach($this->where as $k => $v){
            if($k == $this->cluster_config['id_name']){
                $id = $v;
                unset($this->where[$k]);
            }
            foreach($this->cluster_config['where'] as $value){
                if($value == $k){
                    $where_index[$value] = $v;
                    unset($this->where[$value]);
                }
            }
        }
        return array($id, $where_index);
    }

    /**
     * 通过ID列表查询结果
     */
    private function getIdListByInfo($id, $str){
        $Cluster_Base = new Cluster_Base();
        $idlist       = $Cluster_Base->distr_IdList($this->cluster_list, $this->cluster_config, $this->cluster_dblist, $id);
        $data         = array();
        foreach($idlist as $k => $v){
            foreach($v as $db => $value){
                foreach($value as $table => $idlists){
                    $tablename = $this->cluster_list[$k]['table_list'][$table];
                    $rs        = $this->cluster_dblist[$k][$db]->$tablename->select($this->select)->where($this->cluster_config['id_name'], $idlists);
                    if($this->where){
                        foreach($this->where as $wherek => $wherev){
                            $rs = $rs->where($wherek, $wherev);
                        }
                    }
                    $rs   = $rs->$str();
                    $data = array_merge($data, $rs);
                }
            }
        }
        return $data;
    }

    /**
     * 通过ID列表修改集群
     */
    private function setIdListByInfo($id, $dataupdate){
        $Cluster_Base = new Cluster_Base();
        $idlist       = $Cluster_Base->distr_IdList($this->cluster_list, $this->cluster_config, $this->cluster_dblist, $id);
        $data         = null;
        foreach($idlist as $k => $v){
            foreach($v as $db => $value){
                foreach($value as $table => $idlists){
                    $tablename = $this->cluster_list[$k]['table_list'][$table];
                    $rs        = $this->cluster_dblist[$k][$db]->$tablename->where($this->cluster_config['id_name'], $idlists);
                    if($this->where){
                        foreach($this->where as $wherek => $wherev){
                            $rs = $rs->where($wherek, $wherev);
                        }
                    }
                    $rs = $rs->update($dataupdate);
                    if($rs === false){
                        throw new PhalApi_Exception_BadRequest('update cluster failure');
                    }
                    $data = $data + $rs;
                }
            }
        }
        return $data;
    }

    /**
     * 通过ID列表删除集群
     */
    private function delIdListByInfo($id){
        $Cluster_Base = new Cluster_Base();
        $idlist       = $Cluster_Base->distr_IdList($this->cluster_list, $this->cluster_config, $this->cluster_dblist, $id);
        $data         = null;
        foreach($idlist as $k => $v){
            foreach($v as $db => $value){
                foreach($value as $table => $idlists){
                    $tablename = $this->cluster_list[$k]['table_list'][$table];
                    $rs        = $this->cluster_dblist[$k][$db]->$tablename->where($this->cluster_config['id_name'], $idlists);
                    if($this->where){
                        foreach($this->where as $wherek => $wherev){
                            $rs = $rs->where($wherek, $wherev);
                        }
                    }
                    $rs = $rs->delete();
                    if($rs === false){
                        throw new PhalApi_Exception_BadRequest('update cluster failure');
                    }
                    $data = $data + $rs;
                }
            }
        }
        return $data;
    }

    /**
     * 清楚where等条件
     */
    public function clear(){
        unset($this->select);
        unset($this->where);
        unset($this->limit);
        unset($this->order);
    }

    /**
     * 获取配置
     */
    private function getConfing(){
        //获得集群实例
        $this->cluster = $this->getCluster();
        //获取的主库实例
        $this->maindb = $this->getMainDB();
        //获取集群列表参数
        $this->cluster_list = $this->cluster->getClusterList();
        //获取集群配置
        $this->cluster_config = $this->cluster->getClusterConfig();
        //获取数据库链接
        $this->cluster_dblist = $this->cluster->getDBList();
    }
}
