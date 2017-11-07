<?php
// +----------------------------------------------------------------------
// | 
// +----------------------------------------------------------------------
// | Author: wk <weika520@qq.com | http://www.wcphp.com> 2017/7/21 16:24
// +----------------------------------------------------------------------

namespace app\api\validate;

use think\Validate;

class Authority extends Validate
{
    protected $rule=[
        ['Fsystem_id','require','系统ID不能为空'],
        ['Fname','require','权限名称不能为空'],
        ['Fauthority_id','require','权限ID不能为空'],
        ['ids','require','删除的权限ID参数有误'],
        ['Fstatus','in:1,2','修改状态有误'],
    ];
    protected $scene=[
        'add'=>['Fsystem_id','Fname'],
        'info'=>['Fauthority_id'],
        'edit'=>['Fauthority_id','Fname','Fsystem_id','Fstatus'],
        'del'=>['ids'],
        'select'=>['Fsystem_id'],
        'tree'=>['Fsystem_id'],
    ];

}