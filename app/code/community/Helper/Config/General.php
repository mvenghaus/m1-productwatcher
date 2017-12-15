<?php

class Inkl_ProductWatcher_Helper_Config_General extends Mage_Core_Helper_Abstract
{

	const XML_PATH_GENERAL_ENABLED = 'inkl_productwatcher/general/enabled';
	const XML_PATH_GENERAL_EMAIL_SUBJECT = 'inkl_productwatcher/general/email_subject';
	const XML_PATH_GENERAL_EMAIL_RECEIVER = 'inkl_productwatcher/general/email_receiver';

	public function isEnabled($storeId = null)
	{
		return Mage::getStoreConfigFlag(self::XML_PATH_GENERAL_ENABLED, $storeId);
	}

	public function getEmailSubject($storeId = null)
	{
		return Mage::getStoreConfig(self::XML_PATH_GENERAL_EMAIL_SUBJECT, $storeId);
	}

	public function getEmailReceiver($storeId = null)
	{
		return Mage::getStoreConfig(self::XML_PATH_GENERAL_EMAIL_RECEIVER, $storeId);
	}

}