<?php
// +----------------------------------------------------------------------
// | 
// +----------------------------------------------------------------------
// | Author: wk <weika520@qq.com | http://www.wcphp.com> 2017/6/12 11:43
// +----------------------------------------------------------------------

namespace app\common\lib;

use think\cache\driver\Redis  as RedisF;

class Redis extends RedisF
{
    /**
     * 构造函数如果不加配置直接走系统配置
     * @param mixed $config 配置
     * Author: wk <weika520@qq.com>
     */
    public function __construct($config=[])
    {
        $config = array_merge(config('redis_config'),$config);
        parent::__construct($config);
    }
    /**
     * 在列表头部插入数据
     * @param string    $name 变量名
     * @param mixed     $value  存储数据
     * @return boolean
     */
    public function lPush($name, $value)
    {
        $value = (is_object($value) || is_array($value)) ? json_encode($value) : $value;
        $result = $this->handler->lPush($name,$value);
        return $result;
    }

    /**
     * 列表头部弹出
     * @param string    $name 变量名
     * @param mixed     $value  存储数据
     * @return boolean
     */
    public function lPop($name)
    {
        return $this->handler->lPop($name);
    }



}