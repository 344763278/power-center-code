<?php
// +----------------------------------------------------------------------
// | 已授权接口访问基类
// +----------------------------------------------------------------------
// | Author: wk <weika520@qq.com | http://www.wcphp.com> 2017/7/11 15:00
// +----------------------------------------------------------------------

namespace app\common\controller;

use think\Controller;
use think\Exception;
use think\Request;

class AuthBase extends Base
{
    /**
     * Author: wk <weika520@qq.com>
     */
    public function __construct(Request $request)
    {
        parent::__construct($request);
        $this->checkLogin();
    }

    /**
     * 登录校验  校验缓存中的 token userId  是否等于传递的 传递的系统ID是否在
     * Author: wk <weika520@qq.com>
     */
    protected function checkLogin()
    {
        //user_id  token system_id 必传参数
        $map = [
            'token/s'=>'login_token',
            'user_id/d'=>'login_user_id',
            'system_id/d'=>'login_system_id',
        ];
        $data = $this->getParams($map,'User.registerSystem');
        //获取缓存中的登录状态
        self::$userInfo = self::$redis->get(config('amc_user_info_prefix').$data['user_id']);

        //print_r(self::$userInfo);
        self::$systemId = $data['system_id'];
        //校验是否登录
        if(empty(self::$userInfo['token']) || self::$userInfo['token']!= $data['token'] ){
            abort(10005,'令牌校验有误,请重新登录后再来访问');
        }
        //校验是否注册系统
        if(empty(self::$userInfo['system']) || !in_array($data['system_id'],self::$userInfo['system'])){
            abort(10006,'请注册系统后再访问');
        }
    }


}