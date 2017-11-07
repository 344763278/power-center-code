<?php
// +----------------------------------------------------------------------
// | 搜索条件处理
// +----------------------------------------------------------------------
// | Author: wk <weika520@qq.com | http://www.wcphp.com> 2017/5/11 22:31
// +----------------------------------------------------------------------

namespace app\common\lib;


class Search
{
    protected $where = [];
    protected $whereOr = [];
    protected $order = [];
    protected $page = '';
    protected static  $obj ='';
    /**
     * 处理搜索条件
     * @param array  $search 搜索条件
     * Author: wk <weika520@qq.com>
     */
    public static  function tidy($searchRule,$data=[])
    {
        self::$obj = new self;
        foreach($searchRule as $key=>$item){
            $key = strtolower($key);
            switch($key){
                case 'where':
                    self::$obj->tidyWhere($item,$data);
                    break;
                case 'order':
                    self::$obj->tidyOrder($item,$data);
                    break;
                case 'page':
                    self::$obj->tidyPage($item,$data);
                    break;
                default:
                    break;
            }

        }
        return self::$obj;
    }
    /**
     * 整理查询条件
     * Author: wk <weika520@qq.com>
     */
    public  function tidyWhere($where,$data){
        foreach($where as $k=>$v){
            if(is_string($v[0])){
                $res = $this->tidySymbol($v[0],$v[1],$data);
                if($res !== false){
                    $this->where = array_merge($this->where,[$k=>$res]);
                }
            }else{
                $res = $this->tidySymbol($v[0][0],$v[1],$data);
                if($res !== false){
                    $this->whereOr = array_merge($this->whereOr,[$k=>$res]);
                }

            }
        }
    }

    /**
     * 根据不同符号处理查询条件
     * Author: wk <weika520@qq.com>
     */
    public function tidySymbol($symbol,$param,$data){
        $res = false;
        $symbol = strtolower($symbol);
        if($symbol == 'time'){
            //区间时间
            if(is_array($param)){
                $startDate = $data[$param[0]];
                $endDate = $data[$param[1]];
                if(!empty($startDate) && !empty($endDate)){
                    $startDate = date('Y-m-d 00:00:00',strtotime($startDate));
                    $endDate = date('Y-m-d 23:59:59',strtotime($endDate));
                    $res = $startDate == $endDate ? ['eq',$startDate] : ['between time',[$startDate,$endDate]];
                }elseif(!empty($startDate)){
                    $startDate = date('Y-m-d 00:00:00',strtotime($startDate));
                    $res = ['egt',$startDate];
                }elseif(!empty($endDate)){
                    $endDate = date('Y-m-d 23:59:59',strtotime($endDate));
                    $res = ['elt',$endDate];
                }
                //等于这个时间
            }else{
                if(!empty($data[$param])){
                    return date('Y-m-d H:i:s',strtotime($data['$param']));
                }
            }
        }elseif($symbol == 'like'){
            if(isset($data[$param]) && $data[$param] !==''){
                $value =(string)$data[$param];
                $res = ['like',['%'.trim($value).'%']];
            }
        }elseif(in_array($symbol,['eq','=','neq','<>','gt','>','egt','>=','lt','<','elt','<=','in'])){
            if(is_array($param)){//数据库值与展示值映射   例如：展示1  数据库中存的是 10  或者展示是男数据库中存的是 1
                if(!empty($data[$param[0]]) && is_array($param[1])){
                    $key = $data[$param[0]];
                    foreach ($param[1] as $k=>$v){
                        if($key == $k){
                            $res = [$symbol,$v];
                            break;
                        }
                    }
                }
            }else{
                if(!empty($data[$param])){
                    $res = [$symbol,(string)$data[$param]];
                }
            }
        }
        return $res;
    }
    /**
     * 整理排序条件
     * Author: wk <weika520@qq.com>
     */
    public  function tidyOrder($order,$data){
            //  'order'=>['字段'=>['提交字段','asc']]], asc   desc

        $res = [];
           foreach($order as $key=>$item){
               if(is_array($item) && (empty($item[0]) || empty($data[$item[0]]))&& isset($item[1]) && in_array(strtolower($item[1]),['asc','desc'])) {
                   $res = array_merge($res, [$key => strtolower($item[1])]);
               }elseif(is_array($item) && isset($item[0]) && isset($data[$item[0]]) && in_array(strtolower($data[$item[0]]),['asc','desc'])){
                   $res = array_merge($res, [$key => strtolower($data[$item[0]])]);
               }elseif(is_string($item) && isset($data[$item]) && in_array(strtolower($data[$item]),['asc','desc'])){
                   $res = array_merge($res,[$key=>strtolower($data[$item])]);
               }
           }
        $this->order = $res;
    }
    /**
     * 整理每页
     * Author: wk <weika520@qq.com>
     */
    public  function tidyPage($page,$data){
        if(isset($page[0]) && is_numeric($page[0])){
            $res = $page[0];
        }elseif(isset($page[0]) && isset($data[$page[0]])){
            $res = (int)$data[$page[0]];
        }else{
            $res =1;
        }

        if(isset($page[1]) && is_numeric($page[1])){
            $res .=',' .  $page[1];
        }elseif(isset($page[1]) && isset($data[$page[1]])){
            $res .=',' .   (int)$data[$page[1]];
        }else{
            $res .=',' .  config('page_size');
        }
       // echo $res;exit;
        $this->page = $res;
    }

    /**
     *
     * @param mixed
     * Author: wk <weika520@qq.com>
     */
    public function __get($name)
    {
        if(isset($this->$name)){
            return $this->$name;
        }else{
            return '';
        }
    }


}