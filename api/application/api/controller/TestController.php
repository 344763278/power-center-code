<?php
// +----------------------------------------------------------------------
// | 
// +----------------------------------------------------------------------
// | Author: wk <weika520@qq.com | http://www.wcphp.com> 2017/7/11 9:31
// +----------------------------------------------------------------------

namespace app\api\controller;



use app\common\controller\AuthBase;
use app\common\service\BossUserService;

class TestController
{
    /**
     * Author: wk <weika520@qq.com>
     */
    public function index()
    {
	
        $bossUser = new BossUserService();
        $bossUser->syncBoss();


    }

}