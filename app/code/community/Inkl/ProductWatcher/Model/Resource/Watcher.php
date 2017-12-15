<?php

class Inkl_ProductWatcher_Model_Resource_Watcher extends Mage_Core_Model_Resource_Db_Abstract
{

	protected function _construct()
	{
		$this->_init('inkl_productwatcher/watcher', 'id');
	}

}
