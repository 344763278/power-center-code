<?php
// +----------------------------------------------------------------------
// | 接口公共基类
// +----------------------------------------------------------------------
// | Author: wk <weika520@qq.com | http://www.wcphp.com> 2017/7/11 15:00
// +----------------------------------------------------------------------

namespace app\common\controller;

use app\common\service\OperatorService;
use think\Controller;
use think\Exception;
use think\Log;
use think\Request;

class Base extends Com
{
    protected  $requestData=[];//请求数据
    protected  $operatorDesc = '';//操作描述
    protected  $OperatorRequestData = true;//是否记录操作请求头

    public function __construct(Request $request)
    {
        parent::__construct($request);
        //获取请求数据
        $this->getRequestData();
    }
    public function _empty(){
        apiFail(10001,'接口不存在');
    }


    /**
     * 获取提交的接口请求参数并按指定规则接收和校验
     * Author: wk <weika520@qq.com>
     */
    protected function getParams($map=[],$validate='')
    {
        try{
            if(!is_array($map)){
                throw new Exception('过滤参数映射条件必须为数组',10002);
            }else{
                $data = [];
                //接口请求参数
                $param = $this->getRequestData('params');

                foreach ($map as $key => $item) {
                    if (is_int($key)) {
                        $key = $item;
                    }
                    if (is_array($item)) {
                        $row = isset($item[1]) ? (isset($param[$item[0]]) ? $param[$item[0]] : $item[1]) : (isset($param[$item[0]]) ? $param[$item[0]] : '');
                    } else {
                        $row = isset($param[$item]) ? $param[$item] : '';
                    }
                    // 解析name
                    if (strpos($key, '/')) {
                        list($key, $type) = explode('/', $key);
                    } else {
                        $type = 's';
                    }
                    //接收参数强制类型转换
                    $this->_typeCast($row, $type);
                    $data[$key] = $row;
                }
                //验证
                if (!empty($validate)) {
                    $result = $this->validate($data, $validate);
                    if ($result !== true) {
                        //参数验证失败抛出错误
                        throw new Exception($result,10003);
                    }
                }
                return $data;
            }

        }catch(Exception $e){
            $error = ['code'=>$e->getCode(),'msg'=>$e->getMessage()];
            //请求参数校验错误日志
            Log::record('paramVerify:'.json_encode($error,JSON_UNESCAPED_UNICODE ),'api');
            abort($error['code'],$error['msg']);
        }
    }

    /**
     * 获取接口请求数据
     * Author: wk <weika520@qq.com>
     */
    protected function getRequestData($name='')
    {
        if(empty($this->requestData)){
            $this->requestData = $this->request->getContent();
            //接口请求日志
            Log::record(['接口请求参数:',$this->requestData],'api');
            $this->requestData = json_decode($this->requestData, true);
            if(!isset($this->requestData) || !isset($this->requestData['head']) || !isset($this->requestData['params'])){
                abort(10004, '接口请求数据格式有误');
            }
        }
        if(empty($name)){
            return $this->requestData;
        }elseif(in_array($name,['head','params'])){
            return $this->requestData[$name];
        }else{
            return '';
        }
    }
    /**
     * 强制类型转换
     * @param string $data 数据
     * @param string $type 类型
     * @return mixed
     */
    protected function _typeCast(&$data, $type)
    {
        switch (strtolower($type)) {
            // 数组
            case 'a':
                $data = (array) $data;
                break;
            // 数字
            case 'd':
                $data = (int) $data;
                break;
            // 浮点
            case 'f':
                $data = (float) $data;
                break;
            // 布尔
            case 'b':
                $data = (boolean) $data;
                break;
            // 字符串
            case 's':
            default:
                //标量变量
                if (is_scalar($data)) {
                    $data = trim((string) $data);
                }
        }
    }

    /**
     * 操作日志
     * @param mixed $desc 操作描述
     * Author: wk <weika520@qq.com>
     */
    public function operatorRecord($desc='',$operatorId='',$operatorName='',$systemId=''){
        $desc = empty($desc) ? $this->operatorDesc : $desc;
        if(!empty($desc)){
            $operatorId = empty($operatorId) ?(isset(self::$userInfo['user_id']) ? self::$userInfo['user_id'] : '') :$operatorId;
            $systemId = empty($systemId) ?(isset(self::$systemId) ? self::$systemId : '') :$systemId;
            $requestParam = $this->OperatorRequestData ? $this->getRequestData('params'): '';
            //记录操作日志
            $operator = new OperatorService();
            $operator->record($desc,$requestParam,$operatorId,$operatorName,$systemId);
        }
    }

    /**
     * 更新 $userInfo 在缓存中的值
     * Author: wk <weika520@qq.com>
     */
    protected function updateUserInfoCache()
    {
        if(!empty(self::$userInfo['user_id']))
            self::$redis->set(config('amc_user_info_prefix').self::$userInfo['user_id'],self::$userInfo,config('amc_user_info_time'));
    }

    /**
     * 析构函数
     * @param mixed
     * Author: wk <weika520@qq.com>
     */
    public function __destruct()
    {
        //操作日志
        $this->operatorRecord();
        //更新 $userInfo 在缓存中的值
       $this->updateUserInfoCache();
    }



}