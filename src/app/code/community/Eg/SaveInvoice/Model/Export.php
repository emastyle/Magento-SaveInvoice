<?php

/**
 * Class Eg_SaveInvoice_Model_Export
 */
class Eg_SaveInvoice_Model_Export extends Mage_Core_Model_Abstract
{
    const XML_PATH_SAVE_INVOICE_ENABLED              = 'sales/saveinvoice/enabled';
    const XML_PATH_PDF_EXPORT_FOLDER                 = 'sales/saveinvoice/export_folder';

    const INVOICE_EXTENSION                          = '.pdf';

    protected function getPdfExportFolder()
    {
        return $this->checkPath(Mage::getStoreConfig(self::XML_PATH_PDF_EXPORT_FOLDER)) . DS;
    }

    protected function isSavePdfInvoiceEnabled()
    {
        return Mage::getStoreConfig(self::XML_PATH_SAVE_INVOICE_ENABLED);
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

        if (!$this->isSavePdfInvoiceEnabled())
            return;

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