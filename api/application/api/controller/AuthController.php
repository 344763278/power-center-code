<?php
// +----------------------------------------------------------------------
// | 登录认证类
// +----------------------------------------------------------------------
// | Author: wk <weika520@qq.com | http://www.wcphp.com> 2017/7/16 22:47
// +----------------------------------------------------------------------

namespace app\api\controller;

use app\common\controller\Base;
use app\common\service\UserService;

class AuthController extends Base
{
    /**
     * 登录
     * Author: wk <weika520@qq.com>
     */
    public function login()
    {
        //字段获取并映射
        $map = [
            'username/s'=>'username',
            'password/s'=>'password',
            'system_id/s'=>'system_id',
        ];

        $data = $this->getParams($map,'User.login');
        //print_r($data);exit;
        $userService = new UserService();
        $result = $userService->login($data['username'],$data['password']);
        //登录失败
        if($result['code'] !== true){
            $this->operatorRecord('登录失败：'.$result['msg'],'',$data['username'],$data['system_id']);

            apiFail($result['code'],$result['msg']);
        }else{
            $this->operatorRecord('登录成功',$result['data']['user_id'],$data['username'],$data['system_id']);
           apiSuccess('登录成功',$result['data']);
        }
    }

    /**
     * 注册系统
     * 1、校验签名是否正确
     * 2、判断是否有该子系统权限
     * 3、是否已注册
     * 4、注册对应子系统
     * Author: wk <weika520@qq.com>
     */
    public function registerSystem()
    {
        //字段获取并映射
        $map = [
            'token/s'=>'token',
            'user_id/d'=>'user_id',
            'system_id/d'=>'system_id',
        ];

        $data = $this->getParams($map,'User.registerSystem');
        $userService = new UserService();

        $result = $userService->registerSystem($data['token'],$data['user_id'],$data['system_id']);
        //登录失败
        if($result['code'] !== true){
            $this->operatorRecord('注册系统失败：'.$result['msg'],$data['user_id'],'',$data['system_id']);
            apiFail($result['code'],$result['msg']);
        }else{
            $this->operatorRecord('注册系统成功',$data['user_id'],'',$data['system_id']);
            apiSuccess('注册系统成功',$result['data']);
        }
    }



    /**
     * 系统登出
     * @param mixed
     * Author: wk <weika520@qq.com>
     */
    public function logout()
    {
        $map = [
            'user_id/d'=>'login_user_id',
        ];
        $data = $this->getParams($map,'User.logout');
        $userService = new UserService();
        // 销毁登录状态
        $userService->loginOutToken($data['user_id']);
        apiSuccess('退出系统成功');
    }






}