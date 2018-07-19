<?php

namespace Jormin\SFExpress;

class SFExpress extends BaseObject {

    /**
     * 下单
     *
     * @param string $order 客户订单号，最大长度限于56位
     * @param string $custId 顺丰月结卡号10位数字
     * @param array $consigneeInfo 收件方信息
     * @param array $deliverInfo 寄件方信息
     * @param string $remark 备注，最大长度30个汉字
     * @param int $expressType 快件产品类别 [1:标准快递(默认) 2:顺丰特惠 3:电商特惠 5:顺丰次晨 6:顺丰即日 7:电商速配 15:生鲜速配]
     * @param int $payMethod 付款方式 [1:寄方付(默认) 2:收方付【暂不支持】3:第三方付]
     * @param int $isDocall 是否通知收派员上门取件 [1:通知 0:不通知(默认)]
     * @param int $isGenBillno 是否申请运单号 [1:申请(默认) 0:不申请]
     * @param int $isGenEletricPic 是否生成电子运单图片 [1:生成(默认) 0:不生成]
     * @param string $payArea 月结卡号对应的网点，如果付款方式为第三方支付，则必填
     * @param string $sendStartTime 要求上门取件开始时间，格式：YYYY-MM-DDHH24:MM:SS，示例：2012-7-30 09:30:00，默认值为系统收到订单的系统时间
     * @param int $needReturnTrackingNo 是否需要签回单号 [1:需要 0:不需要(默认)]
     * @param array $cargoInfo 货物信息
     * @param array $addedServices 增值服务（注意字段名称必须为英文字母大写）
     * @return array
     */
    public function createOrder($order, $custId, $consigneeInfo, $deliverInfo=null, $remark=null, $expressType=1, $payMethod=1, $isDocall=0, $isGenBillno=1, $isGenEletricPic=1, $payArea=null, $sendStartTime=null, $needReturnTrackingNo=0, $cargoInfo=null, $addedServices=null){
        if(!$order){
            $this->error('客户订单号不能为空');
        }
        if(!$custId){
            $this->error('月结卡号不能为空');
        }
        if(!$consigneeInfo){
            $this->error('收件方信息不能为空');
        }
        if($payMethod === 3 && !$payArea){
            $this->error('付款方式为第三方支付时，月卡号对应的网店信息不能空');
        }
        $data = [
            'orderId' => $order,
            'expressType' => $expressType,
            'payMethod' => $payMethod,
            'isDocall' => $isDocall,
            'isGenBillno' => $isGenBillno,
            'isGenEletricPic' => $isGenEletricPic,
            'custId' => $custId,
            'payArea' => $payArea,
            'sendStartTime' => $sendStartTime,
            'needReturnTrackingNo' => $needReturnTrackingNo,
            'remark' => $remark,
            'deliverInfo' => $deliverInfo,
            'consigneeInfo' => $consigneeInfo,
            'cargoInfo' => $cargoInfo,
            'addedServices' => $addedServices,
        ];
        return $this->request('200', $data);
    }
}
