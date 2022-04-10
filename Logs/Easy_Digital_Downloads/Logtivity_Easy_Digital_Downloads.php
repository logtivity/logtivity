<?php

class Logtivity_Easy_Digital_Downloads extends Logtivity_Abstract_Easy_Digital_Downloads
{
	public function __construct()
	{
		add_action('edd_post_add_to_cart',  [$this, 'itemAddedToCart'], 10, 3);
		add_action('edd_post_remove_from_cart',  [$this, 'itemRemovedFromCart'], 10, 2);
		add_action('edd_customer_post_create', [$this, 'customerCreated'], 10, 2);
		add_action('edd_update_payment_status', [$this, 'paymentStatusUpdated'], 10, 3);
		add_action('edd_process_verified_download', [$this, 'fileDownloaded'], 10, 4);
		add_action( 'edd_cart_discount_set', [$this, 'discountApplied'], 10, 2);
		add_action( 'edd_cart_discount_removed', [$this, 'discountRemoved'], 10, 2);
	}

	public function itemAddedToCart($download_id, $options, $items)
	{
		$log = Logtivity_Logger::log()
			->setAction('Download Added to Cart')
			->setContext(logtivity_get_the_title($download_id))
			->setPostId($download_id);

		$prices = edd_get_variable_prices($download_id);

		foreach ($items as $item) {
			if (isset($item['options']['price_id']) && isset($prices[$item['options']['price_id']])) {
				$log->addMeta('Variable Item', $prices[$item['options']['price_id']]['name']);
			}

			if ($item['quantity']) {
				$log->addMeta('Quantity', $item['quantity']);
			}
		}

		$log->send();
	}

	public function itemRemovedFromCart($key, $item_id)
	{
		Logtivity_Logger::log()
			->setAction('Download Removed from Cart')
			->setContext(logtivity_get_the_title($item_id))
			->send();
	}

	public function paymentStatusUpdated($payment_id, $status, $old_status)
	{
		$payment = new EDD_Payment($payment_id);

		if ($status == 'refunded') {
			return $this->paymentRefunded($payment);
		}

		if ($status == 'publish') {
			return $this->paymentCompleted($payment);
		}

		if ($status == 'edd_subscription') {
			return;
		}

		Logtivity_Logger::log()
			->setAction('Payment Status Changed to ' . ucfirst($status))
			->setContext($this->getPaymentKey($payment))
			->addMeta('Payment Key', $this->getPaymentKey($payment))
			->addMeta('Total', $payment->total)
			->addMeta('Currency', $payment->currency)
			->addMeta('Gateway', $payment->gateway)
			->addMeta('Customer ID', $payment->customer_id)
			->send();
	}

	public function paymentCompleted($payment)
	{
		$key = $this->getPaymentKey($payment);

		$log = Logtivity_Logger::log()
			->setAction('Payment Completed')
			->setContext($key)
			->addMeta('Payment Key', $key);

		foreach ($payment->cart_details as $item) {
			$log->addMeta('Cart Item', $item['name']);
		}

		if ($payment->discounts != 'none') {
			$log->addMeta('Discount Code', $payment->discounts);
		}

		$log->addMeta('Total', $payment->total)
			->addMeta('Currency', $payment->currency)
			->addMeta('Gateway', $payment->gateway)
			->addMeta('Customer ID', $payment->customer_id)
			->send();
	}

	public function paymentRefunded($EDD_Payment)
	{
		Logtivity_Logger::log()
			->setAction('Payment Refunded')
			->setContext($this->getPaymentKey($EDD_Payment))
			->addMeta('Amount', $EDD_Payment->get_meta('_edd_payment_total'))
			->addMeta('Payment Key', $this->getPaymentKey($EDD_Payment))
			->addMeta('Customer ID', $EDD_Payment->customer_id)
			->send();
	}

	public function getPaymentKey($payment)
	{
		$meta = $payment->get_meta();

		if (isset($meta['key'])) {
			return $meta['key'];
		}
	}

	public function customerCreated($created, $args)
	{
		if (!$created) {
			return;
		}

		Logtivity_Logger::log()
			->setAction('Customer Created')
			->setContext($args['name'])
			->addMeta('Customer ID', $created)
			->send();
	}

	public function fileDownloaded($download, $email, $payment, $args)
	{
		$download = new EDD_Download($download);

		$payment = new EDD_Payment($payment);

		$log = Logtivity_Logger::log()
			->setAction('File Downloaded')
			->setContext($this->getDownloadTitle($download->get_ID(), $args['price_id'] ?? null))
			->setPostId($download->get_ID())
			->addMeta('Payment Key', $this->getPaymentKey($payment));

		if (isset($args['file_key'])) {
			$log->addMeta('File ID', $args['file_key']);
		}

		$log->addMeta('Customer ID', $payment->customer_id)
			->send();
	}

	public function discountApplied($code, $discounts)
	{
		Logtivity_Logger::log()
			->setAction('Discount Applied')
			->setContext($code)
			->send();
	}

	public function discountRemoved($code, $discounts)
	{
		Logtivity_Logger::log()
			->setAction('Discount Removed')
			->setContext($code)
			->send();
	}
}

$Logtivity_Easy_Digital_Downloads = new Logtivity_Easy_Digital_Downloads;

