<?php
require_once 'abstract.php';

class Mage_Shell_Runtime extends Mage_Shell_Abstract
{

    /**
     * Additional initialize instruction
     *
     * @return Mage_Shell_Abstract
     */
    protected function _construct()
    {
        parent::_construct();
        Mage::app()->loadArea(Mage_Core_Model_App_Area::AREA_ADMINHTML);
        Mage::app()->setCurrentStore(1);
        return $this;
    }

    public function run()
    {
        if ($this->getArg('increment_id') && $this->getArg('increment_id') != '' && $this->getArg('increment_id') != ' ') {

            $invoiceIncrementId = $this->getArg('increment_id');
            Mage::helper('eg_saveinvoice')->_log('Invoice saving by shell script was called for invoice increment_id ' . $invoiceIncrementId);
            $saveInvoiceModel = Mage::getModel('eg_saveinvoice/export');

            $_invoice = Mage::getModel('sales/order_invoice')->loadByIncrementId($invoiceIncrementId);

            /** got to shop root directory **/
            chdir('../');
            $saveInvoiceModel->_saveInvoicePdf($_invoice);

            echo 'Invoice ' . $invoiceIncrementId . ' created' . PHP_EOL;
        } else {
            echo $this->usageHelp();
        }

    }

    /**
     * Retrieve Usage Help Message
     *
     */
    public function usageHelp()
    {
        return <<<USAGE
Usage:  php -f saveinvoice.php -- [parameters]

  --increment_id <invoice increment id>     Save PDF invoice on filesystem passing invoice increment_id
  help                                      This help


USAGE;
    }
}

$shell = new Mage_Shell_Runtime();
$shell->run();



