<?php
// +----------------------------------------------------------------------
// | 公共基类
// +----------------------------------------------------------------------
// | Author: wk <weika520@qq.com | http://www.wcphp.com> 2017/7/11 15:00
// +----------------------------------------------------------------------

namespace app\common\controller;

use app\common\lib\Redis;
use app\common\service\OperatorService;
use think\Controller;
use think\Exception;
use think\Log;
use think\Request;

class Com extends Controller
{
    public  static $redis = '';//redis静态
    public  static $userInfo = [];//缓存在redis中用户数据
    public  static $systemId = '';//当前请求系统ID

    public function __construct(Request $request)
    {
        self::$redis = new Redis();
        parent::__construct($request);
    }

}