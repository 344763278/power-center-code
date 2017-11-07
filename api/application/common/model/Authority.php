<?php
// +----------------------------------------------------------------------
// | 权限模型
// +----------------------------------------------------------------------
// | Author: wk <weika520@qq.com | http://www.wcphp.com> 2017/7/21 11:40
// +----------------------------------------------------------------------

namespace app\common\model;


class Authority extends BaseModel
{
    protected $autoWriteTimestamp = 'datetime';
    protected $updateTime = 'Fupdate_time';
    protected $createTime = 'Fcreate_time';
    protected $insert = ['Foperator_id','Foperator_name','Fcreate_id','Fcreate_name'];
    protected $update = ['Foperator_id','Foperator_name'];

    protected $defaultFieldMap = [
        'Fauthority_id'=>'authority_id',
        'Fname'=>'name',
        'Ftype'=>'type',
        'Fsystem_id'=>'system_id',
        'Fpid'=>'pid',
        'Fstyle'=>'style',
        'Faccess_flags'=>'access_flags',
        'Fshow_flags'=>'show_flags',
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
        return $this->setFieldMap(['authority_id','system_id','name','type','pid','style','access_flags','show_flags','status','create_time','create_name'],false,true,true);
    }
    /**
     * 搜索筛选规则
     * @param array $searchConfig['searchRuleType'] 不同搜索规则
     * Author: wk <weika520@qq.com>
     */
    protected function _tidySearchRule(){
        return $tidyRule = [
            'where'=>[
                'a.Fsystem_id'=>['eq','system_id'],
                'a.Fname'=>['like','search_word'],
                'a.Fstatus'=>['eq','status'],
            ],
            'page'=>['page','page_size'],
        ];
    }

}