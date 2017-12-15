<?php

/* @var $installer Mage_Core_Model_Resource_Setup */
$installer = $this;

$installer->startSetup();

$connection = $installer->getConnection();

$tableName = 'catalog_product_watcher';

$table = $connection
	->newTable($tableName)
	->addColumn('id', Varien_Db_Ddl_Table::TYPE_INTEGER, 11, ['primary' => true, 'auto_increment' => true])
	->addColumn('store_id', Varien_Db_Ddl_Table::TYPE_INTEGER, 11)
	->addColumn('product_id', Varien_Db_Ddl_Table::TYPE_INTEGER, 11)
	->addColumn('product_sku', Varien_Db_Ddl_Table::TYPE_TEXT, 255)
	->addColumn('product_name', Varien_Db_Ddl_Table::TYPE_TEXT, 255)
	->addColumn('product_status', Varien_Db_Ddl_Table::TYPE_TINYINT, 1)
	->addColumn('created_at', Varien_Db_Ddl_Table::TYPE_DATETIME)
	->addIndex('store_id', ['store_id'])
	->addIndex('product_id', ['product_id'])
	->addIndex('product_status', ['product_status']);

$connection->dropTable($tableName);
$connection->createTable($table);

$installer->endSetup();
