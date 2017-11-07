<?php
// +----------------------------------------------------------------------
// | 用户 错误代码段 401xx
// +----------------------------------------------------------------------
// | Author: wk <weika520@qq.com | http://www.wcphp.com> 2017/7/17 9:19
// +----------------------------------------------------------------------
namespace app\common\service;

use app\common\controller\Base;
use app\common\controller\Com;
use app\common\model\User;
use think\Db;
use think\Exception;
use think\session\driver\Memcache;

class UserService extends BaseService
{
    const USERNAME_ERR_CODE = 40101;//登录用户不存在
    const PASSWORD_ERR_CODE = 40102;//登录密码有误
    const ISLOCK_ERR_CODE   = 40103;//已锁定

    const LOGIN_TOKEN_TIMEOUT   = 40104;//登录令牌超时或过期
    const NOT_LOGIN_AUTHORITY   = 40105;//没有登录权限



    /**
     * 用户登录
     * @param string $username
     * @param string $password
     * Author: wk <weika520@qq.com>
     */
    public function login($username,$password='')
    {

        try{
            $userModel = new User();
            $userRow = $userModel->setFieldMap()->where(['Fusername'=>$username])->find();
            if(empty($userRow)){
                throw new Exception('登录用户不存在',self::USERNAME_ERR_CODE);
            }
            if($userRow->password != sha5($password,$userRow->salt)){
                throw new Exception('登录密码有误',self::PASSWORD_ERR_CODE);
            }

            if($userRow->is_lock == 2){
                throw new Exception('用户已锁定,请联系管理员处理',self::ISLOCK_ERR_CODE);
            }
            //登录成功生成并保存令牌到redis
            $accessToken = $this->setLoginToken($userRow->user_id);

            if($accessToken === false){
                throw new Exception('登录校验成功,生成登录令牌失败');
            }
            return ['code'=>true,'msg'=>'登录成功','data'=>['login_token'=>$accessToken,'user_id'=>$userRow->user_id]];

        }catch(Exception $e){
           return ['code'=>$e->getCode(),'msg'=>$e->getMessage()];
        }
    }

    /**
     * 设置登录令牌
     * @param string $userId
     * Author: wk <weika520@qq.com>
     */
    public function setLoginToken($userId=0,$loginToken='')
    {
        //判断是否已有登录 有就下线各子系统登录
        $amcUserInfoKey = config('amc_user_info_prefix').$userId;
        //print_r($loginToken=$redis->get($tokenKey));
        //删除原来的状态
        Com::$redis->rm($amcUserInfoKey);
        Com::$userInfo['user_id'] = $userId;
        $loginToken = empty($loginToken)? random_str(32) : $loginToken;//新生成32位令牌
        Com::$userInfo['token'] = $loginToken;
        if(Com::$redis->set($amcUserInfoKey,Com::$userInfo,config('amc_user_info_time'))){
            cookie('login_token',Com::$userInfo);
            return $loginToken;
        }else{
            return false;
        }
    }

    /**
     * 注销系统
     * @param string $userId
     * Author: wk <weika520@qq.com>
     */
    public function loginOutToken($userId=0)
    {
        $amcUserInfoKey = config('amc_user_info_prefix').$userId;
        //删除redis状态
        Com::$userInfo='';
        Com::$redis->rm($amcUserInfoKey);
        cookie(null,'login_token');
    }



    /**
     * 注册子系统
     * @param string $token 令牌
     * @param string $userId 用户ID
     * @param string $systemId 系统ID
     * Author: wk <weika520@qq.com>
     */
    public function registerSystem($token,$userId,$systemId)
    {
        try{
            $amcUserInfoKey = config('amc_user_info_prefix').$userId;
            Com::$userInfo = Com::$redis->get($amcUserInfoKey);
            if(empty(Com::$userInfo) || $token != Com::$userInfo['token']){
                throw new Exception('该账户已在其它地方登录或者令牌已过期',self::LOGIN_TOKEN_TIMEOUT);
            }
            //校验用户是否有该系统权限
            $systemService = new SystemService();
            $systemIdList = $systemService->getUserSystemIds($userId);
            if(!in_array($systemId,$systemIdList)){
                throw new Exception('用户没有系统登录权限',self::NOT_LOGIN_AUTHORITY);
            }
            //查询用户是否已注册过
            if(!isset(Com::$userInfo['system'])){
                Com::$userInfo['system']=[];
            }
            if(!in_array($systemId,Com::$userInfo['system'])){
                Com::$userInfo['system'][] = $systemId;
            }

            Com::$redis->set($amcUserInfoKey,Com::$userInfo,config('register_system_time'));
            $this->setBossUserKey($userId,$token);
            return ['code'=>true,'msg'=>'注册系统成功','data'=>['login_token'=>$token,'user_id'=>$userId,'system_info'=>$systemService->getSystem($systemId)]];
        }catch(Exception $e){
            return ['code'=>$e->getCode(),'msg'=>$e->getMessage()];
        }
    }

    /**
     * 用户基本信息
     * Author: wk <weika520@qq.com>
     */
    public function getInfo($userId=0)
    {
        $row = [];
        if(!empty($userId)){
            $userModel =  new User();
            $row = $userModel
                ->setFieldMap([
                    'user_id',
                    'username',
                    'phone',
                    'user_desc',
                    'real_name',
                    'photo',
                    'email',
                    'city_id',
                    'is_lock',
                    'lock_time',
                    'reset_password',
                    'login_times',
                    'department_id',
                    'position_id',
                ],false,true)
                ->field(['d.Fname'=>'department_name','p.Fname'=>'position_name'])
                ->where(['u.Fuser_id'=>$userId])
                ->alias('u')
                ->join('__DEPARTMENT__ d','u.Fdepartment_id = d.Fdepartment_id','left')
                ->join('__POSITION__ p','u.Fposition_id = p.Fposition_id','left')
                ->find();
            if(!empty($row)){
                $row = $row->toArray();
            }
        }
        return $row;
    }

    /**
     * 设置boss系统中的登录状态
     * @param mixed
     * Author: wk <weika520@qq.com>
     */
    public function setBossUserKey($userId,$token)
    {
        $memcache = new \think\cache\driver\Memcache(config('memcache_config'));
        // 将$token写入boss的memcache，有效期一天
        return $mem_userkey = $memcache->set('boss_login|'.$userId, $token, 24*3600);
    }

    /**
     * 获取系统拥有的用户信息
     * @param int $systemId 系统ID
     * Author: wk <weika520@qq.com>
     */
    public function getSystemUserList($systemId=[])
    {
        $userList = [];
        if(!empty($systemId)){
            $systemId = is_array($systemId) ? $systemId:[$systemId];
            $position = Db::name('position_role')->where(['Fsystem_id'=>['in',$systemId]])->column('Fposition_id');
            if(empty($position))
                return $userList;
            $position = array_unique($position) ;
            $userList = Db::name('user')->where(['Fposition_id'=>['in',$position]])->field(['Fuser_id'=>'user_id','Fusername'=>'username','Freal_name'=>'real_name'])->select();
        }
        return $userList;
    }
}