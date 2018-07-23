基于顺丰开放平台官方SDK的扩展包

## 安装

``` bash
$ composer require jormin/sf-express -vvv
```

## 通用配置

 - appID: 顺丰开放平台App ID
 - appKey: 顺丰开放平台App Key

## 通用响应

| 参数  | 类型  | 是否必须  | 描述  |
| ------------ | ------------ | ------------ | ------------ |
| success | bool | 是 | false：操作失败 true:操作成功 |
| message | string | 是 | 结果说明 |
| data | array | 否 | 返回数据 |


## 使用

### 生成顺丰对象
> 默认使用正式接口，调试阶段，第三个参数可传 true 使用沙盒接口。
``` php
$appID = 'your app id';
$appKey = 'your app key';
$sfExpress = new \Jormin\SFExpress\SFExpress($appID, $appKey, [$sandbox=false]);
```

### 下单

```php
/**
 * 下单
 *
 * @param string $order 客户订单号，最大长度限于56位
 * @param string $custId 顺丰月结卡号10位数字
 * @param array $consigneeInfo 收件方信息，数组字段参考最下方【顺丰接口文档】
 * @param array $cargoInfo 货物信息，数组字段参考最下方【顺丰接口文档】
 * @param array $deliverInfo 寄件方信息，数组字段参考最下方【顺丰接口文档】
 * @param string $remark 备注，最大长度30个汉字
 * @param int $expressType 快件产品类别 [1:标准快递(默认) 2:顺丰特惠 3:电商特惠 5:顺丰次晨 6:顺丰即日 7:电商速配 15:生鲜速配]
 * @param int $payMethod 付款方式 [1:寄方付(默认) 2:收方付【暂不支持】3:第三方付]
 * @param int $isDocall 是否通知收派员上门取件 [1:通知 0:不通知(默认)]
 * @param int $isGenBillno 是否申请运单号 [1:申请(默认) 0:不申请]
 * @param int $isGenEletricPic 是否生成电子运单图片 [1:生成(默认) 0:不生成]
 * @param string $payArea 月结卡号对应的网点，如果付款方式为第三方支付，则必填
 * @param string $sendStartTime 要求上门取件开始时间，格式：YYYY-MM-DDHH24:MM:SS，示例：2012-7-30 09:30:00，默认值为系统收到订单的系统时间
 * @param int $needReturnTrackingNo 是否需要签回单号 [1:需要 0:不需要(默认)]
 * @param array $addedServices 增值服务（注意字段名称必须为英文字母大写），数组字段参考最下方【顺丰接口文档】
 * @return array
 */
$sfExpress->createOrder($order, $custId, $consigneeInfo, $cargoInfo, [$deliverInfo=null, $remark=null, $expressType=1, $payMethod=1, $isDocall=0, $isGenBillno=1, $isGenEletricPic=1, $payArea=null, $sendStartTime=null, $needReturnTrackingNo=0, $addedServices=null]);
```

### 订单查询

```php
/**
 * 订单查询
 *
 * @param string $order 客户订单号，最大长度限于56位
 * @return array
 */
$sfExpress->orderQuery($order);
```

### 订单筛选，判断是否可派收

```php
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
$sfExpress->orderFilter($consigneeAddress, $consigneeProvince, $consigneeCity, $consigneeCounty, [$deliverAddress=null, $deliverProvince=null, $deliverCity=null, $deliverCounty=null, $order=null, $consigneeTel=null, $deliverTel=null, $deliverCustId=null, $consigneeCountry='中国', $deliverCountry='中国']);
```

### 路由查询

```php
/**
 * 路由查询
 *
 * @param string $trackingNumber 查询号（订单号/运单号）,如果有多个单号，以英文逗号分隔,如”755123456789, 755123456788, 755123456787”批量查询中，最多不能超过5个单号
 * @param int $trackingType 查询类别 [1:根据运单号查询【只支持查询客户在本系统下的订单对应的顺丰运单号】（默认） 2:根据订单号查询]
 * @param int $methodType 查询方法选择 [1:标准查询(默认) 2:定制查询【暂不支持】]
 * @return array
 */
$sfExpress->routeQuery($trackingNumber, [$trackingType=1, $methodType=1]);
```

### 路由增量信息推送申请

```php
/**
 * 路由增量信息推送申请
 *
 * @param string $order 客户订单号，最大长度限于56位
 * @return array
 */
$sfExpress->routePushApply($order);
```

### 路由增量查询

```php
/**
 * 路由增量查询
 *
 * @param string $order 客户订单号，最大长度限于56位
 * @return array
 */
$sfExpress->routeIncQuery($order);
```

### 基础服务查询

```php
/**
 * 基础服务查询
 *
 * @return array
 */
$sfExpress->productBasicQuery();
```

### 附加服务查询

```php
/**
 * 附加服务查询
 *
 * @return array
 */
$sfExpress->productAdditionalQuery();
```

### 电子运单图片下载
> 支持存储图片存储到七牛，需要配置七牛的AccessKey\SecretKey\Bucket\Domain
```php
/**
 * 电子运单图片下载
 *
 * @param string $order 客户订单号，最大长度限于56位
 * @param string $path 图片保存地址，保存在本地时代表绝对路径，保存到七牛时提供保存的Key，如果保存到七牛时没有提供path，则默认用【/sf-express/waybill/{订单号}.png】保存
 * @param bool $qiniu 是否上传到七牛
 * @param array $qiniuConfig 七牛配置，包含四项： accessKey secretKey bucket domain
 * @return array
 */
$sfExpress->waybillImage($order, $path, [$qiniu=false, $qiniuConfig=[]]);
```

## 参考文档

1. [顺丰接口文档](https://open.sf-express.com/doc/sf_openapi_document_V1.pdf)

2. [顺丰Api测试工具](https://open.sf-express.com/apitools/apitools.html)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
