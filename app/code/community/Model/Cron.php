<?php

class Inkl_ProductWatcher_Model_Cron
{

	public function run()
	{
		if (Mage::helper('inkl_productwatcher/config_general')->isEnabled())
		{
			Mage::getSingleton('inkl_productwatcher/task_detectChanges')->run();
		}
	}

}