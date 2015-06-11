<?php
/**
 * Created by PhpStorm.
 * User: wme
 * Date: 02/03/15
 * Time: 9.07
 */ 
class Eg_SaveInvoice_Helper_Data extends Mage_Core_Helper_Abstract
{

    public function formatInvoiceData($InvoiceData)
    {
        return Mage::getModel('core/date')->date('Ymd', strtotime($InvoiceData));
    }

    public function getDefaultStoreID()
    {
        $defaultStoreId = Mage::app()
            ->getWebsite(true)
            ->getDefaultGroup()
            ->getDefaultStoreId();
        return $defaultStoreId;
    }

    protected function getInvoiceEntityTypeId() {
            $entityType = Mage::getModel('eav/config')->getEntityType('invoice');
            return $entityType->getEntityTypeId();
    }

    public function getInvoiceIncrementPrefix() {
            $entityStoreConfig = Mage::getModel('eav/entity_store')
                ->loadByEntityStore($this->getInvoiceEntityTypeId(), $this->getDefaultStoreID());
            return $entityStoreConfig->getIncrementPrefix();

    }

}