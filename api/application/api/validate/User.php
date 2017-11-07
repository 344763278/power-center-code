<?php
// +----------------------------------------------------------------------
// | 用户校验类
// +----------------------------------------------------------------------
// | Author: wk <weika520@qq.com | http://www.wcphp.com> 2017/7/16 22:58
// +----------------------------------------------------------------------

namespace app\api\validate;
use think\Validate;

class User extends Validate
{
    protected $rule=[
        ['Fuser_id','require','用户ID不能为空'],
        ['username','require','用户名不能为空'],
        ['password','require|length:6,18','登录密码不能为空|登录密码必须为6到18位之间'],
        ['token','require','注册令牌不能为空'],
        ['user_id','require','用户名唯一识别码不能为空'],
        ['system_id','require','系统唯一识别码不能为空'],
        ['ids','require','用户ID不能为空'],


        ['Fusername','require','账户不能为空'],
        ['Freal_name','require','真实姓名不能为空'],
        ['Fcity_id','require','所在城市不能为空'],
        ['Fpassword','require','用户密码不能为空'],
        ['Fdepartment_id','require','部门不能为空'],
        ['Fposition_id','require','职位不能为空'],
        ['Fis_lock','in:1,2','锁定状态有误'],
        ['new_password','require','新密码不能为空'],
        ['old_password','require','旧密码不能为空'],



    ];

    protected $scene=[
        'login'=>['username','password','system_id'],//登录
        'loginLoad'=>['system_id'],//子系统登录加载
        'subLogin'=>['system_id','username','password'],//子系统登录

        'registerSystem'=>['token','user_id','system_id'],//注册子系统
        'info'=>['Fuser_id'],//注册子系统
        'del'=>['ids'],//删除
        'add'=>['Fusername','Freal_name','Fcity_id','Fpassword','Fdepartment_id','Fposition_id'],//注册
        'edit'=>['Fuser_id','Fdepartment_id','Fposition_id','Fis_lock'],//修改
        'editPassword'=>['new_password','old_password'],//修改密码
        'resetPassword'=>['user_id'],
        'logout'=>['user_id'],

    ];

}