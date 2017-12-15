<?php

class Inkl_ProductWatcher_Model_Provider_Watcher
{

	public function getAllHashProducts()
	{
		$watcherCollection = Mage::getResourceModel('inkl_productwatcher/watcher_collection');

		$hashProducts = [];
		foreach ($watcherCollection as $watcher)
		{
			$hashProducts += Mage::getSingleton('inkl_productwatcher/builder_hashProduct')->build(
				$watcher->getStoreId(),
				$watcher->getProductId(),
				$watcher->getProductSku(),
				$watcher->getProductName(),
				$watcher->getProductStatus()
			);
		}

		return $hashProducts;
	}

}