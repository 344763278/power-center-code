<?php
// +----------------------------------------------------------------------
// | 
// +----------------------------------------------------------------------
// | Author: wk <weika520@qq.com | http://www.wcphp.com> 2017/7/21 16:24
// +----------------------------------------------------------------------

namespace app\api\validate;

use think\Validate;

class Department extends Validate
{
    protected $rule=[
        ['Fdepartment_id','require','部门ID不能为空'],
        ['Fname','require','部门名称不能为空'],
        ['Fdesc','require','部门描述不能为空'],
        ['ids','require','删除的部门ID参数有误'],
    ];
    protected $scene=[
        'add'=>['Fname','Fdesc'],
        'info'=>['Fdepartment_id'],
        'edit'=>['Fdepartment_id','Fname','Fdesc'],
        'del'=>['ids'],
    ];

}