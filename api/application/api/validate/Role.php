<?php
// +----------------------------------------------------------------------
// | 
// +----------------------------------------------------------------------
// | Author: wk <weika520@qq.com | http://www.wcphp.com> 2017/7/21 16:24
// +----------------------------------------------------------------------

namespace app\api\validate;

use think\Validate;

class Role extends Validate
{
    protected $rule=[
        ['Frole_id','require','角色ID不能为空'],
        ['Fname','require','系统名称不能为空'],
        ['Fsystem_id','require','系统ID不能为空'],
        ['ids','require','删除的系统ID不能为空'],
        ['Fstatus','in:1,2','修改状态有误'],
    ];
    protected $scene=[
        'add'=>['Fname','Fsystem_id'],
        'info'=>['Frole_id'],
        'edit'=>['Frole_id','Fname','Fsystem_id','Fstatus'],
        'del'=>['ids'],
    ];

}