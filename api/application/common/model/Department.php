<?php
// +----------------------------------------------------------------------
// | 
// +----------------------------------------------------------------------
// | Author: wk <weika520@qq.com | http://www.wcphp.com> 2017/7/22 10:34
// +----------------------------------------------------------------------

namespace app\common\model;


use app\common\controller\Base;

class Department extends BaseModel
{
    protected $autoWriteTimestamp = 'datetime';
    protected $updateTime = 'Fupdate_time';
    protected $createTime = 'Fcreate_time';
    protected $insert = ['Foperator_id','Foperator_name','Fcreate_id','Fcreate_name'];
    protected $update = ['Foperator_id','Foperator_name'];

    protected $defaultFieldMap = [
        'Fdepartment_id'=>'department_id',
        'Fname'=>'name',
        'Fdesc'=>'desc',
        'Fcreate_time'=>'create_time',
        'Fupdate_time'=>'update_time',
        'Foperator_id'=>'operator_id',
        'Foperator_name'=>'position_name',
        'Fcreate_id'=>'create_id',
        'Fcreate_name'=>'create_name',
    ];

    protected function _searchField()
    {
        return $this->setFieldMap(['department_id','name','desc','create_time','create_name'],false,false,true);
    }
    /**
     * 搜索筛选规则
     * @param array $searchConfig['searchRuleType'] 不同搜索规则
     * Author: wk <weika520@qq.com>
     */
    protected function _tidySearchRule(){
        return $tidyRule = [
            'where'=>[
                'Fname'=>['like','search_word'],//部门名称
            ],
            'order'=>['Fcreate_time'=>['sort','DESC']],
            'page'=>['page','page_size'],
        ];
    }



}