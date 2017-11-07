<?php
// +----------------------------------------------------------------------
// | 子系统访问
// +----------------------------------------------------------------------
// | Author: wk <weika520@qq.com | http://www.wcphp.com> 2017/9/5 14:39
// +----------------------------------------------------------------------

namespace app\api\controller;

use app\common\controller\Com;
use app\common\service\UserService;
use think\Controller;

class PublicController extends Com
{
    /**
     * 子系统登录业务
     * Author: wk <weika520@qq.com>
     */
    public function login()
    {
        //post登录
        if($this->request->isPost()){
            $data = [];
            $data['jump_url'] = input('jumpUrl');
            empty(input('systemId')) || $data['system_id'] = input('systemId');
            empty(input('username')) || $data['username'] = input('username');
            empty(input('password')) || $data['password'] = input('password');
            if(true !== ($result = $this->validate($data,'User.subLogin'))){
                apiFail(10001,$result);
            }
            $userService = new UserService();
            $result = $userService->login($data['username'],$data['password']);
            //登录失败
            if($result['code'] !== true){
                apiFail($result['code'],$result['msg']);
            }
            //注册登录系统
            $registerResult = $userService->registerSystem($result['data']['login_token'],$result['data']['user_id'],$data['system_id']);
            //注册登录失败
            if($registerResult['code'] !== true){
                apiFail($registerResult['code'],$registerResult['msg']);
            }else{
                $jumpUrl = empty($data['jump_url']) ? $registerResult['data']['system_info']['url'] : $data['jump_url'];
                $registerResult['data']['jump_url'] = get_url_param($jumpUrl,['login_token'=>$registerResult['data']['login_token'],'user_id'=>$registerResult['data']['user_id']]);
                apiSuccess('登录系统成功',$registerResult['data']);
            }
        }else{
            //登录页加载
            $data = [];
            $data['jump_url'] = input('jump_url');
            empty(input('system_id')) || $data['system_id'] = input('system_id');

            if(true !== ($result = $this->validate($data,'User.loginLoad'))){
                apiFail(10001,$result);
            }
            //判断是否已登录 如果已登录直接跳转到指定的url
            $loginToken = cookie('login_token');
            if(isset($loginToken['user_id']) && isset($loginToken['token'])){
              $userService = new UserService();
                 /* $userService->setLoginToken($loginToken['user_id'],$loginToken['token']);*/
                //注册系统
                $registerResult = $userService->registerSystem($loginToken['token'],$loginToken['user_id'],$data['system_id']);
                //注册登录系统成功 直接重定向到指定系统 带token和user_id
                if($registerResult['code'] === true){
                    $jumpUrl = empty($data['jump_url']) ? $registerResult['data']['system_info']['url'] : $data['jump_url'];
                    $this->redirect(get_url_param($jumpUrl,['login_token'=>$loginToken['token'],'user_id'=>$loginToken['user_id']]));
                }
                //自动登录失败 销毁登录状态
                $userService->loginOutToken($loginToken['user_id']);
            }
            return $this->fetch('public/login',$data);
        }
    }



}