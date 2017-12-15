<?php

class Inkl_ProductWatcher_Model_Management_Watcher
{
	private $coreResource;
	private $dbRead;
	private $dbWrite;

	public function __construct()
	{
		$this->coreResource = Mage::getSingleton('core/resource');
		$this->dbRead = $this->coreResource->getConnection('core_read');
		$this->dbWrite = $this->coreResource->getConnection('core_write');
	}

	public function updateWatcherData(array $watcherData)
	{
		$this->dbWrite->beginTransaction();

		$this->dbWrite->truncateTable('catalog_product_watcher');

		$watcherData = $this->addCreatedAtToData($watcherData);

		$chunks = array_chunk($watcherData, 100);
		foreach ($chunks as $chunk)
		{
			$this->dbWrite->insertMultiple('catalog_product_watcher', $chunk);
		}

		$this->dbWrite->commit();
	}

	private function addCreatedAtToData($watcherData)
	{
		$createdAt = date('Y-m-d H:i:s', Mage::getModel('core/date')->timestamp(time()));

		return array_map(function ($data) use ($createdAt) {
			return array_merge($data, ['created_at' => $createdAt]);
		}, $watcherData);
	}

}