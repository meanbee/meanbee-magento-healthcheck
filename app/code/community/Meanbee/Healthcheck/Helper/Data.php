<?php
class Meanbee_Healthcheck_Helper_Data extends Mage_Core_Helper_Abstract
{
    /**
     * @param $message
     * @param int $severity
     */
    public function log($message, $severity = Zend_Log::DEBUG)
    {
        Mage::log($message, $severity, 'meanbee_healthcheck.log', true);
    }
}
