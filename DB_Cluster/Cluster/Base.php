<?php

/**
 * 集群基础类
 * @author: 喵了_个咪 <wenzhenxi@vip.qq.com> 2015-10-21
 */
class Cluster_Base{

    /**
     * 打印数组或对象
     */
    public function showarr($a){
        echo('<pre>');
        print_r($a);
        echo('</pre>');
    }

    /**
     * 数组对象取值相关 - 避免出错
     */
    public function getIndex($arr, $key, $default = false){
        return isset($arr[$key]) ? $arr[$key] : $default;
    }

    /**
     * 插入时筛选主表生产需要插入的列表
     */
    public function mian_Insert_List($cluster_config, $data){
        $rs = array();
        foreach($cluster_config['where'] as $k => $v){
            if($this->getIndex($data, $v) === false){
                throw new PhalApi_Exception_BadRequest('The lack of the ' . $v);
            }
            $rs[$v] = $data[$v];
        }
        return $rs;
    }

    /**
     * 通过ID分配数据库已经表的算法
     */
    public function distr_DB($cluster_list, $cluster_config, $cluster_dblist, $id){
        foreach($cluster_list as $k => $v){
            if(($cluster_config['list'][$k]['id_min'] <= $id && $cluster_config['list'][$k]['id_max'] >= $id) || $cluster_config['list'][$k]['id_max'] == 0){
                list($db, $table) = $this->arithmetic($cluster_list[$k], $id);
                return $cluster_dblist[$k][$db]->$v['table_list'][$table];
            }
        }
    }

    /**
     * 通过ID列表通过算法返回分库分表
     */
    public function distr_IdList($cluster_list, $cluster_config, $cluster_dblist, $idlist){
        $idlist_old = array();
        $data       = array();
        foreach($idlist as $id){
            foreach($cluster_list as $k => $v){
                if(($cluster_config['list'][$k]['id_min'] <= $id && $cluster_config['list'][$k]['id_max'] >= $id) || ($cluster_config['list'][$k]['id_max'] == 0)){
                    list($db, $table) = $this->arithmetic($cluster_list[$k], $id);
                    if(!in_array($id,$idlist_old)){
                        $data[$k][$db][$table][] = $id;
                        $idlist_old[] = $id;
                    }
                    //return $cluster_dblist[$k][$db]->$v['table_list'][$table];
                }
            }
        }
        return $data;
    }

    /**
     * 分库分表算法
     */
    private function arithmetic($cluster_list, $id){
        $db_count    = count($cluster_list['db_list']);
        $table_count = count($cluster_list['table_list']);
        //取余获得0~15的id 当4库*4表 时
        $id = $id % ($db_count * $table_count);
        //除获得0~3的db
        $db = $id / $table_count;
        //取余获得0~3的table
        $table = $id % $table_count;
        return array((int) $db, $table);
    }
}
