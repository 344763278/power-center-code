<?php
// +----------------------------------------------------------------------
// | 路由
// +----------------------------------------------------------------------

return [
    //校验接口
    'authlogin$'=>['api/auth/login',['method'=>'post']],//登录接口
    'authregistersystem$'=>['api/auth/registerSystem',['method'=>'post']],//注册系统接口
    'logininfo$'=>['api/user/loginInfo',['method'=>'post']],//获取用户信息


    //外部子系统
    'login'=>'api/public/login',//登录
    'loginuserinfo$'=>['api/Subsystem/loginInfo',['method'=>'post']],//获取登录后的子系统用户信息
    'logout'=>['api/auth/logout',['method'=>'post']],//退出子系统
    'systemusers'=>['api/Subsystem/systemUserList',['method'=>'post']],    //获取系统用户列表


    //部门
    'departmentlists$'=>['api/Department/lists',['method'=>'post']],//列表
    'departmentadd$'=>['api/Department/add',['method'=>'post']],//添加
    'departmentinfo$'=>['api/Department/info',['method'=>'post']],//详情
    'departmentedit$'=>['api/Department/edit',['method'=>'post']],//修改
    'departmentdel$'=>['api/Department/del',['method'=>'post']],//删除

    'departmentselect$'=>['api/Department/departmentSelect',['method'=>'post']],//部门选择

    //职位
    'positionlists$'=>['api/Position/lists',['method'=>'post']],//列表
    'positionadd$'=>['api/Position/add',['method'=>'post']],//添加
    'positioninfo$'=>['api/Position/info',['method'=>'post']],//详情
    'positionedit$'=>['api/Position/edit',['method'=>'post']],//修改
    'positiondel$'=>['api/Position/del',['method'=>'post']],//删除

    'positionselect$'=>['api/Position/positionSelect',['method'=>'post']],//职位选择


    //规则权限
    'authoritylists$'=>['api/Authority/lists',['method'=>'post']],//列表
    'authorityadd$'=>['api/Authority/add',['method'=>'post']],//添加
    'authorityinfo$'=>['api/Authority/info',['method'=>'post']],//详情
    'authorityedit$'=>['api/Authority/edit',['method'=>'post']],//修改
    'authoritydel$'=>['api/Authority/del',['method'=>'post']],//删除

    'columnselect$'=>['api/Authority/columnSelect',['method'=>'post']],//栏目选项

    'authoritytree$'=>['api/Authority/authorityTree',['method'=>'post']],//权限树


       //用户
    'userlists$'=>['api/User/lists',['method'=>'post']],//列表
    'useradd$'=>['api/User/add',['method'=>'post']],//添加
    'userinfo$'=>['api/User/info',['method'=>'post']],//详情
    'useredit$'=>['api/User/edit',['method'=>'post']],//修改
    'userdel$'=>['api/User/del',['method'=>'post']],//删除
    'editpassword$'=>['api/User/editPassword',['method'=>'post']],//修改密码
    'resetpassword$'=>['api/User/resetPassword',['method'=>'post']],//重置密码
    'usersyncboss$'=>['api/User/syncBoss',['method'=>'post']],//同步boss用户


    //角色
    'rolelists$'=>['api/Role/lists',['method'=>'post']],//列表
    'roleadd$'=>['api/Role/add',['method'=>'post']],//添加
    'roleinfo$'=>['api/Role/info',['method'=>'post']],//详情
    'roleedit$'=>['api/Role/edit',['method'=>'post']],//修改
    'roledel$'=>['api/Role/del',['method'=>'post']],//删除


    //系统
    'systemlists$'=>['api/System/lists',['method'=>'post']],//列表
    'systemadd$'=>['api/System/add',['method'=>'post']],//添加
    'systeminfo$'=>['api/System/info',['method'=>'post']],//详情
    'systemedit$'=>['api/System/edit',['method'=>'post']],//修改
    'systemdel$'=>['api/System/del',['method'=>'post']],//删除


    'systemselect'=>['api/System/systemSelect',['method'=>'post']],//系统选项

    //首页路由
    '/'=>'api/index/index',
    //后台路由映射  隐藏index模块
    ':controller/:action'=>'api/:controller/:action'

];
