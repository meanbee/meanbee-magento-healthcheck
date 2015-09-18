<?php

class Meanbee_Healthcheck_Test_Helper_Alert extends EcomDev_PHPUnit_Test_Case
{
    /**
     * @var Meanbee_Healthcheck_Helper_Alert
     */
    protected $_helper;

    public function setUp()
    {
        $this->_helper = new Meanbee_Healthcheck_Helper_Alert();
    }

    public function tearDown()
    {
        $this->helper = null;
    }

    /**
     * @test
     */
    public function test_all_receipients_are_notified_on_alert()
    {
        $email_template = $this->getModelMock('core/email_template', array('send'));
        $email_template->expects($this->exactly(3))
            ->method('send');

        $config_helper = $this->getHelperMock('meanbee_healthcheck/config', array(
            'getNotificationEmailAddresses',
            'isEnabled'
        ));

        $config_helper->expects($this->once())
            ->method('getNotificationEmailAddresses')
            ->willReturn(array(
                'email1@example.com',
                'email2@example.com',
                'email3@example.com'
            ));

        $config_helper->expects($this->any())
            ->method('isEnabled')
            ->willReturn(true);

        $this->replaceByMock('model', 'core/email_template', $email_template);
        $this->replaceByMock('helper', 'meanbee_healthcheck/config', $config_helper);

        $alert = Mage::getModel('meanbee_healthcheck/alert');

        $this->_helper->notify($alert);
    }

    /**
     * @test
     */
    public function test_no_emails_sent_when_disabled()
    {
        $email_template = $this->getModelMock('core/email_template', array('send'));
        $email_template->expects($this->never())
            ->method('send');

        $config_helper = $this->getHelperMock('meanbee_healthcheck/config', array(
            'getNotificationEmailAddresses',
            'isEnabled'
        ));

        $config_helper->expects($this->any())
            ->method('getNotificationEmailAddresses')
            ->willReturn(array(
                'email1@example.com'
            ));

        $config_helper->expects($this->any())
            ->method('isEnabled')
            ->willReturn(false);

        $this->replaceByMock('model', 'core/email_template', $email_template);
        $this->replaceByMock('helper', 'meanbee_healthcheck/config', $config_helper);

        $alert = Mage::getModel('meanbee_healthcheck/alert');

        $this->_helper->notify($alert);
    }
}
