<?php
// +----------------------------------------------------------------------
// | boss系统中的用户
// +----------------------------------------------------------------------
// | Author: wk <weika520@qq.com | http://www.wcphp.com> 2017/7/31 14:50
// +----------------------------------------------------------------------

namespace app\common\service;


use app\common\model\User as UserModel;
use think\Db;
use think\Exception;

class BossUserService extends BaseService
{
    /**
     * 同步boss用户到本系统 用户  user_id username
     * Author: wk <weika520@qq.com>
     */
    public function syncBoss()
    {
        try{
            $bossIds = Db::connect(config('boss_db_read'))->table('t_boss_user')->column('Fuser_id');
            $userIds = Db::name('user')->column('Fuser_id');
            $userIdDiff = array_diff($bossIds,$userIds);
            $userModel = new UserModel();
            $updateNum = 0;
            $userList = Db::connect(config('boss_db_read'))
                ->table('t_boss_user')
                ->where(['Fuser_id'=>['in',$userIdDiff]])
                ->field([
                    'Fuser_id',
                    'Fuser_name'=>'Fusername',
                    'Fphone_num'=>'Fphone',
                    'Fuser_desc',
                    'Femail',
                    'Fcity_id',
                    'Foffice_clerk_name'=>'Freal_name',
                ])->chunk(500,function($data )use(&$userModel,&$updateNum){
                    if(empty(!$data)){

                        foreach($data as $key=>$item) {
                            $item['Fsalt'] = random_str();
                            $item['Fpassword'] = sha5(config('user_new_password'),$item['Fsalt']);
                            $data[$key] = $item;
                        }
                        $updateNum += $userModel->insertAll($data);
                    }
                });
            return ['code'=>true,'msg'=>'同步Boss用户数据成功','data'=>$updateNum];
        }catch(Exception $e){
            return ['code'=>$e->getCode(),$e->getMessage()];
        }
    }

}