<?php

/**
 * Redis 拓展类
 * @author: 喵了个咪 <wenzhenxi@vip.qq.com> 2015-11-15
 */
class Redis_Lite extends PhalApi_Cache_Redis{

    private $db_old;

    //---------------------------------------------------string类型-------------------------------------------------
    /**
     * 将value 的值赋值给key,生存时间为永久 并根据名称自动切换库
     */
    public function set_forever($key, $value, $dbname = 0){
        $this->switchDB($dbname);
        return $this->redis->set($this->formatKey($key), $this->formatValue($value));
    }

    /**
     * 获取value 并根据名称自动切换库
     */
    public function get_forever($key, $dbname = 0){
        $this->switchDb($dbname);
        $value = $this->redis->get($this->formatKey($key));
        return $value !== FALSE ? $this->unformatValue($value) : NULL;
    }

    /**
     * 存入一个有实效性的键值队
     */
    public function set_time($key, $value, $expire = 600, $dbname = 0){
        $this->switchDB($dbname);
        return $this->redis->setex($this->formatKey($key), $expire, $this->formatValue($value));
    }

    /**
     * 统一get/set方法,对于set_Time使用get_Time
     */
    public function get_time($key, $dbname = 0){
        $this->switchDB($dbname);
        $value = $this->redis->get($this->formatKey($key));
        return $value !== FALSE ? $this->unformatValue($value) : NULL;
    }

    /**
     * 得到一个key的生存时间
     */
    public function get_time_ttl($key, $dbname = 0){
        $this->switchDB($dbname);
        $value = $this->redis->ttl($this->formatKey($key));
        return $value !== FALSE ? $this->unformatValue($value) : NULL;
    }

    /**
     * 批量插入k-v,请求的v需要是一个数组 如下格式
     * array('key0' => 'value0', 'key1' => 'value1')
     */
    public function set_list($value, $dbname = 0){
        $this->switchDB($dbname);
        $data = array();
        foreach($value as $k => $v){
            $data[$this->formatKey($k)] = $this->formatValue($v);
        }
        return $this->redis->mset($data);
    }

    /**
     * 批量获取k-v,请求的k需要是一个数组
     */
    public function get_list($key, $dbname = 0){
        $this->switchDB($dbname);
        $data = array();
        foreach($key as $k => $v){
            $data[] = $this->formatKey($v);
        }
        $rs = $this->redis->mget($data);
        foreach($rs as $k => $v){
            $rs[$k] = $this->unformatValue($v);
        }
        return $rs;
    }

    /**
     * 判断key是否存在。存在 true 不在 false
     */
    public function get_exists($key, $dbname = 0){
        $this->switchDb($dbname);
        return $this->redis->exists($this->formatKey($key));
    }

    /**
     * 返回原来key中的值，并将value写入key
     */
    public function get_getSet($key, $value, $dbname = 0){
        $this->switchDb($dbname);
        $value = $this->redis->getSet($this->formatKey($key), $this->formatValue($value));
        return $value !== FALSE ? $this->unformatValue($value) : NULL;
    }

    /**
     * string，名称为key的string的值在后面加上value
     */
    public function set_append($key, $value, $dbname = 0){
        $this->switchDb($dbname);
        return $this->redis->append($this->formatKey($key), $this->formatValue($value));
    }

    /**
     * 返回原来key中的值，并将value写入key
     */
    public function get_strlen($key, $dbname = 0){
        $this->switchDb($dbname);
        return $this->redis->strlen($this->formatKey($key));
    }

    /**
     * 自动增长
     * value为自增长的值默认1
     */
    public function get_incr($key, $value = 1, $dbname = 0){
        $this->switchDb($dbname);
        return $this->redis->incr($this->formatKey($key), $value);
    }

    /**
     * 自动减少
     * value为自减少的值默认1
     */
    public function get_decr($key, $value = 1, $dbname = 0){
        $this->switchDb($dbname);
        return $this->redis->decr($this->formatKey($key), $value);
    }
    //------------------------------------------------List类型-------------------------------------------------

    /**
     * 写入队列左边 并根据名称自动切换库
     */
    public function set_lPush($key, $value, $dbname = 0){
        $this->switchDb($dbname);
        return $this->redis->lPush($this->formatKey($key), $this->formatValue($value));
    }

    /**
     * 写入队列左边 如果value已经存在，则不添加 并根据名称自动切换库
     */
    public function set_lPushx($key, $value, $dbname = 0){
        $this->switchDb($dbname);
        return $this->redis->lPushx($this->formatKey($key), $this->formatValue($value));
    }

    /**
     * 写入队列右边 并根据名称自动切换库
     */
    public function set_rPush($key, $value, $dbname = 0){
        $this->switchDb($dbname);
        return $this->redis->rPush($this->formatKey($key), $this->formatValue($value));
    }

    /**
     * 写入队列右边 如果value已经存在，则不添加 并根据名称自动切换库
     */
    public function set_rPushx($key, $value, $dbname = 0){
        $this->switchDb($dbname);
        return $this->redis->rPushx($this->formatKey($key), $this->formatValue($value));
    }

    /**
     * 读取队列左边
     */
    public function get_lPop($key, $dbname = 0){
        $this->switchDb($dbname);
        $value = $this->redis->lPop($this->formatKey($key));
        return $value != FALSE ? $this->unformatValue($value) : NULL;
    }

    /**
     * 读取队列右边
     */
    public function get_rPop($key, $dbname = 0){
        $this->switchDb($dbname);
        $value = $this->redis->rPop($this->formatKey($key));
        return $value != FALSE ? $this->unformatValue($value) : NULL;
    }

    /**
     * 读取队列左边 如果没有读取到阻塞一定时间 并根据名称自动切换库
     */
    public function get_blPop($key, $dbname = 0){
        $this->switchDb($dbname);
        $value = $this->redis->blPop($this->formatKey($key), DI()->config->get('app.redis.blocking'));
        return $value != FALSE ? $this->unformatValue($value[1]) : NULL;
    }

    /**
     * 读取队列右边 如果没有读取到阻塞一定时间 并根据名称自动切换库
     */
    public function get_brPop($key, $dbname = 0){
        $this->switchDb($dbname);
        $value = $this->redis->brPop($this->formatKey($key), DI()->config->get('app.redis.blocking'));
        return $value != FALSE ? $this->unformatValue($value[1]) : NULL;
    }

    /**
     * 名称为key的list有多少个元素
     */
    public function get_lSize($key, $dbname = 0){
        $this->switchDb($dbname);
        return $this->redis->lSize($this->formatKey($key));
    }

    /**
     * 返回名称为key的list中指定位置的元素
     */
    public function set_lSet($key, $index, $value, $dbname = 0){
        $this->switchDb($dbname);
        return $this->redis->lSet($this->formatKey($key), $index, $this->formatValue($value));
    }

    /**
     * 返回名称为key的list中指定位置的元素
     */
    public function get_lGet($key, $index, $dbname = 0){
        $this->switchDb($dbname);
        $value = $this->redis->lGet($this->formatKey($key), $index);
        return $value != FALSE ? $this->unformatValue($value[1]) : NULL;
    }

    /**
     * 返回名称为key的list中start至end之间的元素（end为 -1 ，返回所有）
     */
    public function get_lRange($key, $start, $end, $dbname = 0){
        $this->switchDb($dbname);
        $rs = $this->redis->lRange($this->formatKey($key), $start, $end);
        foreach($rs as $k => $v){
            $rs[$k] = $this->unformatValue($v);
        }
        return $rs;
    }

    /**
     * 截取名称为key的list，保留start至end之间的元素
     */
    public function get_lTrim($key, $start, $end, $dbname = 0){
        $this->switchDb($dbname);
        $rs = $this->redis->lTrim($this->formatKey($key), $start, $end);
        foreach($rs as $k => $v){
            $rs[$k] = $this->unformatValue($v);
        }
        return $rs;
    }

    //未实现 lRem lInsert  rpoplpush

    //----------------------------------------------------set类型---------------------------------------------------
    //----------------------------------------------------zset类型---------------------------------------------------
    //----------------------------------------------------Hash类型---------------------------------------------------

    //----------------------------------------------------通用方法---------------------------------------------------
    /**
     * 设定一个key的活动时间（s）
     */
    public function setTimeout($key, $time = 600, $dbname = 0){
        $this->switchDB($dbname);
        return $this->redis->setTimeout($key, $time);
    }

    /**
     * 返回key的类型值
     */
    public function type($key, $dbname = 0){
        $this->switchDB($dbname);
        return $this->redis->type($key);
    }

    /**
     * key存活到一个unix时间戳时间
     */
    public function expireAt($key, $time = 600, $dbname = 0){
        $this->switchDB($dbname);
        return $this->redis->expireAt($key, $time);
    }

    /**
     * 随机返回key空间的一个key
     */
    public function randomKey($key, $dbname = 0){
        $this->switchDB($dbname);
        return $this->redis->randomKey();
    }

    /**
     * 返回满足给定pattern的所有key
     */
    public function keys($key, $pattern, $dbname = 0){
        $this->switchDB($dbname);
        return $this->redis->keys($key, $pattern);
    }

    /**
     * 查看现在数据库有多少key
     */
    public function dbSize($dbname = 0){
        $this->switchDB($dbname);
        return $this->redis->dbSize();
    }

    /**
     * 转移一个key到另外一个数据库
     */
    public function move($key, $db, $dbname = 0){
        $this->switchDB($dbname);
        $arr = DI()->config->get('app.redis.DB');
        $rs  = isset($arr[$db]) ? $arr[$db] : $db;
        return $this->redis->move($key, $rs);
    }

    /**
     * 给key重命名
     */
    public function rename($key, $key2, $dbname = 0){
        $this->switchDB($dbname);
        return $this->redis->rename($key, $key2);
    }

    /**
     * 给key重命名 如果重新命名的名字已经存在，不会替换成功
     */
    public function renameNx($key, $key2, $dbname = 0){
        $this->switchDB($dbname);
        return $this->redis->renameNx($key, $key2);
    }

    /**
     * 删除键值 并根据名称自动切换库(对所有通用)
     */
    public function del($key, $dbname = 0){
        $this->switchDB($dbname);
        return $this->redis->del($this->formatKey($key));
    }

    /**
     * 返回redis的版本信息等详情
     */
    public function info(){
        return $this->redis->info();
    }

    /**
     * 切换DB并且获得操作实例
     */
    public function get_redis($dbname = 0){
        $this->switchDb($dbname);
        return $this->redis;
    }

    /**
     * 查看连接状态
     */
    public function ping(){
        return $this->redis->ping();
    }

    /**
     * 内部切换Redis-DB 如果已经在某个DB上则不再切换
     */
    private function switchDB($name){
        $arr = DI()->config->get('app.redis.DB');
        if(is_int($name)){
            $db = $name;
        }else{
            $db = isset($arr[$name]) ? $arr[$name] : $name;
        }
        if($this->db_old != $db){
            $this->redis->select($db);
            $this->db_old = $db;
        }
    }
    //-------------------------------------------------------谨慎使用------------------------------------------------

    /**
     * 清空当前数据库
     */
    public function flushDB($dbname = 0){
        $this->switchDB($dbname);
        return $this->redis->flushDB();
    }

    /**
     * 清空所有数据库
     */
    public function flushAll(){
        return $this->redis->flushAll();
    }

    /**
     * 选择从服务器
     */
    public function slaveof($host, $port){
        return $this->redis->slaveof($host, $port);
    }

    /**
     * 将数据同步保存到磁盘
     */
    public function save(){
        return $this->redis->save();
    }

    /**
     * 将数据异步保存到磁盘
     */
    public function bgsave(){
        return $this->redis->bgsave();
    }

    /**
     * 返回上次成功将数据保存到磁盘的Unix时戳
     */
    public function lastSave(){
        return $this->redis->lastSave();
    }

    /**
     * 使用aof来进行数据库持久化
     */
    public function bgrewriteaof($dbname = 0){
        $this->switchDB($dbname);
        return $this->redis->bgrewriteaof();
    }
}