<?php

/**
 * Class Eg_SaveInvoice_Model_Observer
 */
class Eg_SaveInvoice_Model_Observer
{
    public function saveInvoice(Varien_Event_Observer $observer)
    {
        $_export = Mage::getModel('eg_saveinvoice/export');

        $_event = $observer->getEvent();
        $_invoice = $_event->getInvoice();

        Mage::helper('eg_saveinvoice')->_log('Eg_SaveInvoice_Model_Observer :: called');

        $_export->_saveInvoicePdf($_invoice);
    }
}