<?php
// +----------------------------------------------------------------------
// | 
// +----------------------------------------------------------------------
// | Author: wk <weika520@qq.com | http://www.wcphp.com> 2017/7/21 16:24
// +----------------------------------------------------------------------

namespace app\api\validate;

use think\Validate;

class Position extends Validate
{
    protected $rule=[
        ['Fdepartment_id','require','部门ID不能为空'],
        ['Fname','require','职位名称不能为空'],
        ['Fdesc','require','职位描述能为空'],
        ['Fposition_id','require','职位ID不能为空'],
        ['ids','require','删除的职位ID参数有误'],

    ];
    protected $scene=[
        'add'=>['Fname','Fdesc','Fdepartment_id'],
        'info'=>['Fposition_id'],
        'edit'=>['Fposition_id','Fname','Fdesc'],
        'del'=>['ids'],
    ];

}