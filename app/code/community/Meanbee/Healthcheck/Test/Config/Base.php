<?php

class Meanbee_Healthcheck_Test_Config_Base extends EcomDev_PHPUnit_Test_Case_Config
{
    /**
     * @test
     */
    public function testModelAlias()
    {
        $this->assertModelAlias('meanbee_healthcheck/alert', 'Meanbee_Healthcheck_Model_Alert');
    }

    /**
     * @test
     */
    public function testHelperAlias()
    {
        $this->assertHelperAlias('meanbee_healthcheck', 'Meanbee_Healthcheck_Helper_Data');
        $this->assertHelperAlias('meanbee_healthcheck/config', 'Meanbee_Healthcheck_Helper_Config');
    }
}
