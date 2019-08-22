<?php
namespace Jormin\SFExpress;

/**
 * Class BaseObject
 * @package Jormin\Qiniu
 */
class BaseObject{

    public static $subUrls = [
        '200' => [
            'name' => '快速下单',
            'url' => '/order/'
        ],
        '203' => [
            'name' => '订单查询',
            'url' => '/order/query/'
        ],
        '204' => [
            'name' => '订单筛选',
            'url' => '/filter/'
        ],
        '501' => [
            'name' => '路由查询',
            'url' => '/route/query/'
        ],
        '503' => [
            'name' => '路由增量信息申请接口',
            'url' => '/route/push/apply/'
        ],
        '504' => [
            'name' => '路由增量查询',
            'url' => '/route/inc/query/'
        ],
        '205' => [
            'name' => '电子运单图片下载',
            'url' => '/waybill/image/'
        ],
        '250' => [
            'name' => '基础服务查询',
            'url' => '/product/basic/query/'
        ],
        '251' => [
            'name' => '附加服务查询',
            'url' => '/product/additional/query/'
        ],
        '301' => [
            'name' => '申请访问令牌',
            'url' => '/security/access_token/'
        ],
        '300' => [
            'name' => '查询访问令牌',
            'url' => '/security/access_token/query/'
        ],
        '302' => [
            'name' => '刷新访问令牌',
            'url' => '/security/refresh_token/'
        ],
        '600' => [
            'name' => '供应商',
            'url' => '/wms/vendor/'
        ],
        '601' => [
            'name' => '商品目录',
            'url' => '/wms/goods/'
        ],
        '602' => [
            'name' => '入库单（采购订单）',
            'url' => '/wms/purchase/order/'
        ],
        '607' => [
            'name' => '库存查询',
            'url' => '/wms/inventory/query/'
        ],
        '604' => [
            'name' => '入库单状态查询',
            'url' => '/wms/purchase/order/status/query/'
        ],
        '610' => [
            'name' => '入库单明细推送',
            'url' => '/wms/purchase/order/push/'
        ],
        '603' => [
            'name' => '出库单（销售订单）',
            'url' => '/wms/sales/order/'
        ],
        '605' => [
            'name' => '出库单明细查询',
            'url' => '/wms/sales/order/query/'
        ],
        '606' => [
            'name' => '出库单状态查询',
            'url' => '/wms/sales/order/status/query/'
        ],
        '609' => [
            'name' => '出库单明细推送',
            'url' => '/wms/sales/order/push/'
        ],
        '611' => [
            'name' => '出库单发票',
            'url' => '/wms/sales/order/invoice/'
        ],
        '608' => [
            'name' => '取消订单（销售订单）',
            'url' => '/wms/sales/order/cancel/'
        ],
    ];

    /**
     * 安全类接口
     *
     * @var array
     */
    public static $authTransTypes = ['301', '300', '302'];

    /**
     * 响应码
     *
     * @var array
     */
    public static $responseCode = [
        'EX_CODE_OPENAPI_0100'	=> '输入校验异常',
        'EX_CODE_OPENAPI_0101'	=> 'APPID 不存在',
        'EX_CODE_OPENAPI_0102'	=> 'APPKEY 不存在',
        'EX_CODE_OPENAPI_0103'	=> '访问令牌不存在',
        'EX_CODE_OPENAPI_0104'	=> '更新令牌不存在',
        'EX_CODE_OPENAPI_0105'	=> '访问令牌过期',
        'EX_CODE_OPENAPI_0106'	=> '更新令牌过期',
        'EX_CODE_OPENAPI_0200'	=> '操作成功',
        'EX_CODE_OPENAPI_0400'	=> '操作失败',
        'EX_CODE_OPENAPI_0420'	=> '不存在该订单号对应的订单信息',
        'EX_CODE_OPENAPI_0500'	=> '系统异常',
        'EX_CODE_OPENAPI_0212'	=> '无效帐户状态',
        'EX_CODE_OPENAPI_0300'	=> '验证输入参数异常',
        'EX_CODE_OPENAPI_0403'	=> '获取用户权限失败',
        'EX_CODE_OPENAPI_0404'	=> '重复下单',
        'EX_CODE_OPENAPI_0405'	=> '查询非客户所有订单',
        'EX_CODE_OPENAPI_0406'	=> '生产电子运单图片失败',
        'EX_CODE_OPENAPI_0407'	=> '未有数据生成电子运单',
        'EX_CODE_OPENAPI_0425'	=> '订单信息有误',
        'EX_CODE_OPENAPI_0426'	=> '调用地址筛单异常',
        'EX_CODE_OPENAPI_0444'	=> '查询路由信息不存在',
        'EX_CODE_OPENAPI_0445'	=> '该订单号非本系统的订单或者运单号不存在',
        'EX_CODE_OPENAPI_0446'	=> '该订单号尚未申请路由增量接口'
    ];

    /**
     * 顺丰 AppID 和 AppKey
     *
     * @var
     */
    protected $appID, $appKey;

    /**
     * 顺丰 $accessToken 和 $refreshToken
     *
     * @var
     */
    protected $accessToken, $refreshToken;

    /**
     * 沙盒模式
     *
     * @var bool
     */
    protected $sandbox = false;

    /**
     * 正式接口地址
     *
     * @var string
     */
    private $url = 'https://open-prod.sf-express.com';

    /**
     * 沙盒环境接口地址
     *
     * @var string
     */
    private $sandboxUrl = 'https://open-sbox.sf-express.com';

    /**
     * 记录 Token 的文件路径
     *
     * @var string
     */
    private $tokenFile = 'access_token%s.json';

    /**
     * SFExpress constructor.
     * @param $appID
     * @param $appKey
     * @param bool $sandbox
     */
    public function __construct($appID, $appKey, $sandbox=false)
    {
        $this->appID = $appID;
        $this->appKey = $appKey;
        $this->sandbox = $sandbox;
        $this->tokenFile = sprintf($this->tokenFile, '_'.$appID);
    }

    /**
     * 发起请求
     *
     * @param $transType
     * @param array $data
     * @return array
     */
    public function request($transType, $data=[]){
        if(!in_array($transType, self::$authTransTypes)){
            $this->initToken();
        }
        $url = $this->makeRequestUrl($transType);
        $params = $this->makeRequestParams($transType, $data);
        $response = $this->http('POST', $url, $params);
        if(!$response){
            $return['msg'] = '接口访问异常';
            return $return;
        }
        $head = $response['head'];
        if(in_array($head['code'], ['EX_CODE_OPENAPI_0103', 'EX_CODE_OPENAPI_0104', 'EX_CODE_OPENAPI_0105', 'EX_CODE_OPENAPI_0106'])){
            $this->getToken();
            return $this->request($transType, $data);
        }
        if($head['code'] !== 'EX_CODE_OPENAPI_0200'){
            return $this->error($head['message'], $response);
        }
        return $this->success($head['message'], $response);
    }

    /**
     * 生成请求Url
     *
     * @param $transType
     * @return string
     */
    private function makeRequestUrl($transType){
        $urlData = self::$subUrls[$transType];
        $urlType = in_array($transType, self::$authTransTypes) ? 'public' : 'rest';
        $url = ($this->sandbox ? $this->sandboxUrl : $this->url).'/'.$urlType.'/v1.0'.$urlData['url'];
        if(!in_array($transType, ['301', '300'])){
            $url .= 'access_token/'.$this->accessToken.'/';
            if($transType == '302'){
                $url .= 'refresh_token/'.$this->refreshToken.'/';
            }
        }
        $url .= 'sf_appid/'.$this->appID.'/sf_appkey/'.$this->appKey;
        return $url;
    }

    /**
     * 生成请求参数
     *
     * @param $transType
     * @param null $data
     * @return array
     */
    private function makeRequestParams($transType, $data=null){
        $params = [];
        if($transType === '302'){
            $params['head'] = [
                'accessToken' => $this->accessToken,
                'refreshToken' => $this->refreshToken,
            ];
        }else{
            $params['head'] = [
                'transType' => $transType,
                'transMessageId' => date('Ymd').time(),
            ];
        }
        if(!in_array($transType, self::$authTransTypes)){
            foreach ($data as $key => $value){
                !is_null($value) && $params['body'][$key] = $value;
            }
        }
        return $params;
    }

    /**
     * 初始化Token
     */
    protected function initToken(){
        $tokenFile = dirname(__FILE__).'/'.$this->tokenFile;
        $limitTime = time();
        if(file_exists($tokenFile)){
            $tokenData = json_decode(file_get_contents($tokenFile), true);
            $this->accessToken = $tokenData['access']['token'];
            $this->refreshToken = $tokenData['refresh']['token'];
            if($tokenData['access']['expireTime'] <= $limitTime){
                if($tokenData['refresh']['expireTime'] <= $limitTime){
                    $this->getToken();
                }else{
                    $this->refreshToken();
                }
            }
        }else{
            $this->getToken();
        }
    }

    /**
     * 生成Token
     */
    private function getToken(){
        $response = $this->request('301', []);
        if($response['success']){
            $body = $response['data']['body'];
            $this->storeToken($body['accessToken'], $body['refreshToken']);
        }else{
            throw new \Exception('初始化Token失败，失败原因：'.$response['message']);
        }

    }

    /**
     * 刷新Token
     */
    private function refreshToken(){
        $response = $this->request('302', []);
        if($response['success']){
            $body = $response['data']['body'];
            $this->storeToken($body['accessToken'], $body['refreshToken'], true);
        }else{
            throw new \Exception('刷新Token失败，失败原因：'.$response['message']);
        }
    }

    /**
     * 存储Token信息
     *
     * @param $accessToken
     * @param $refreshToken
     * @param bool $update
     */
    private function storeToken($accessToken, $refreshToken, $update = false){
        $this->accessToken = $accessToken;
        $this->refreshToken = $refreshToken;
        $tokenFile = dirname(__FILE__).'/'.$this->tokenFile;
        $currentTime = time();
        if($update){
            $json = file_get_contents($tokenFile);
            $tokenData = json_decode($json, true);
            $refreshExpireTime = $tokenData['refresh']['expireTime'];
        }else{
            $refreshExpireTime = $currentTime + 3600*24;
        }
        $tokenData = [
            'access' => [
                'token' => $accessToken,
                'expireTime' => $currentTime + 3600
            ],
            'refresh' => [
                'token' => $refreshToken,
                'expireTime' => $refreshExpireTime
            ],
        ];
        file_put_contents($tokenFile,json_encode($tokenData));
    }

    /**
     * 发送报文
     *
     * @param $method
     * @param $url
     * @param $params
     * @return array
     */
    public function http($method, $url, $params){
        if(strtoupper($method) == 'GET'){
            $args = [];
            foreach ($params as $key => $value){
                $args[] = $key.'='.$value;
            }
            $url .= '?'.implode('&', $args);
        }
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_CUSTOMREQUEST, strtoupper($method));
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_USERAGENT, 'Mozilla/5.0 (compatible; MSIE 10.0; Windows NT 6.1; Trident/6.0)');
        curl_setopt($curl, CURLOPT_HEADER, 0);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($curl, CURLOPT_HTTPHEADER, array('Content-Type:application/json'));
        if(strtoupper($method) == 'POST'){
            curl_setopt($curl, CURLOPT_POST, 1);
            curl_setopt($curl, CURLOPT_POSTFIELDS, json_encode($params));
        }
        $data = curl_exec($curl);
        if (curl_errno($curl)) {
            return curl_error($curl);
        }
        curl_close($curl);
        return json_decode($data, true);
    }

    /**
     * 失败
     *
     * @param $message
     * @param null $data
     * @return array
     */
    public function error($message, $data=null){
        is_object($data) && $data = (array)$data;
        $return = ['success' => false, 'message' => $message, 'data'=>$data];
        return $return;
    }

    /**
     * 成功
     *
     * @param $message
     * @param null $data
     * @return array
     */
    public function success($message, $data=null){
        is_object($data) && $data = (array)$data;
        $return = ['success' => true, 'message' => $message, 'data'=>$data];
        return $return;
    }
}