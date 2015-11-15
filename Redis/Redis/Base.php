<?php

/**
 * Redis 拓展类
 * @author: 喵了个咪 <wenzhenxi@vip.qq.com> 2015-11-15
 */
class Redis_Base extends PhalApi_Cache_Redis{

    /**
     * 将value 的值赋值给key,生存时间为永久 并根据名称自动切换库
     */
    public function set_forever($key, $value, $dbname){
        $this->switchDB($dbname);
        return $this->redis->set($this->formatKey($key), $this->formatValue($value));
    }

    /**
     * 存入一个有实效性的键值队
     */
    public function set_Time($key, $value, $dbname, $expire = 600){
        $this->switchDB($dbname);
        return $this->redis->setex($this->formatKey($key), $expire, $this->formatValue($value));
    }

    /**
     * 统一get/set方法,对于set_Time使用get_Time
     */
    public function get_Time($key, $dbname){
        $this->switchDB($dbname);
        $value = $this->redis->get($this->formatKey($key));
        return $value !== FALSE ? $this->unformatValue($value) : NULL;
    }

    /**
     * 删除键值 并根据名称自动切换库(对所有通用)
     */
    public function del($key, $dbname){
        $this->switchDB($dbname);
        return $this->redis->del($this->formatKey($key));
    }

    /**
     * 获取value 并根据名称自动切换库
     */
    public function get_forever($key, $dbname){
        $this->switchDb($dbname);
        $value = $this->redis->get($this->formatKey($key));
        return $value !== FALSE ? $this->unformatValue($value) : NULL;
    }

    /**
     * 写入队列左边 并根据名称自动切换库
     */
    public function set_Lpush($key, $value, $dbname){
        $this->switchDb($dbname);
        return $this->redis->lPush($this->formatKey($key), $this->formatValue($value));
    }

    /**
     * 读取队列右边 如果没有读取到阻塞一定时间 并根据名称自动切换库
     */
    public function get_Brpop($key, $dbname){
        $this->switchDb($dbname);
        $value = $this->redis->blPop($this->formatKey($key), DI()->config->get('rds.blocking'));
        return $value != FALSE ? $this->unformatValue($value[1]) : NULL;
    }

    /**
     * 读取队列右边
     */
    public function get_lpop($key, $dbname){
        $this->switchDb($dbname);
        $value = $this->redis->lPop($this->formatKey($key));
        return $value != FALSE ? $this->unformatValue($value) : NULL;
    }

    /**
     * 内部切换Redis-DB
     */
    private function switchDB($name){
        $arr = DI()->config->get('rds.DB');
        $db  = isset($arr[$name]) ? $arr[$name] : $name;
        $this->redis->select($db);
    }

    /**
     * 自动增长
     */
    public function get_incr($key, $dbname){
        $this->switchDb($dbname);
        return $this->redis->incr($this->formatKey($key));
    }

    /**
     * 切换DB并且获得操作实例
     */
    public function get_redis($dbname){
        $this->switchDb($dbname);
        return $this->redis;
    }
}