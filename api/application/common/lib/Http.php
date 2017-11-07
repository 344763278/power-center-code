<?php

/**
 * API接口错误异常处理
 * Author: wk <weika520@qq.com>
 * Date: 2016/11/23 16:42
 */
namespace app\common\lib;

use think\exception\Handle;
use think\exception\HttpException;

class Http extends Handle
{
    public function render(\Exception $e)
    {
        if ($e instanceof HttpException) {
            $statusCode = $e->getStatusCode();
        }
        if (!isset($statusCode) || $statusCode<10000) {
            $statusCode = '10000';
        }
        $result = [
            'head'=>[
                'interface'=>request()->path(),
                'msgtype'=>'response',
                'remark'=>'',
                'version'=>'0.01'
            ],
            'body'=>[
                'data'=>new \stdClass(),
                'ret'=>'1',
                'retcode'=>$statusCode,
                'retinfo'=>(string)$e->getMessage(),
            ]
        ];
        arrayIntToString($result);
        return  json($result);
    }
}