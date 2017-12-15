<?php

class Inkl_ProductWatcher_Model_Resource_Watcher_Collection extends Mage_Core_Model_Resource_Db_Collection_Abstract
{

	protected function _construct()
	{
		$this->_init('inkl_productwatcher/entity_watcher', 'inkl_productwatcher/watcher');
	}

}
