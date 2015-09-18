<?php

/**
 * Class Meanbee_Healthcheck_Model_Alert
 *
 * @method $this setName(string $name)
 * @method string getName()
 * @method $this setMessage(string $message)
 * @method string getMessage()
 * @method $this setSeverity(int $severity)
 * @method int getSeverity()
 * @method $this setStackTrace(array $stack_trace)
 * @method array getStackTrace()
 */
class Meanbee_Healthcheck_Model_Alert extends Mage_Core_Model_Abstract
{

    public function save()
    {
        throw new Exception(sprintf("%s is not a database backed model, %s not allowed.", __CLASS__, __METHOD__));
    }

    public function load()
    {
        throw new Exception(sprintf("%s is not a database backed model, %s not allowed.", __CLASS__, __METHOD__));
    }

    public function delete()
    {
        throw new Exception(sprintf("%s is not a database backed model, %s not allowed.", __CLASS__, __METHOD__));
    }
}
