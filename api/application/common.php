<?php
// +----------------------------------------------------------------------
// | 应用公共文件
// +----------------------------------------------------------------------
// | Author: wk <weika520@qq.com | http://www.wcphp.com> 2017/7/11 9:31
// +----------------------------------------------------------------------
if (!function_exists('arrayIntToString')) {
    /**
     * 数组元素中的数字类型强制转换为string类型
     */
    function arrayIntToString(&$arr)
    {
        array_walk_recursive($arr,function(&$item){
            if(is_numeric($item) || empty($item)){
                $item = (string)$item;
            }
        });
    }
}

if (!function_exists('sha5')) {
    /* *
     *  密码加密函数
     */
    function sha5($password='',$salt='')
    {
        return md5($password.config('amc_key').$salt);
    }
}

if (!function_exists('random_str')) {
    /* *
     * 获取指定长度的随机字符串
     * @param int $length  随机字符串长度
     */
    function random_str($length=8)
    {
        $num =ceil(intval($length)/32);
        $str='';
        for($i=1;$i<=$num;$i++)
            $str .= md5($str.microtime().mt_rand(100000,999999));
        return  substr($str,0,$length);
    }

}


if (!function_exists('apiSuccess')) {
    /**
     * 接口请求成功返回数据
     */
    function apiSuccess($msg ='SUCCESS',$data=[] )
    {
        empty($data) && $data = new \stdClass();
        $result = [
            'head'=>[
                'interface'=>request()->path(),
                'msgtype'=>'response',
                'remark'=>'',
                'version'=>'0.01'
            ],
            'body'=>[
                'data'=>$data,
                'ret'=>'0',
                'retcode'=>'0',
                'retinfo'=>$msg,
            ]
        ];
        arrayIntToString($result);
        \think\Log::record('[RESPONSE]'.json_encode($result,JSON_UNESCAPED_UNICODE),'api');
        $response =  \think\Response::create($result, 'json', 200);
        throw new \think\exception\HttpResponseException($response);

    }
}

if (!function_exists('apiFail')) {
    /**
     * 接口请求失败返回数据
     */
    function apiFail($errcode=10008,$msg ='Fail',$data=[])
    {
        empty($data) && $data = new \stdClass();
        $result = [
            'head'=>[
                'interface'=>request()->path(),
                'msgtype'=>'response',
                'remark'=>'',
                'version'=>'0.01'
            ],
            'body'=>[
                'data'=>$data,
                'ret'=>'1',
                'retcode'=>$errcode,
                'retinfo'=>$msg,
            ]
        ];
        arrayIntToString($result);
        \think\Log::record('[RESPONSE]'.json_encode($result,JSON_UNESCAPED_UNICODE),'api');
        $response = \think\Response::create($result, 'json', 200);
        throw new \think\exception\HttpResponseException($response);

    }
}

if (!function_exists('get_url_param')) {
    /**
     * 通过url获取带参数的url
     */
    function get_url_param($url,$param=[])
    {
        $url = urldecode($url);
        $param = urldecode(is_array($param) ? (empty($param) ? '' :http_build_query($param)) : $param);
        if(false === strpos($url, '://')){
            $url = 'http://'.$url;
        }
        if(false === strpos($url, '?')){
            $url = $url.'?'.$param;
        }else{
            $url = $url . '&' .$param;
        }
        return $url;
    }
}


