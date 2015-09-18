<?php

class Meanbee_Healthcheck_Model_Cron
{
    public function runChecks()
    {
        if (!Mage::helper('meanbee_healthcheck/config')->isEnabled()) {
            $this->log("Healthcheck is not enabled.");
            return;
        }

        $this->log("Fetching all checks..");

        $checks = Mage::getConfig()->getXpath('/config/meanbee_healthcheck/checks/*');

        foreach ($checks as $check) {
            /** @var Mage_Core_Model_Config_Element $check */
            $check_name = $check->getName();
            $check_class = (string) $check->class;

            $this->log(sprintf("%s -> %s", $check_name, $check_class));

            /** @var Meanbee_Healthcheck_Model_CheckInterface $check_instance */
            $check_instance = Mage::getModel($check_class);

            if (!$check_instance) {
                $this->log(sprintf("Unable to instantiate for %s", $check_class), Zend_Log::ERR);
                continue;
            }

            if (!($check_instance instanceof Meanbee_Healthcheck_Model_CheckInterface)) {
                $this->log(sprintf("Check class %s does not implement Meanbee_Healthcheck_Model_CheckInterface", $check_class), Zend_Log::ERR);
                continue;
            }

            try {
                $this->log(sprintf("%s: Starting check..", $check_name));
                $check_instance->test();
                $this->log(sprintf("%s: Completed successfully.", $check_name));
            } catch (Exception $e) {
                $this->log(sprintf("%s: Error during check (%s)", $check_name, $e->getMessage()), Zend_Log::WARN);
                Mage::logException($e);
            }
        }

        $this->log("Complete.");
    }

    protected function log($message, $severity = Zend_Log::DEBUG)
    {
        Mage::helper('meanbee_healthcheck')->log(sprintf("%s: %s",  get_class($this), $message), $severity);
    }
}
