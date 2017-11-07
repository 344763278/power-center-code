<?php
// +----------------------------------------------------------------------
// | 
// +----------------------------------------------------------------------
// | Author: wk <weika520@qq.com | http://www.wcphp.com> 2017/7/21 16:24
// +----------------------------------------------------------------------

namespace app\api\validate;

use think\Validate;

class System extends Validate
{
    protected $rule=[
        ['Fname','require','系统名称不能为空'],
        ['Furl','require','系统地址(url)不能为空'],
        ['Fsystem_id','require','系统ID不能为空'],
        ['ids','require','删除的系统ID不能为空'],
    ];
    protected $scene=[
        'add'=>['Fname','Furl'],
        'info'=>['Fsystem_id'],
        'edit'=>['Fsystem_id','Fname','Furl'],
        'del'=>['ids'],
    ];

}