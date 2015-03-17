<?php


class Module_OrderTotals_Custom extends Erp_Core_Abstract
{
    public $moduleName = 'OrderTotals';

    protected $status = null;
    protected $configurations;
    protected $order = null;

    function __construct (Erp_Core_Order $orderObject = null)
    {

        parent::__construct();
        if (is_null($orderObject)) {
            $this->status = null;
        } else {
            $this->status = true;
            $this->setOrder($orderObject);
        }
    }

    /**
     * @return the $order
     */
    public function getOrder ()
    {
        return $this->order;
    }

    /**
     * @param $order the $order to set
     */
    public function setOrder ($order)
    {
        $this->order = $order;
    }

    public function getQuote ($classes = null)
    {
	if (!$this->status) {
	    return false;
	}

        $totalsCriteria = new Criteria();
        $totalsCriteria->add(OrdersTotalPeer::ORDERS_ID, $this->order->getOrderId());
        $totalsCriteria->add(OrdersTotalPeer::CLASS1, 'custom_subtotal');
        $orderTotals = OrdersTotalPeer::doSelectOne($totalsCriteria);
        if ($orderTotals) {
            
            $this->order->orderInfo['totals']['ot_customsubtotal'] = array(
                    'class' => 'ot_shipping' , 
                    'title' => 'Zonetable' , 
                    'value' => $orderTotals->getValue() , 
                    'text' => $orderTotals->getText() ,
                    'orders_id' => $orderTotals->getOrdersId()
            );
        }
    }


}
