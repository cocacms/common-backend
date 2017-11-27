<?php
/**
 * Author:     Rojer
 * Mail:       rojerchen@qq.com
 */

namespace App\Services\Alipay;

use AopClient as Client;
use Illuminate\Log\Writer;
use Monolog\Logger;


/**
 * 提现
 */

//$alipay = app(AliClient::class);
//$request = new \AlipayFundTransToaccountTransferRequest ();
//$request->setBizContent("{" .
//    "\"out_biz_no\":\"3142321423432\"," .
//    "\"payee_type\":\"ALIPAY_LOGONID\"," .
//    "\"payee_account\":\"sandbox_cilai_c@163.com\"," .
//    "\"amount\":\"12.23\"," .
//    "\"payer_show_name\":\"厦门空帆船科技有限公司\"," .
//    "\"payee_real_name\":\"sandbox_cilai_c\"," .
//    "\"remark\":\"提现\"" .
//    "}");
//$result = $alipay->execute ( $request );
//
//$responseNode = str_replace(".", "_", $request->getApiMethodName()) . "_response";
//$resultCode = $result->$responseNode->code;
//if(!empty($resultCode)&&$resultCode == 10000){
//    echo "成功";
//} else {
//    echo $result->$responseNode->sub_msg;
//}



/**
 * Class AliClient
 * @package App\Services\Alipay
 */

class AliClient extends Client
{

    /**
     * AliClient constructor.
     * @param $alipay_config
     * @throws \Exception
     */
    public function __construct($alipay_config)
    {

        if($alipay_config['sandbox']){
            $this->gatewayUrl = 'https://openapi.alipaydev.com/gateway.do';
        }

        $this->appId = $alipay_config['app_id'];
        $this->rsaPrivateKey = $alipay_config['merchant_private_key'];
        $this->alipayrsaPublicKey = $alipay_config['alipay_public_key'];
        $this->postCharset = $alipay_config['charset'];
        $this->signType = $alipay_config['sign_type'];

        if(empty($this->appId)||trim($this->appId)==""){
            throw new \Exception("appid should not be NULL!");
        }
        if(empty($this->rsaPrivateKey)||trim($this->rsaPrivateKey)==""){
            throw new \Exception("private_key should not be NULL!");
        }
        if(empty($this->alipayrsaPublicKey)||trim($this->alipayrsaPublicKey)==""){
            throw new \Exception("alipay_public_key should not be NULL!");
        }

    }

    public function sandboxMode(){
        $this->gatewayUrl = 'https://openapi.alipaydev.com/gateway.do';
    }

    protected function logCommunicationError($apiName, $requestUrl, $errorCode, $responseTxt) {
        $type = 'error';
        $localIp = request()->getClientIp();
        $logger = new Writer(new Logger($type));
        $logger->useDailyFiles(storage_path().'/logs/alipay/'. $type .'.log', 30);
        $logData = array(
            date("Y-m-d H:i:s"),
            $apiName,
            $this->appId,
            $localIp,
            PHP_OS,
            $this->alipaySdkVersion,
            $requestUrl,
            $errorCode,
            str_replace("\n", "", $responseTxt)
        );
        $logger->error(implode("\n",$logData));
    }


    public function exec($paramsArray) {
        if (!isset ($paramsArray["method"])) {
            trigger_error("No api name passed");
        }
        $method = str_replace(".","_",$paramsArray["method"]);
        $requestClassName = ucfirst(camel_case(substr($method, 7))) . "Request";
        if (!class_exists($requestClassName)) {
            trigger_error("No such api: " . $paramsArray["method"]);
        }

        $session = isset ($paramsArray["session"]) ? $paramsArray["session"] : null;

        $req = new $requestClassName;
        foreach ($paramsArray as $paraKey => $paraValue) {
            $setterMethodName = studly_case($paraKey);
            $setterMethodName = "set" . $setterMethodName;
            if (method_exists($req, $setterMethodName)) {
                $req->$setterMethodName ($paraValue);
            }
        }
        return $this->execute($req, $session);
    }

}