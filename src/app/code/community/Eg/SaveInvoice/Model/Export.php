<?php

/**
 * Magento EG_SaveInvoice_Model_Export
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Open Software License (OSL 3.0)
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://opensource.org/licenses/osl-3.0.php
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to license@magentocommerce.com so we can send you a copy immediately.
 *
 * DISCLAIMER
 *
 * Do not edit or add to this file if you wish to upgrade Magento to newer
 * versions in the future. If you wish to customize Magento for your
 * needs please refer to http://www.magentocommerce.com for more information.
 *
 * */
class Eg_SaveInvoice_Model_Export extends Mage_Core_Model_Abstract
{
    const SAVE_INVOICE_ENABLED              = 'sales/saveinvoice/enabled';
    const PDF_EXPORT_FOLDER                 = 'sales/saveinvoice/export_folder';

    const INVOICE_EXTENSION                 = '.pdf';

    //abstract public function saveInvoice(Varien_Event_Observer $observer);

    protected function getPdfExportFolder()
    {
        return $this->checkPath(Mage::getStoreConfig(self::PDF_EXPORT_FOLDER)) . DS;
    }

    protected function getInvoiceLogFolder()
    {
        return Mage::getStoreConfig(self::INVOICE_LOG_FOLDER);
    }

    protected function isSavePdfInvoiceEnabled()
    {
        return self::SAVE_INVOICE_ENABLED;
    }

    private function getIoAdapter()
    {
        if (is_null($this->_ioFile)) {
            $this->_ioFile = new Varien_Io_File();
        }
        return $this->_ioFile;
    }

    protected function checkPath($pathToCheck) {
        $ioAdapter = $this->getIoAdapter();
        try {
            $path = $ioAdapter->getCleanPath($pathToCheck);
            $ioAdapter->checkAndCreateFolder($path);
        }
        catch (Exception $e) {
            //Mage::exception($e->getMessage());
            Mage::log("*** SaveInvoice ERROR: " . $e->getMessage());
        }
        return $pathToCheck;
    }

    public function createInvoiceFileName($IncrementID) {
         return $IncrementID . self::INVOICE_EXTENSION;
    }

    public function _saveInvoicePdf($_invoice) {

        $orderId = $_invoice->getOrder()->getId();
        //$LogInvoiceData = $this->prepareInvoiceData($_invoice, $order);

        $orderModel = Mage::getModel('sales/order')->load($orderId);
        $invoices = $orderModel->getInvoiceCollection();
        $invoicesSet = array();
        foreach ($invoices as $_invoice) {
            array_push($invoicesSet, $_invoice);
        }
        try {
            $pdf = Mage::getModel('sales/order_pdf_invoice')->getPdf($invoicesSet);
            $pdf->save($this->getPdfExportFolder() . $this->createInvoiceFileName($_invoice->getIncrementId()), true);

           // $this->logInvoice($LogInvoiceData);

        } catch (Exception $ex) {
            Mage::log("saveInvoice:" . $ex->getMessage());
        }
    }

}