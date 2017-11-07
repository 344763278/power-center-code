<?php
// +----------------------------------------------------------------------
// | 后台用户模型
// +----------------------------------------------------------------------
// | Author: wk <weika520@qq.com | http://www.wcphp.com> 2017/7/17 1:14
// +----------------------------------------------------------------------

namespace app\common\model;

use traits\model\SoftDelete;

class User extends BaseModel
{
    use SoftDelete;
    protected $deleteTime = 'Fdelete_time';
    protected $autoWriteTimestamp = 'datetime';
    protected $updateTime = 'Fupdate_time';
    protected $createTime = 'Fcreate_time';
    protected $insert = ['Foperator_id','Foperator_name','Fcreate_id','Fcreate_name'];
    protected $update = ['Foperator_id','Foperator_name'];


    protected $defaultFieldMap = [
        'Fuser_id'=>'user_id',
        'Fusername'=>'username',
        'Fpassword'=>'password',
        'Fphone'=>'phone',
        'Fsalt'=>'salt',
        'Fuser_desc'=>'user_desc',
        'Freal_name'=>'real_name',
        'Fphoto'=>'photo',
        'Femail'=>'email',
        'Fcity_id'=>'city_id',
        'Fregister_ip'=>'register_ip',
        'Fcreate_time'=>'create_time',
        'Fis_lock'=>'is_lock',
        'Flock_time'=>'lock_time',
        'Freset_password'=>'reset_password',
        'Flogin_times'=>'login_times',
        'Flast_time'=>'last_time',
        'Flast_ip'=>'last_ip',
        'Fupdate_time'=>'update_time',
        'Fdepartment_id'=>'department_id',
        'Fposition_id'=>'position_id',
        'Foperator_id'=>'operator_id',
        'Foperator_name'=>'operator_name',
        'Fcreate_id'=>'create_id',
        'Fcreate_name'=>'create_name',
    ];

    protected function _searchField()
    {
        return $this->setFieldMap(['user_id','username','real_name','city_id','user_desc','create_time','last_time','last_ip','login_times','department_id','position_id','is_lock','create_name','email'],false,true,true);
    }
    /**
     * 搜索筛选规则
     * @param array $searchConfig['searchRuleType'] 不同搜索规则
     * Author: wk <weika520@qq.com>
     */
    protected function _tidySearchRule(){
        return $tidyRule = [
            'where'=>[
                'u.Fusername|u.Freal_name'=>['like','search_word'],
            ],
            'order'=>['u.Fcreate_time'=>['sort','DESC']],
            'page'=>['page','page_size'],
        ];
    }
}