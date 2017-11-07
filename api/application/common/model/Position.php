<?php
// +----------------------------------------------------------------------
// | 职位模型
// +----------------------------------------------------------------------
// | Author: wk <weika520@qq.com | http://www.wcphp.com> 2017/7/17 1:14
// +----------------------------------------------------------------------

namespace app\common\model;

class Position extends BaseModel
{
    protected $autoWriteTimestamp = 'datetime';
    protected $updateTime = 'Fupdate_time';
    protected $createTime = 'Fcreate_time';
    protected $insert = ['Foperator_id','Foperator_name','Fcreate_id','Fcreate_name'];
    protected $update = ['Foperator_id','Foperator_name'];

    protected $defaultFieldMap = [
        'Fposition_id'=>'position_id',
        'Fname'=>'name',
        'Fdepartment_id'=>'department_id',
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
        return $this->setFieldMap(['position_id','name','desc','department_id','create_time','create_name'],false,true,true);
    }
    /**
     * 搜索筛选规则
     * @param array $searchConfig['searchRuleType'] 不同搜索规则
     * Author: wk <weika520@qq.com>
     */
    protected function _tidySearchRule(){
        return $tidyRule = [
            'where'=>[
                'p.Fname'=>['like','search_word'],
                'p.Fdepartment_id'=>['eq','department_id'],
            ],
            'order'=>['p.Fcreate_time'=>['sort','DESC']],
            'page'=>['page','page_size'],
        ];
    }








}