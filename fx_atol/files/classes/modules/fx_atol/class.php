<?php
class fx_atol extends def_module {

	public function __construct() {
		parent::__construct();
        if(cmsController::getInstance()->getCurrentMode() == "admin") {
            $this->__loadLib("__admin.php");
            $this->__implement("__fx_atol");
        }else{
            require_once "./classes/modules/fx_atol/classes/foxiAtol.php";
        }
	}
    
    public function testDocumentRequest($order){
        if(is_numeric($order)){
            $order = order::get($order);
        }
        if(!$order) return array('error' => 'Передан неверный заказ');
        return foxiAtol::getInstance()->documentRequest($order);
    }

    public function documentRequestEvent(iUmiEventPoint $event){
        $mode = $event->getMode();
        if ($mode == 'after') {
            $oldStatusId = $event->getParam('old-status-id');
            $newStatusId = $event->getParam('new-status-id');
            if ($oldStatusId != $newStatusId) {
                $orderStatus = order::getCodeByStatus($newStatusId);
                $order = $event->getRef('order');
                if ($orderStatus == 'accepted' && !$order->getValue('uuid')) {
                    foxiAtol::getInstance()->documentRequest($order);
                }
            }
        }
    }


    
};
?>