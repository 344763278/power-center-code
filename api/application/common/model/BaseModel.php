<?php
// +----------------------------------------------------------------------
// | 模型基类
// +----------------------------------------------------------------------
// | Author: wk <weika520@qq.com | http://www.wcphp.com> 2017/7/17 1:15
// +----------------------------------------------------------------------

namespace app\common\model;

use app\common\controller\Base;
use app\common\lib\Search;
use think\Model;

class BaseModel extends Model
{
    //默认的字段映射
    protected  $defaultFieldMap = [];

    //搜索配置
    protected $searchConfig = [];


    //操作人自动添加数据
    protected function setFoperatorIdAttr(){

        return empty(Base::$userInfo['user_info']['user_id']) ? '' : Base::$userInfo['user_info']['user_id'];
    }

    protected function setFoperatorNameAttr(){
        return empty(Base::$userInfo['user_info']['username']) ? '' : Base::$userInfo['user_info']['username'];
    }

    protected function setFcreateIdAttr(){
        return empty(Base::$userInfo['user_info']['user_id']) ? '' : Base::$userInfo['user_info']['user_id'];
    }

    protected function setFcreateNameAttr(){
        return empty(Base::$userInfo['user_info']['username']) ? '' : Base::$userInfo['user_info']['username'];
    }

    /**
     * 设置数据库查询对象
     * @param mixed
     * Author: wk <weika520@qq.com>
     */
    public function setQuery($queryObj)
    {
        unset($this->query);
        $this->query = $queryObj;
        return $this;
    }

    /**
     * 搜索查询接收参数方法
     * @param array $searchParam 控制器接收的查询参数
     * @param array $config 搜索配置
     * Author: wk <weika520@qq.com>
     */
    final public function searchQuery($searchCondition,$config=[]){
        //'searchRuleType','fieldType','appendFieldType','hiddenFiledType  多条件查询的时候使用
        $configItem = ['searchRuleType','fieldType','appendFieldType','hiddenFiledType'];
        $this->searchConfig=[];
        foreach($configItem as $item){
            if(isset($config[$item])){
                $this->searchConfig[$item] = $config[$item];
            }else{
                $this->searchConfig[$item] = [];
            }
        }
        return $this-> _searchQuery($this->_tidySearchCondition($searchCondition));

    }
    /**
     * 搜索查询实现方法
     * @param array $searchParam 控制器接收的查询参数
     * Author: wk <weika520@qq.com>
     */
    protected function _searchQuery($search)
    {
        $options = $this->getOptions();
        $resList = $this->where($search->where)
            ->field($this->_searchField())
            ->whereOr($search->whrerOr)
            ->order($search->order)
            ->page($search->page)
            //->fetchSql(true)
            ->select();
        //print_r($resList);exit;
        //总条数1秒
        $key = md5($this->getLastSql());
        if(empty($total = cache($key))){
            $this->setOptions($options);
            $total = $this->where($search->where)->whereOr($search->whrerOr)->count();
            cache($key,$total,1);
        }
        $searchData = [];
        if(!empty($resList)){
            foreach($resList as $row){
                $searchData[] = $row->append($this->_searchAppendField())->hidden($this->_searchHiddenField())->toArray();
            }
        }
        return $search = ['list'=>$searchData,'total'=>$total];
    }

    /**
     * 搜索返回字段
     * @param mixed $fieldType 不同搜索返回返回字段不一样使用
     * Author: wk <weika520@qq.com>
     */
    protected function _searchField(){
        return '*';
    }

    /**
     * 搜索返回增加字段
     * @param array $searchConfig['appendFieldType'] 不同搜索增加字段类型
     * Author: wk <weika520@qq.com>
     */
    protected function _searchAppendField(){
        return [];
    }

    /**
     * 搜索返回隐藏字段
     * @param array $searchConfig['hiddenFieldType'] 不同搜索隐藏字段类型
     * Author: wk <weika520@qq.com>
     */
    protected function _searchHiddenField(){
        return [];
    }
    /**
     * 整理控制器接收前端来的的查询参数
     * @param array $searchCondition 查询参数
     * Author: wk <weika520@qq.com>
     */
    final protected function _tidySearchCondition($searchCondition){
        //print_r( SearchEvent::tidy($this->_tidySearchRule(),$searchCondition));exit;
        return Search::tidy($this->_tidySearchRule(),$searchCondition);
    }

    /**
     * 搜索筛选规则
     * @param array $searchConfig['searchRuleType'] 不同搜索规则
     * Author: wk <weika520@qq.com>
     */
    protected function _tidySearchRule(){
        return [];
    }

    /**
     * 设置字段映射
     * @param array $fieldMap 改变的字段数组
     * @param bool $type 字段合并类型：true-并集，false-交集
     * @param bool $table 是否需要加表前缀，多表查询时加 true-加，false-不加
     * Author: wk <weika520@qq.com>
     */
    public function setFieldMap($fieldMap=[],$type=false,$addTable=false,$isSearch=false)
    {
        $filed = $this->defaultFieldMap;
        if(!empty($fieldMap) && $type){
            $filed = array_merge($filed ,$fieldMap);
        }elseif(!empty($fieldMap) && !$type){
            $filed = array_intersect($filed ,$fieldMap);
        }
        $table = $this->getTable();
        if($addTable){//加表前缀
            $fields = $filed;
            $filed = [];
            foreach($fields as $key=>$item){
                $key = $table.'.'.$key;
                $filed[$key] = $item;
            }
        }

        if($isSearch){
            return $filed;
        }else{
        //print_r($filed);exit;
        if(empty($filed))
            return $this;
        else
            return $this->field($filed);
    }

    }


}