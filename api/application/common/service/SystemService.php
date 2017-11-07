<?php
// +----------------------------------------------------------------------
// | 子系统
// +----------------------------------------------------------------------
// | Author: wk <weika520@qq.com | http://www.wcphp.com> 2017/7/19 14:26
// +----------------------------------------------------------------------

namespace app\common\service;

use app\common\controller\Base;
use app\common\controller\Com;
use app\common\model\Position;
use app\common\model\System;
use think\Db;

class SystemService extends BaseService
{
    /**
     * 获取用户所拥有的系统ID
     * @param int $userId 用户ID
     * Author: wk <weika520@qq.com>
     */
    public function getUserSystemIds($userId=0)
    {
        $systemList = [];
        if(!empty($userId)){
            $positionModel = new Position();
            $systemList = $positionModel->alias('p')
                ->join('__USER__ u','u.Fposition_id = p.Fposition_id')
                ->join('__POSITION_ROLE__ pr','p.Fposition_id = pr.Fposition_id')
                ->where(['u.Fuser_id'=>$userId])
                //->fetchSql(true)
                ->column('pr.Fsystem_id');
        }
        return $systemList;
    }

    /**
     * 获取系统信息
     * @param int $systemId 系统ID如果存在就获取指定系统信息，如果不存在就获取所有系统信息以  系统ID=>系统信息 格式数组返回
     * Author: wk <weika520@qq.com>
     */
    public function getSystem($systemId=0)
    {
        $catchKey = 'system_k_v_all';
        if(empty($systemList = Com::$redis->get($catchKey))){
            $systemObj = (new System)->setFieldMap(['system_id', 'name', 'url', 'system_desc'])->select();
            if(!empty($systemObj)){
                foreach($systemObj as $item){
                    $systemList[$item->system_id] = $item->toArray();
                }
            }
            Com::$redis->set($catchKey,$systemList,30);
        }
        if(!empty($systemId)){
           return isset($systemList[$systemId]) ? $systemList[$systemId] : [];
        }
        return $systemList;
    }

    /**
     * 获取用户所拥有的系统
     * @param int $positionId 职位ID
     * @param int $systemId 排除的ID
     * Author: wk <weika520@qq.com>
     */
    public function getUserSystem($positionId=0,$systemId=[])
    {
        $systemList = [];
        if(!empty($positionId)){
            $systemList = Db::name('position_role')
                ->alias('pr')
                ->where(['pr.Fposition_id'=>$positionId,'pr.Fsystem_id'=>['NOT IN',$systemId],'s.Fdelete_time'=>['exp','is null']])
                ->join('__SYSTEM__ s','pr.Fsystem_id = s.Fsystem_id')
                ->field(['s.Fsystem_id'=>'system_id','s.Fname'=>'name','s.Furl'=>'url','s.Fsystem_desc'=>'system_desc'])
                //->fetchSql(true)
                ->select();
        }
        //print_r($systemList);exit;
        return $systemList;
    }




}