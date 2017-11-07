<?php
// +----------------------------------------------------------------------
// | 
// +----------------------------------------------------------------------
// | Author: wk <weika520@qq.com | http://www.wcphp.com> 2017/7/19 15:35
// +----------------------------------------------------------------------

namespace app\common\model;


use traits\model\SoftDelete;

class System extends BaseModel
{
    use SoftDelete;
    protected $autoWriteTimestamp = 'datetime';
    protected $updateTime = 'Fupdate_time';
    protected $createTime = 'Fcreate_time';
    protected $insert = ['Foperator_id','Foperator_name','Fcreate_id','Fcreate_name'];
    protected $update = ['Foperator_id','Foperator_name'];
    protected $deleteTime = 'Fdelete_time';

    protected $defaultFieldMap = [
        'Fsystem_id'=>'system_id',
        'Fname'=>'name',
        'Furl'=>'url',
        'Fsystem_desc'=>'system_desc',
        'Fcreate_time'=>'create_time',
        'Fupdate_time'=>'update_time',
        'Foperator_id'=>'operator_id',
        'Foperator_name'=>'position_name',
        'Fcreate_id'=>'create_id',
        'Fcreate_name'=>'create_name',
    ];

    protected function _searchField()
    {
        return $this->setFieldMap(['system_id','name','url','system_desc','create_time','create_name'],false,false,true);
    }
    /**
     * 搜索筛选规则
     * @param array $searchConfig['searchRuleType'] 不同搜索规则
     * Author: wk <weika520@qq.com>
     */
    protected function _tidySearchRule(){
        return $tidyRule = [
            'where'=>[
                'Fname'=>['like','search_word'],//
        ],
            'order'=>['Fcreate_time'=>['sort','DESC']],
            'page'=>['page','page_size'],
        ];
    }




}