<?php

class Eg_SaveInvoice_Model_Observer
{
    public function saveInvoice(Varien_Event_Observer $observer)
    {
        $_export = Mage::getModel('eg_saveinvoice/export');

        $_event = $observer->getEvent();
        $_invoice = $_event->getInvoice();

        Mage::log('Eg_SaveInvoice_Model_Observer :: calling');

        $_export->_saveInvoicePdf($_invoice);
    }
}