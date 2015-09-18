<?php

class Meanbee_Healthcheck_Helper_Alert extends Mage_Core_Helper_Abstract
{
    /**
     * @param Meanbee_Healthcheck_Model_Alert $alert
     */
    public function notify(Meanbee_Healthcheck_Model_Alert $alert)
    {
        if (!Mage::helper('meanbee_healthcheck/config')->isEnabled()) {
            return;
        }

        $severity_string = $alert->getSeverity() ? $this->getSeverityAsString($alert->getSeverity()) : '';
        $stack_trace_string = $alert->getStackTrace() ? $this->getStackTraceAsString($alert->getStackTrace()) : '';

        $email = Mage::getModel('core/email_template')->loadDefault('meanbee_healthcheck_alert');

        $email->setSenderEmail('healthcheck');
        $email->setSenderName('Meanbee Healthcheck');

        $email->setTemplateSubject(sprintf("Healthcheck Alert: %s from %s", $severity_string, $alert->getName()));

        $email_variables = array(
            'message'     => $alert->getMessage(),
            'severity'    => $severity_string,
            'stack_trace' => $stack_trace_string
        );

        $notification_email_addresses = Mage::helper('meanbee_healthcheck/config')->getNotificationEmailAddresses();

        if (count($notification_email_addresses) > 0) {
            foreach ($notification_email_addresses as $notification_email_address) {
                $email->send($notification_email_address, $notification_email_address, $email_variables);
            }
        }
    }

    /**
     * @param $severity
     *
     * @return string
     */
    protected function getSeverityAsString($severity)
    {
        switch ($severity) {
            case Zend_Log::EMERG:
                return 'Emergency';
            case Zend_Log::ALERT:
                return 'Alert';
            case Zend_Log::CRIT:
                return 'Critical';
            case Zend_Log::ERR:
                return 'Error';
            case Zend_Log::WARN:
                return 'Warning';
            case Zend_Log::NOTICE:
                return 'Notice';
            case Zend_Log::INFO:
                return 'Informational';
            case Zend_Log::DEBUG:
                return 'Debug';
            default:
                return sprintf('Unknown (%s)', $severity);
        }
    }

    /**
     * @param array $stack_trace
     * @see https://gist.githubusercontent.com/jhurliman/3147770/raw/38e631307abc21435dd8f8b8416eca60bdc0d3d3/stacktrace.php
     * @return string
     */
    protected function getStackTraceAsString(array $stack_trace)
    {
        $output = 'Stack trace:' . PHP_EOL;

        $stackLen = count($stack_trace);

        for ($i = 1; $i < $stackLen; $i++) {
            $entry = $stack_trace[$i];

            $func = $entry['function'] . '(';
            $argsLen = count($entry['args']);
            for ($j = 0; $j < $argsLen; $j++) {
                $func .= $entry['args'][$j];
                if ($j < $argsLen - 1) $func .= ', ';
            }
            $func .= ')';

            $output .= '#' . ($i - 1) . ' ' . $entry['file'] . ':' . $entry['line'] . ' - ' . $func . PHP_EOL;
        }

        return $output;
    }
}
