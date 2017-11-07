<?php
// +----------------------------------------------------------------------
// | 子系统接口控制器
// +----------------------------------------------------------------------
// | Author: wk <weika520@qq.com | http://www.wcphp.com> 2017/7/19 19:03
// +----------------------------------------------------------------------

namespace app\api\controller;

use app\common\controller\AuthBase;
use app\common\service\BossUserService;
use app\common\service\PositionService;
use app\common\service\SystemService;
use app\common\service\UserService;
use app\common\model\User as UserModel;
use think\Db;
use think\Request;

class SubsystemController extends AuthBase
{

    /**
     * 子系统用户
     * Author: wk <weika520@qq.com>
     */
    public function loginInfo()
    {
        $userService = new UserService();
        //用户基本信息
        $userInfo = self::$userInfo['user_info'] = $userService->getInfo(self::$userInfo['user_id']);
        $positionService = new PositionService();
        $menuTree = $positionService->getMenuTree(self::$userInfo['user_id'],self::$systemId);
        $accessFlags =  $positionService->getUserRule(self::$userInfo['user_id'],self::$systemId);
        $showFlags = $positionService->getShowFlags(self::$userInfo['user_id'],self::$systemId);
        apiSuccess('请求成功',['user_info'=>$userInfo,'menu'=>$menuTree,'access_flags'=>$accessFlags,'show_flags'=>$showFlags]);
    }

    /**
     * 用户信息
     * Author: wk <weika520@qq.com>
     */
    public function info()
    {
        $map = [
            'Fuser_id'=>'user_id',
        ];
        $data = $this->getParams($map,'User.info');
        $userService = new UserService();
        //用户基本信息
        $userInfo = $userService->getInfo($data['Fuser_id']);
        apiSuccess('获取用户信息成功',$userInfo);
    }

    /**
     * 获取当前系统用户列表
     * Author: wk <weika520@qq.com>
     */
    public function systemUserList()
    {
        $userData = (new UserService())->getSystemUserList(self::$systemId);
        apiSuccess('获取用户信息成功',$userData);

    }


}