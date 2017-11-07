<?php
// +----------------------------------------------------------------------
// | 职位
// +----------------------------------------------------------------------
// | Author: wk <weika520@qq.com | http://www.wcphp.com> 2017/7/21 9:32
// +----------------------------------------------------------------------

namespace app\common\service;


use app\common\controller\Base;
use app\common\model\Authority;
use think\Db;

class PositionService extends BaseService
{
    /**
     * 获取用户指定系统菜单树
     * Author: wk <weika520@qq.com>
     */
    public function getMenuTree($userId,$systemId)
    {
        $list = $this->getAuthority($userId,$systemId,2);
        function menuTree($list,$pid=0){
            $menu =[];
            foreach($list as $item){
                if($item['pid'] == $pid){
                    $item['sub_menu'] = menuTree($list,$item['authority_id']);
                    $menu[] = $item;
                }
            }
            return $menu;
        }
       return menuTree($list);
    }

    /**
     * 获取用户指定系统权限规则列表
     * Author: wk <weika520@qq.com>
     */
    public function getUserRule($userId,$systemId)
    {
        if(empty(Base::$userInfo['rule'][$systemId])){
            $list = $this->getAuthority($userId,$systemId);
            $ruleList = [];
            if(!empty($list)){
                foreach($list as $item){
                    if(!in_array($item['access_flags'],$ruleList) && !empty($item['access_flags'])){
                        $ruleList[] =  mb_strtolower($item['access_flags']);
                    }
                }
            }
            Base::$userInfo['rule'][$systemId] = array_unique($ruleList);
        }
        return Base::$userInfo['rule'][$systemId];
    }

    /**
     * 获取用户指定系统显示的标识列表
     * Author: wk <weika520@qq.com>
     */
    public function getShowFlags($userId,$systemId)
    {
        if(empty(Base::$userInfo['is_show'][$systemId])){
            $list = $this->getAuthority($userId,$systemId);
            $showList = [];
            if(!empty($list)){
                foreach($list as $item){
                    if(!in_array($item['show_flags'],$showList) && !empty($item['show_flags'])){
                        $showList[] =  mb_strtolower($item['show_flags']);
                    }
                }
            }
            Base::$userInfo['is_show'][$systemId] = array_unique($showList);
        }
        return Base::$userInfo['is_show'][$systemId];

    }

    /**
     * 获取用户指定系统中的角色
     * Author: wk <weika520@qq.com>
     */
    protected function getUserRoleId($userId,$systemId)
    {
        if(empty(Base::$userInfo['system_role'][$systemId])){
            $userInfo = (new UserService())->getInfo($userId);
            if(empty($userInfo)){
                return false;
            }
            $roleId = Db::name('position_role')->where(['Fposition_id'=>$userInfo['position_id'],'Fsystem_id'=>$systemId])->value('Frole_id');
            if(empty($roleId)){
               return false;
            }
            Base::$userInfo['system_role'][$systemId] = $roleId;
        }
        return  Base::$userInfo['system_role'][$systemId];
    }

    /**
     * 获取列表
     * Author: wk <weika520@qq.com>
     */
    public function getAuthority($userId,$systemId,$type=0)
    {
        $roleId = $this->getUserRoleId($userId,$systemId);
        $where =[];
        if($type == 2){//菜单
            $where['a.Ftype'] = $type;
        }
        $list = [];
        if(!empty($roleId)){
            $authorityList = (new Authority())
                ->setFieldMap(['authority_id', 'name', 'type', 'pid', 'style', 'access_flags', 'show_flags'],false,true)
                ->alias('a')
                ->where($where)
                ->where(['ra.Frole_id'=>$roleId,'a.Fstatus'=>1,])
                ->join('__ROLE_AUTHORITY__ ra','a.Fauthority_id = ra.Fauthority_id')
                //->fetchSql(true)
                ->select();
            //echo $authorityList;exit;
            if(!empty($authorityList)){
                foreach($authorityList as $item){
                    $list[] = $item->toArray();
                }
            }
        }
        return $list;

    }






}