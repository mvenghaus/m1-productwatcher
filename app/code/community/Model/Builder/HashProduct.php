<?php

class Inkl_ProductWatcher_Model_Builder_HashProduct
{

	public function build($storeId, $productId, $productSku, $productName, $productStatus)
	{
		return [
			sprintf('%s##%s', $storeId, $productId) => [
				'store_id' => $storeId,
				'product_id' => $productId,
				'product_sku' => $productSku,
				'product_name' => $productName,
				'product_status' => $productStatus
			]
		];
	}

}