<?php

class Inkl_ProductWatcher_Model_Task_DetectChanges
{

	public function run()
	{
		$stores = Mage::app()->getStores();

		$storeHashProducts = [];

		/** @var Mage_Core_Model_Store $store */
		foreach ($stores as $store)
		{
			$storeHashProducts += $this->getStoreHashProducts($store);
		}

		$watcherHashProducts = Mage::getSingleton('inkl_productwatcher/provider_watcher')->getAllHashProducts();

		$newHashProducts = $this->detectNew($storeHashProducts, $watcherHashProducts);
		$deletedHashProducts = $this->detectDeleted($storeHashProducts, $watcherHashProducts);
		$enabledHashProducts = $this->detectEnabled($storeHashProducts, $watcherHashProducts);
		$disabledHashProducts = $this->detectDisabled($storeHashProducts, $watcherHashProducts);

		if (count($newHashProducts) || count($deletedHashProducts) || count($enabledHashProducts) || count($disabledHashProducts))
		{
			Mage::getSingleton('inkl_productwatcher/management_mail')->send($newHashProducts, $deletedHashProducts, $enabledHashProducts, $disabledHashProducts);
		}

		Mage::getSingleton('inkl_productwatcher/management_watcher')->updateWatcherData($storeHashProducts);
	}

	private function getStoreHashProducts(Mage_Core_Model_Store $store)
	{
		$productCollection = Mage::getResourceModel('inkl_productwatcher/product_collection')
			->addAttributeToSelect(['sku', 'name', 'status'])
			->setStore($store)
			->addStoreFilter($store);

		$storeHashProducts = [];
		foreach ($productCollection as $product)
		{
			$storeHashProducts += Mage::getSingleton('inkl_productwatcher/builder_hashProduct')->build(
				$store->getId(),
				$product->getId(),
				$product->getSku(),
				$product->getName(),
				$product->getStatus()
			);
		}

		return $storeHashProducts;
	}

	private function detectNew(array $storeHashProducts, array $watcherHashProducts)
	{
		$newHashProducts = array_diff_key($storeHashProducts, $watcherHashProducts);

		return array_filter($newHashProducts, function ($productData) {
			return ($productData['product_status'] == 1);
		});
	}

	private function detectDeleted(array $storeHashProducts, array $watcherHashProducts)
	{
		$deletedHashProducts = array_diff_key($watcherHashProducts, $storeHashProducts);

		return array_filter($deletedHashProducts, function ($productData) {
			return ($productData['product_status'] == 1);
		});
	}

	private function detectEnabled(array $storeHashProducts, array $watcherHashProducts)
	{
		return array_filter($storeHashProducts, function ($productData, $hashKey) use ($watcherHashProducts) {
			return ($productData['product_status'] == 1 && isset($watcherHashProducts[$hashKey]) && $watcherHashProducts[$hashKey]['product_status'] != 1);
		}, ARRAY_FILTER_USE_BOTH);
	}

	private function detectDisabled(array $storeHashProducts, array $watcherHashProducts)
	{
		return array_filter($watcherHashProducts, function ($productData, $hashKey) use ($storeHashProducts) {
			return ($productData['product_status'] == 1 && isset($storeHashProducts[$hashKey]) && $storeHashProducts[$hashKey]['product_status'] != 1);
		}, ARRAY_FILTER_USE_BOTH);
	}

}