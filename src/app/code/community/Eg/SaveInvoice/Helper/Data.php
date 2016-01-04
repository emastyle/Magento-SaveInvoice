<?php

/**
 * Class Eg_SaveInvoice_Helper_Data
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

    public function _log($msg) {
        Mage::log($msg, null, 'Eg_SaveInvoice.log', true);
    }

}