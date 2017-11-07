<?php
// +----------------------------------------------------------------------
// | 角色模型
// +----------------------------------------------------------------------
// | Author: wk <weika520@qq.com | http://www.wcphp.com> 2017/7/22 10:34
// +----------------------------------------------------------------------

namespace app\common\model;


class Role extends BaseModel
{
    protected $autoWriteTimestamp = 'datetime';
    protected $updateTime = 'Fupdate_time';
    protected $createTime = 'Fcreate_time';
    protected $insert = ['Foperator_id','Foperator_name','Fcreate_id','Fcreate_name'];
    protected $update = ['Foperator_id','Foperator_name'];

    protected $defaultFieldMap = [
        'Frole_id'=>'role_id',
        'Fname'=>'name',
        'Fsystem_id'=>'system_id',
        'Fdesc'=>'desc',
        'Fstatus'=>'status',
        'Fcreate_time'=>'create_time',
        'Fupdate_time'=>'update_time',
        'Foperator_id'=>'operator_id',
        'Foperator_name'=>'position_name',
        'Fcreate_id'=>'create_id',
        'Fcreate_name'=>'create_name',
    ];
    protected function _searchField()
    {
        return $this->setFieldMap(['role_id','name','system_id','desc','create_time','create_name','status'],false,true,true);
    }
    /**
     * 搜索筛选规则
     * @param array $searchConfig['searchRuleType'] 不同搜索规则
     * Author: wk <weika520@qq.com>
     */
    protected function _tidySearchRule(){
        return $tidyRule = [
            'where'=>[
                'r.Fname'=>['like','search_word'],
                's.Fsystem_id'=>['eq','Fsystem_id'],
            ],
            'order'=>['r.Fcreate_time'=>['sort','DESC']],
            'page'=>['page','page_size'],
        ];
    }



}