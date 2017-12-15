<?php

class Inkl_ProductWatcher_Model_Entity_Watcher extends Mage_Core_Model_Abstract
{

	protected function _construct()
	{
		$this->_init('inkl_productwatcher/watcher');
	}

	public function _beforeSave()
	{
		$this->setUpdatedAt(Varien_Date::now());

		if (!$this->getCreatedAt())
		{
			$this->setCreatedAt(Varien_Date::now());
		}

		return parent::_beforeSave();
	}
	
}
