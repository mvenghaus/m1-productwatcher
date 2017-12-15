<?php

class Inkl_ProductWatcher_Model_Management_Mail
{

	public function send(array $newHashProducts, array $deletedHashProducts, array $enabledHashProducts, array $disabledHashProducts)
	{
		try
		{
			/* @var Mage_Core_Model_Email_Template $emailTemplate */
			$emailTemplate = Mage::getModel('core/email_template')
				->setTemplateSubject($this->getEmailSubject())
				->setTemplateText($this->createMessage($newHashProducts, $deletedHashProducts, $enabledHashProducts, $disabledHashProducts))
				->setSenderEmail(Mage::getStoreConfig('trans_email/ident_general/email'))
				->setSenderName(Mage::getStoreConfig('trans_email/ident_general/name'));

			if (count($newHashProducts))
			{
				$this->addAttachment($emailTemplate, 'new.csv', $newHashProducts);
			}

			if (count($deletedHashProducts))
			{
				$this->addAttachment($emailTemplate, 'deleted.csv', $deletedHashProducts);
			}

			if (count($enabledHashProducts))
			{
				$this->addAttachment($emailTemplate, 'enabled.csv', $enabledHashProducts);
			}

			if (count($disabledHashProducts))
			{
				$this->addAttachment($emailTemplate, 'disabled.csv', $disabledHashProducts);
			}

			return $emailTemplate->send($this->getEmailReceiver());
		} catch (Exception $e)
		{
			die($e->getMessage());
		}
	}

	private function getEmailSubject()
	{
		$emailSubject = Mage::helper('inkl_productwatcher/config_general')->getEmailSubject();
		if (!$emailSubject)
		{
			$emailSubject = 'Product Changes';
		}

		return $emailSubject;
	}

	private function getEmailReceiver()
	{
		$emailReceiver = Mage::helper('inkl_productwatcher/config_general')->getEmailReceiver();
		if (!$emailReceiver)
		{
			throw new Exception('no email receiver found');
		}

		$emails = explode("\n", $emailReceiver);

		return array_map('trim', $emails);
	}

	private function createMessage($newHashProducts, $deletedHashProducts, $enabledHashProducts, $disabledHashProducts)
	{
		$message = '';

		if (count($newHashProducts))
		{
			$message .= $this->createSummaryMessage('new', $newHashProducts);
		}

		if (count($deletedHashProducts))
		{
			$message .= $this->createSummaryMessage('deleted', $deletedHashProducts);
		}

		if (count($enabledHashProducts))
		{
			$message .= $this->createSummaryMessage('enabled', $enabledHashProducts);
		}

		if (count($disabledHashProducts))
		{
			$message .= $this->createSummaryMessage('disabled', $disabledHashProducts);
		}

		return $message;
	}

	private function createSummaryMessage($type, $hashProducts)
	{
		$message = '<p style="font-family: Arial; font-size: 12px;">';

		$message .= sprintf('<strong>%s</strong><br><br>', strtoupper($type));

		$websiteSummary = [];
		foreach ($hashProducts as $hashProduct)
		{
			$websiteName = Mage::app()->getStore($hashProduct['store_id'])->getWebsite()->getName();
			$websiteSummary[$websiteName] = ($websiteSummary[$websiteName] ?? 0) + 1;
		}

		foreach ($websiteSummary as $websiteName => $count)
		{
			$message .= sprintf('%s: %d<br>', $websiteName, $count);
		}

		$message .= '</p>';

		return $message;
	}

	private function addAttachment($emailTemplate, $filename, $hashProducts)
	{
		$lines = [];
		$lines[] = implode(';', ['website', 'sku', 'name', 'status']);
		foreach ($hashProducts as $hashProduct)
		{
			$lines[] = implode(';', [
				Mage::app()->getStore($hashProduct['store_id'])->getWebsite()->getName(),
				$hashProduct['product_sku'],
				$hashProduct['product_name'],
				$hashProduct['product_status'],
			]);
		}

		$content = implode(PHP_EOL, $lines);

		$emailTemplate->getMail()->createAttachment(
			$content,
			Zend_Mime::TYPE_OCTETSTREAM,
			Zend_Mime::DISPOSITION_ATTACHMENT,
			Zend_Mime::ENCODING_BASE64,
			$filename
		);
	}

}
