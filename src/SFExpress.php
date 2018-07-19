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
            'addedServices' => $addedServices
        ];
        return $this->request('200', $data);
    }

    /**
     * 订单查询
     *
     * @param string $order 客户订单号，最大长度限于56位
     * @return array
     */
    public function orderQuery($order){
        if(!$order){
            $this->error('客户订单号不能为空');
        }
        $data = [
            'orderId' => $order
        ];
        return $this->request('203', $data);
    }

    /**
     * 订单筛选
     *
     * @param string$consigneeAddress 到件方详细地址
     * @param string $consigneeProvince 到件方所在省份
     * @param string $consigneeCity 到件方所属城市名称
     * @param string $consigneeCounty 到件人所在县/区
     * @param string $deliverAddress 寄件方详细地址，当寄件方省份、城市、区/县三者其一不为空时，则寄件方详细地址不能为空
     * @param string $deliverProvince 寄件方所在省份
     * @param string $deliverCity 寄件方所属城市名称
     * @param string $deliverCounty 寄件人所在县/区
     * @param string $order 客户订单号，最大长度限于56位
     * @param string $consigneeTel 到件方联系电话
     * @param string $deliverTel 寄件方联系电话
     * @param string $deliverCustId 寄方客户编码
     * @param string $consigneeCountry 到件方国家，默认值为中国
     * @param string $deliverCountry 寄件人所在国家，默认值为中国
     * @return array
     */
    public function orderFilter($consigneeAddress, $consigneeProvince, $consigneeCity, $consigneeCounty, $deliverAddress=null, $deliverProvince=null, $deliverCity=null, $deliverCounty=null, $order=null, $consigneeTel=null, $deliverTel=null, $deliverCustId=null, $consigneeCountry='中国', $deliverCountry='中国'){
        if(!$consigneeAddress || !$consigneeProvince || !$consigneeCity || !$consigneeCounty){
            $this->error('到件方所在省份/城市/县区/详细地址不能为空');
        }
        if(($deliverProvince || $deliverCity || $deliverCounty) && !$deliverAddress){
            $this->error('当寄件方省份、城市、区/县三者其一不为空时，则寄件方详细地址不能为空');
        }
        $data = [
            'filterType' => 1,
            'orderId' => $order,
            'consigneeAddress' => $consigneeAddress,
            'deliverCustId' => $deliverCustId,
            'deliverTel' => $deliverTel,
            'deliverAddress' => $deliverAddress,
            'deliverProvince' => $deliverProvince,
            'deliverCity' => $deliverCity,
            'deliverCounty' => $deliverCounty,
            'deliverCountry' => $deliverCountry,
            'consigneeTel' => $consigneeTel,
            'consigneeProvince' => $consigneeProvince,
            'consigneeCity' => $consigneeCity,
            'consigneeCounty' => $consigneeCounty,
            'consigneeCountry' => $consigneeCountry
        ];
        return $this->request('204', $data);
    }

    /**
     * 路由查询
     *
     * @param string $trackingNumber 查询号（订单号/运单号）,如果有多个单号，以英文逗号分隔,如”755123456789, 755123456788, 755123456787”批量查询中，最多不能超过5个单号
     * @param int $trackingType 查询类别 [1:根据运单号查询【只支持查询客户在本系统下的订单对应的顺丰运单号】（默认） 2:根据订单号查询]
     * @param int $methodType 查询方法选择 [1:标准查询(默认) 2:定制查询【暂不支持】]
     * @return array
     */
    public function routeQuery($trackingNumber, $trackingType=1, $methodType=1){
        if(!$trackingNumber){
            $this->error('查询号不能为空');
        }
        $data = [
            'trackingType' => $trackingType,
            'trackingNumber' => $trackingNumber,
            'methodType' => $methodType
        ];
        return $this->request('501', $data);
    }

    /**
     * 路由增量信息申请
     *
     * @param string $order 客户订单号，最大长度限于56位
     * @return array
     */
    public function routePushApply($order){
        if(!$order){
            $this->error('客户订单号不能为空');
        }
        $data = [
            'orderId' => $order,
            'status' => 1
        ];
        return $this->request('503', $data);
    }

    /**
     * 路由增量查询
     *
     * @param string $order 客户订单号，最大长度限于56位
     * @return array
     */
    public function routeIncQuery($order){
        if(!$order){
            $this->error('客户订单号不能为空');
        }
        $data = [
            'orderId' => $order,
        ];
        return $this->request('504', $data);
    }

    /**
     * 基础服务查询
     *
     * @return array
     */
    public function productBasicQuery(){
        return $this->request('250');
    }

    /**
     * 附加服务查询
     *
     * @return array
     */
    public function productAdditionalQuery(){
        return $this->request('251');
    }

    /**
     * 电子运单图片下载
     *
     * @param string $order 客户订单号，最大长度限于56位
     * @return array
     */
    public function waybill($order){
        if(!$order){
            $this->error('客户订单号不能为空');
        }
        $data = [
            'orderId' => $order,
        ];
        return $this->request('205', $data);
    }
}
