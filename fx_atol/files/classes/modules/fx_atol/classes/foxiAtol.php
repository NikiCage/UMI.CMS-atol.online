<?php
define('ATOL_LIB_PATH','./classes/modules/fx_atol/classes/');
interface iFoxiAtol {
    public function documentRequest(order $order);
};
class foxiAtol extends singleton implements iFoxiAtol{
    public function __construct() {
        self::include_lib();
    }

    public static function getInstance($c = NULL) {
        return parent::getInstance(__CLASS__);
    }

    private static function include_lib(){
        require_once ATOL_LIB_PATH."SdkException.php";
        require_once ATOL_LIB_PATH."clients/iClient.php";
        require_once ATOL_LIB_PATH."clients/PostClient.php";
        require_once ATOL_LIB_PATH."data_objects/BaseDataObject.php";
        require_once ATOL_LIB_PATH."data_objects/ReceiptPosition.php";
        require_once ATOL_LIB_PATH."services/BaseServiceRequest.php";
        require_once ATOL_LIB_PATH."services/BaseServiceResponse.php";
        require_once ATOL_LIB_PATH."services/CreateDocumentRequest.php";
        require_once ATOL_LIB_PATH."services/CreateDocumentRequest.php";
        require_once ATOL_LIB_PATH."services/GetStatusRequest.php";
        require_once ATOL_LIB_PATH."services/GetStatusResponse.php";
        require_once ATOL_LIB_PATH."services/GetTokenRequest.php";
        require_once ATOL_LIB_PATH."services/GetTokenResponse.php";
    }

    private static function getToken($login, $password){
        $client = new Platron\Atol\clients\PostClient();
        $tokenService = new Platron\Atol\services\GetTokenRequest($login,$password);
        $tokenResponse = new Platron\Atol\services\GetTokenResponse($client->sendRequest($tokenService));

        $error = $tokenResponse->getErrorDescription();
        if($error){
            return array('error'=>$error);
        }else{
            return $tokenResponse->token;
        }
    }

    public function documentRequest(order $order){
        if(!$order) return array('error' => 'Передан неверный заказ');

        $objects = umiObjectsCollection::getInstance();
        $regedit = regedit::getInstance();

        $enable = $regedit->getVal('//modules/fx_atol/enable');
        if(!$enable) return array('error' => 'Передача чека выключена');

        $login = $regedit->getVal('//modules/fx_atol/login');
        $password = $regedit->getVal('//modules/fx_atol/password');
        if(!$login || !$password) return array('error' => 'Не указаны данные для интеграции');

        $token = self::getToken($login, $password);
        if(is_array($token)) return $token;

        $payment_id = $order->getValue('payment_id');
        $payment = $objects->getObject($payment_id);
        if($payment->getTypeGUID() == 'emarket-payment-802'){
            $payment_code = 0; //наличными
        } else {
            $payment_code = 1;
        }
        $customer_id = $order->getCustomerId();
        $customer = $objects->getObject($customer_id);
        $email = $customer->getValue('e-mail');
        if(!$email)$email = $customer->getValue('email');

        $groupCode = $regedit->getVal('//modules/fx_atol/groupCode');
        $inn = $regedit->getVal('//modules/fx_atol/inn');
        $address = $regedit->getVal('//modules/fx_atol/address');
        $sno = $regedit->getVal('//modules/fx_atol/sno');
        $def_tax = $regedit->getVal('//modules/fx_atol/def_tax');
        $def_tax = self::getTax($def_tax);
        $client = new Platron\Atol\clients\PostClient();

        $createDocumentService = (new Platron\Atol\services\CreateDocumentRequest($token))
            ->addCustomerEmail($email)
            ->addCustomerPhone('')
            ->addGroupCode($groupCode)
            ->addInn($inn)
            ->addExternalId($order->getId())
            ->addMerchantAddress($address)
            ->addOperationType(Platron\Atol\services\CreateDocumentRequest::OPERATION_TYPE_SELL)
            ->addPaymentType($payment_code)
            ->addSno($sno);

        $items = $order->getItems();
        $discount = 1;
        if($order->getActualPrice() - $order->getDeliveryPrice() != $order->getOriginalPrice()){
            $discount = ($order->getActualPrice() - $order->getDeliveryPrice()) / $order->getOriginalPrice();
        }
        foreach ($items as $item){
            $element = $item->getItemElement();
            if($element && $element->getValue('atol_tax')){
                $tax = self::getTax($element->getValue('atol_tax'));
            } else {
                $tax = $def_tax;
            }
            $new_item = new Platron\Atol\data_objects\ReceiptPosition($item->getName(),
                number_format($item->getTotalActualPrice() / $item->getAmount() * $discount, 2, '.', ''), $item->getAmount(), $tax);
            $createDocumentService->addReceiptPosition($new_item);
        }
        if($order->getDeliveryPrice()){
            $new_item = new Platron\Atol\data_objects\ReceiptPosition('Доставка', number_format($order->getDeliveryPrice(), 2, '.', ''), 1, 'none');
            $createDocumentService->addReceiptPosition($new_item);
        }
        $answer = $client->sendRequest($createDocumentService);
//        $createDocumentResponse = new Platron\Atol\services\CreateDocumentResponse($client->sendRequest($createDocumentService));

        $error = $answer->error->text;
        if ($error) {
            return array('error' => $error);
        } else {
            $order->setValue('uuid', $answer->uuid);
            $order->commit();
            return $answer->uuid;
        }
    }

    private static function getTax($tax){
        if(is_numeric($tax)){
            $tax = umiObjectsCollection::getInstance()->getObject($tax);
        }
        if(!$tax instanceof umiObject) return 'none';
        return str_replace('emarket-tax-','',$tax->getGUID());
    }
}