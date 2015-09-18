<?php

class Meanbee_Healthcheck_Helper_Config extends Mage_Core_Helper_Abstract
{
    const XML_ENABLED = 'meanbee_healthcheck/general/active';
    const XML_NOTIFICATION_EMAIL_ADDRESSES = 'meanbee_healthcheck/notification/email_addresses';

    /**
     * @return bool
     */
    public function isEnabled()
    {
        return Mage::getStoreConfigFlag(self::XML_ENABLED);
    }

    /**
     * @return array
     */
    public function getNotificationEmailAddresses()
    {
        $email_csv = trim(Mage::getStoreConfig(self::XML_NOTIFICATION_EMAIL_ADDRESSES));

        if ($email_csv == '') {
            return array();
        }

        $emails = explode(',', $email_csv);

        return array_filter(array_map('trim', $emails));
    }
}
