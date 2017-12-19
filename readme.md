# Proactive Magento Health Checks

[![Build Status](https://travis-ci.org/meanbee/meanbee-magento-healthcheck.svg)](https://travis-ci.org/meanbee/meanbee-magento-healthcheck)

There are often metrics in a project that dictate the health of an online store, some examples:

* Has there been an order placed in the last hour?
* Are there any orphaned Sage Pay transactions?
* Is the reindexing process stuck?

This extension provides a framework for the implementation of proactive Magento 'health checks', run every five minutes, with email notifications to designated contacts if a problem occurs.

# Installation

    modman clone git@github.com:meanbee/meanbee-magento-healthcheck.git
    modman deploy meanbee-magento-healthcheck

# Architecture

* A check is an implementation of `Meanbee_Healthcheck_Model_CheckInterface`
* Checks are registered with healthcheck through XML configuration
* Checks are executed every five minutes through a centralised healthcheck cron
* Checks are responsibile for building an alert (`Meanbee_Healthcheck_Model_Alert`) and passing it through to the notify method (`Meanbee_Healthcheck_Helper_Alert::notify`)

# Usage

Documentation below describes how a developer might go about implementing a check and plugging it into healthcheck.

## Implementing a check

A check is a model that implements an interface: `Meanbee_Healthcheck_Model_CheckInterface`.  An abstract check class is provided with useful utility methods in `Meanbee_Healthcheck_Model_Check_Abstract`.

A check must implement a `check` method.  Inside of the `check` method the developer must implement their test logic and notify with an alert if appropriate.

The following example creates an `alert` and notifies the administrators about the alert.

```php
    class Meanbee_HealthcheckExample_Model_Check_Test extends Meanbee_Healthcheck_Model_Check_Abstract
    {
        public function check()
        {
            $this->log("Starting check..");

            /** @var Meanbee_Healthcheck_Model_Alert $alert */
            $alert = Mage::getModel('meanbee_healthcheck/alert');
            $alert
                ->setName(__CLASS__)
                ->setSeverity(Zend_Log::DEBUG)
                ->setMessage("This is a test check")
                ->setStackTrace(debug_backtrace());

            Mage::helper('meanbee_healthcheck/alert')->notify($alert);

            $this->log("Check complete.");
        }
    }
```

## Registering the check
    
Next we need to register the check with healthcheck.  This is done through configuration XML.

```xml
    <config>
        ...
        <meanbee_healthcheck>
            <checks>
                ...
                <my_example_test>
                    <class>Meanbee_HealthcheckExample_Model_Check_Test</class>
                </my_example_test>
                ...
            </checks>
        </meanbee_healthcheck>
        ...
    </config>
```

By registering of the checks through XML, the healthcheck extension can pick up any checks in third party extensions.


## Configuration

There are configuration options in `Advanced > Meanbee Healthcheck` to set email addresses for notifications.

# Roadmap

* Allow schedules to specified by individual checks
* Store alerts in a database table for providing an administration dashboard
