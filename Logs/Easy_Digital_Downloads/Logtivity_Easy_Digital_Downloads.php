<?php

class Logtivity_Easy_Digital_Downloads extends Logtivity_Abstract_Logger
{
	public function __construct()
	{
		add_action('edd_post_add_to_cart',  [$this, 'itemAddedToCart'], 10, 3);
		add_action('edd_post_remove_from_cart',  [$this, 'itemRemovedFromCart'], 10, 3);
	}

	public function itemAddedToCart($download_id, $options, $items)
	{
		$log = Logtivity_Logger::log()
			->setAction('Download Added to Cart')
			->setContext(get_the_title($download_id));

		$prices = edd_get_variable_prices($download_id);

		foreach ($items as $item) {
			if (isset($prices[$item['options']['price_id']])) {
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
		$log = Logtivity_Logger::log()
			->setAction('Download Removed from Cart')
			->setContext(get_the_title($item_id));

		$prices = edd_get_variable_prices($item_id);

		if (count($prices)) {
			$log->addMeta('Variable Item', $prices[$key]['name']);
		}

		$log->send();
	}
}

$Logtivity_Easy_Digital_Downloads = new Logtivity_Easy_Digital_Downloads;
