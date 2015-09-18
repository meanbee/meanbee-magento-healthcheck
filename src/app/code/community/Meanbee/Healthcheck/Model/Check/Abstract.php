<?php

abstract class Meanbee_Healthcheck_Model_Check_Abstract implements Meanbee_Healthcheck_Model_CheckInterface
{
    /**
     * @param $message
     * @param int $severity
     */
    protected function log($message, $severity = Zend_Log::DEBUG)
    {
        Mage::helper('meanbee_healthcheck')->log(sprintf("%s: %s",  get_class($this), $message), $severity);
    }
}
