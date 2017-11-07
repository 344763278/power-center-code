<?php
// +----------------------------------------------------------------------
// | 操作记录
// +----------------------------------------------------------------------
// | Author: wk <weika520@qq.com | http://www.wcphp.com> 2017/7/17 20:21
// +----------------------------------------------------------------------

namespace app\common\service;


use app\common\model\OperatorRecord;

class OperatorService extends BaseService
{
    /**
     * 写入记录
     * Author: wk <weika520@qq.com>
     */
    public function record($desc='',$requestParam='',$operatorId='',$operatorName='',$systemId='')
    {
        $data = [
            'Fdesc'=>$desc,
            'Frequest_param'=>is_array($requestParam) ? json_encode($requestParam) : $requestParam,
            'Fsystem_id'=>$systemId,
            'Foperatior_ip'=>request()->ip(),
            'Foperator_id'=>$operatorId,
            'Foperator_name'=>$operatorName,
            'Furi'=>request()->pathinfo(),
        ];
        OperatorRecord::create($data);
    }

}