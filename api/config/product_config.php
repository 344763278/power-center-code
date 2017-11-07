<?php
// +----------------------------------------------------------------------
// | 线网配置文件
// +----------------------------------------------------------------------
// | Author: wk <weika520@qq.com | http://www.wcphp.com> 2017/6/1 16:16
// +----------------------------------------------------------------------
return [
    //线网数据库
    'database'=>[
        // 数据库类型
        'type'            => 'mysql',
        // 服务器地址
        'hostname'        => '10.66.111.254',
        // 数据库名
        'database'        => 'amcdb',
        // 用户名
        'username'        => 'amc',
        // 密码
        'password'        => 'mKR0g3z1vKNO',
        // 端口
        'hostport'        => '3306',
        // 连接dsn
        'dsn'             => '',
        // 数据库连接参数
        'params'          => [],
        // 数据库编码默认采用utf8
        'charset'         => 'utf8',
        // 数据库表前缀
        'prefix'          => 't_',
        // 数据库调试模式
        'debug'           => true,
        // 数据库部署方式:0 集中式(单一服务器),1 分布式(主从服务器)
        'deploy'          => 0,
        // 数据库读写是否分离 主从式有效
        'rw_separate'     => false,
        // 读写分离后 主服务器数量
        'master_num'      => 1,
        // 指定从服务器序号
        'slave_no'        => '',
        // 是否严格检查字段是否存在
        'fields_strict'   => true,
        // 数据集返回类型
        'resultset_type'  => 'array',
        // 自动写入时间戳字段
        'auto_timestamp'  => false,
        // 时间字段取出后的默认时间格式
        'datetime_format' => 'Y-m-d H:i:s',
        // 是否需要进行SQL性能分析
        'sql_explain'     => false,
    ],
    //boss后台数据库配置
    'boss_db_write' => 'mysql://hsb:hsb@cgi@10.66.111.254:3306/recycle#utf8',
    'boss_db_read' => 'mysql://hsb:hsb@cgi@10.66.111.254:3306/recycle#utf8',

    //新增用户默认密码
    'user_new_password'=>'huishoubao',

    //权限管理中心秘钥
    'amc_key'=>'123456789',
    //用户信息保存在redis中的前缀
    'amc_user_info_prefix'=>'amc_user_info_',
    //登录令牌有效时间（秒）
    'amc_user_info_time'=>3600,
    //Redis配置
    'redis_config'=>[
        'host'=>'10.66.230.61',
        'port'=>'6379',
        'password'=>'crs-g0dg7okv:vZ3RScfBUI',
        'expire'=>'86400',
    ],

    //memcache 配置
    'memcache_config'=>[
        'host'=>'localhost',
        'port'=>'12000',//
        'expire'=>3600
    ],

];

