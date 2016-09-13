<?php

/**
 * Redis 拓展类
 * @author: 喵了个咪 <wenzhenxi@vip.qq.com> 2015-11-15
 *
 * @author: Axios <axioscros@aliyun.com> 2016-09-01
 *
 * @update 2016-09-01
 * 1. 增加__call魔术方法，重载当前类中方法，统一切换DB，缩减代码行数，减少类中方法互相调用时，重复切换DB
 * 2. 增加save_time方法,更新具有有效时间key的value，并且不重置有效时间,使用方式: DI()->redis->save_time($key, $value,$dbname);
 * 3. 增加counter_forever方法，永久计数器，每次调用递增1，使用方式: DI()->redis->counter_forever($key,$dbname);
 * 4. 增加counter_time_create方法，创建临时计数器，每次调用重置计数器和有效时间，使用方式: DI()->redis->counter_time_create($key,$expire,$dbname);
 * 5. 增加counter_time_update方法，更新临时计数器，每次调用递增1，使用方式: DI()->redis->counter_time_update($key,$dbname);
 * 6. 修复一处bug，get_time_ttl方法中，$this->unformatValue($value)修改为$value。修改前，调用此方法，会一直回调false
 *
 */
class Redis_Lite extends PhalApi_Cache_Redis{

    private $db_old;

    /**
     * 重载方法，统一切换DB
     *
     * @param $name
     * @param $arguments
     * @return mixed
     *
     * @author: Axios <axioscros@aliyun.com> 2016-09-01
     */
    public function __call($name, $arguments)
    {
        $last = count($arguments)-1;
        $dbname = $arguments[$last];
        $this->switchDB($dbname);
        unset($arguments[$last]);
        $arguments = empty($arguments)? array():$arguments;
        return call_user_func_array(array($this,$name),$arguments);
    }

    //---------------------------------------------------string类型-------------------------------------------------
    /**
     * 将value 的值赋值给key,生存时间为永久 并根据名称自动切换库
     */
    protected function set_forever($key, $value){
        return $this->redis->set($this->formatKey($key), $this->formatValue($value));
    }

    /**
     * 获取value 并根据名称自动切换库
     */
    protected function get_forever($key){
        $value = $this->redis->get($this->formatKey($key));
        return $value !== FALSE ? $this->unformatValue($value) : NULL;
    }

    /**
     * 存入一个有实效性的键值队
     */
    protected function set_time($key, $value, $expire = 600){
        return $this->redis->setex($this->formatKey($key), $expire, $this->formatValue($value));
    }


    /**
     * 更新具有有效时间key的value，不重置有效时间
     * @author Axios <axioscros@aliyun.com>
     */
    protected function save_time($key, $value)
    {
        if($this->get_exists($key)){
            $ttl  = $this->get_time_ttl($key);
            return $this->set_time($key,$value,$ttl);
        }

        return NULL;
    }

    /**
     * 统一get/set方法,对于set_Time使用get_Time
     */
    protected function get_time($key){
        $value = $this->redis->get($this->formatKey($key));
        return $value !== FALSE ? $this->unformatValue($value) : NULL;
    }

    /**
     * 得到一个key的生存时间
     */
    protected function get_time_ttl($key){
        $value = $this->redis->ttl($this->formatKey($key));
        return $value !== FALSE ? $value : NULL;
    }

    /**
     * 批量插入k-v,请求的v需要是一个数组 如下格式
     * array('key0' => 'value0', 'key1' => 'value1')
     */
    protected function set_list($value){
        $data = array();
        foreach($value as $k => $v){
            $data[$this->formatKey($k)] = $this->formatValue($v);
        }
        return $this->redis->mset($data);
    }

    /**
     * 批量获取k-v,请求的k需要是一个数组
     */
    protected function get_list($key){
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
    protected function get_exists($key){
        return $this->redis->exists($this->formatKey($key));
    }

    /**
     * 返回原来key中的值，并将value写入key
     */
    protected function get_getSet($key, $value){
        $value = $this->redis->getSet($this->formatKey($key), $this->formatValue($value));
        return $value !== FALSE ? $this->unformatValue($value) : NULL;
    }

    /**
     * string，名称为key的string的值在后面加上value
     */
    protected function set_append($key, $value){
        return $this->redis->append($this->formatKey($key), $this->formatValue($value));
    }

    /**
     * 返回原来key中的值，并将value写入key
     */
    protected function get_strlen($key){
        return $this->redis->strlen($this->formatKey($key));
    }

    /**
     * 自动增长
     * value为自增长的值默认1
     */
    protected function get_incr($key, $value = 1){
        return $this->redis->incr($this->formatKey($key), $value);
    }

    /**
     * 自动减少
     * value为自减少的值默认1
     */
    protected function get_decr($key, $value = 1){
        return $this->redis->decr($this->formatKey($key), $value);
    }
    //------------------------------------------------List类型-------------------------------------------------

    /**
     * 写入队列左边 并根据名称自动切换库
     */
    protected function set_lPush($key, $value){
        return $this->redis->lPush($this->formatKey($key), $this->formatValue($value));
    }

    /**
     * 写入队列左边 如果value已经存在，则不添加 并根据名称自动切换库
     */
    protected function set_lPushx($key, $value){
        return $this->redis->lPushx($this->formatKey($key), $this->formatValue($value));
    }

    /**
     * 写入队列右边 并根据名称自动切换库
     */
    protected function set_rPush($key, $value){
        return $this->redis->rPush($this->formatKey($key), $this->formatValue($value));
    }

    /**
     * 写入队列右边 如果value已经存在，则不添加 并根据名称自动切换库
     */
    protected function set_rPushx($key, $value){
        return $this->redis->rPushx($this->formatKey($key), $this->formatValue($value));
    }

    /**
     * 读取队列左边
     */
    protected function get_lPop($key){
        $value = $this->redis->lPop($this->formatKey($key));
        return $value != FALSE ? $this->unformatValue($value) : NULL;
    }

    /**
     * 读取队列右边
     */
    protected function get_rPop($key){
        $value = $this->redis->rPop($this->formatKey($key));
        return $value != FALSE ? $this->unformatValue($value) : NULL;
    }

    /**
     * 读取队列左边 如果没有读取到阻塞一定时间 并根据名称自动切换库
     */
    protected function get_blPop($key){
        $value = $this->redis->blPop($this->formatKey($key), DI()->config->get('app.redis.blocking'));
        return $value != FALSE ? $this->unformatValue($value[1]) : NULL;
    }

    /**
     * 读取队列右边 如果没有读取到阻塞一定时间 并根据名称自动切换库
     */
    protected function get_brPop($key){
        $value = $this->redis->brPop($this->formatKey($key), DI()->config->get('app.redis.blocking'));
        return $value != FALSE ? $this->unformatValue($value[1]) : NULL;
    }

    /**
     * 名称为key的list有多少个元素
     */
    protected function get_lSize($key){
        return $this->redis->lSize($this->formatKey($key));
    }

    /**
     * 返回名称为key的list中指定位置的元素
     */
    protected function set_lSet($key, $index, $value){
        return $this->redis->lSet($this->formatKey($key), $index, $this->formatValue($value));
    }

    /**
     * 返回名称为key的list中指定位置的元素
     */
    protected function get_lGet($key, $index){
        $value = $this->redis->lGet($this->formatKey($key), $index);
        return $value != FALSE ? $this->unformatValue($value[1]) : NULL;
    }

    /**
     * 返回名称为key的list中start至end之间的元素（end为 -1 ，返回所有）
     */
    protected function get_lRange($key, $start, $end){
        $rs = $this->redis->lRange($this->formatKey($key), $start, $end);
        foreach($rs as $k => $v){
            $rs[$k] = $this->unformatValue($v);
        }
        return $rs;
    }

    /**
     * 截取名称为key的list，保留start至end之间的元素
     */
    protected function get_lTrim($key, $start, $end){
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
     * 永久计数器,回调当前计数
     * @author Axios <axioscros@aliyun.com>
     */
    public function counter_forever($key,$dbname=0){
        $this->switchDB($dbname);
        if($this->get_exists($key)){
            $count = $this->get_forever($key);
            $count++;
            $this->set_forever($key,$count);
        }else{
            $count = 1;
            $this->set_forever($key,$count);
        }

        return $count;
    }
    /**
     * 创建具有有效时间的计数器,回调当前计数,单位毫秒ms
     * @author Axios <axioscros@aliyun.com>
     */
    public function counter_time_create($key,$expire  = 1000,$dbname=0){
        $this->switchDB($dbname);
        $count = 1;
        $this->set_time($key,$count,$expire);
        $this->redis->pSetEx($this->formatKey($key), $expire, $this->formatValue($count));
        return $count;
    }
    /**
     * 更新具有有效时间的计数器,回调当前计数
     * @author Axios <axioscros@aliyun.com>
     */
    public function counter_time_update($key,$dbname=0){
        $this->switchDB($dbname);
        if($this->get_exists($key)){
            $count = $this->get_time($key);
            $count++;
            $expire = $this->redis->pttl($this->formatKey($key));
            $this->set_time($key,$count,$expire);
            return $count;
        }
        return false;
    }
    /**
     * 设定一个key的活动时间（s）
     */
    protected function setTimeout($key, $time = 600){
        return $this->redis->setTimeout($key, $time);
    }

    /**
     * 返回key的类型值
     */
    protected function type($key){
        return $this->redis->type($key);
    }

    /**
     * key存活到一个unix时间戳时间
     */
    protected function expireAt($key, $time = 600){
        return $this->redis->expireAt($key, $time);
    }

    /**
     * 随机返回key空间的一个key
     */
    public function randomKey(){
        return $this->redis->randomKey();
    }

    /**
     * 返回满足给定pattern的所有key
     */
    protected function keys($key, $pattern){
        return $this->redis->keys($key, $pattern);
    }

    /**
     * 查看现在数据库有多少key
     */
    protected function dbSize(){
        return $this->redis->dbSize();
    }

    /**
     * 转移一个key到另外一个数据库
     */
    protected function move($key, $db){
        $arr = DI()->config->get('app.redis.DB');
        $rs  = isset($arr[$db]) ? $arr[$db] : $db;
        return $this->redis->move($key, $rs);
    }

    /**
     * 给key重命名
     */
    protected function rename($key, $key2){

        return $this->redis->rename($key, $key2);
    }

    /**
     * 给key重命名 如果重新命名的名字已经存在，不会替换成功
     */
    protected function renameNx($key, $key2){
        return $this->redis->renameNx($key, $key2);
    }

    /**
     * 删除键值 并根据名称自动切换库(对所有通用)
     */
    protected function del($key){
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
    public function get_redis(){
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
    protected function switchDB($name){
        $arr = DI()->config->get('app.redis.DB');
        if(is_int($name)){
            $db = $name;
        }else{
            $db = isset($arr[$name]) ? $arr[$name] : 0;
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
    protected function flushDB(){
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
    protected function bgrewriteaof(){
        return $this->redis->bgrewriteaof();
    }
}