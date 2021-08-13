<?php

class Logtivity_Easy_Digital_Downloads_Recurring extends Logtivity_Abstract_Easy_Digital_Downloads
{
	public function __construct()
	{
		add_action('edd_subscription_post_renew', [$this, 'subscriptionRenewed'], 10, 4);
		add_action('edd_subscription_post_create', [$this, 'subscriptionCreated'], 10, 2);
		add_action('edd_recurring_update_subscription', [$this, 'subscriptionUpdated'], 10, 3);
		add_action('edd_subscription_status_change', [$this, 'subscriptionStatusChanged'], 10, 3);
		add_action('edd_recurring_before_delete_subscription', [$this, 'subscriptionDeleted'], 10, 1);
		add_action('edd_recurring_update_subscription_payment_method', [$this, 'setupPaymentMethodUpdate'], 9, 3);
	}

	public function subscriptionRenewed($subscription_id, $expiration, $subscription, $payment_id)
	{
		$payment = new EDD_Payment($payment_id);

		$log = Logtivity_Logger::log()
			->setAction('Subscription Renewed')
			->setContext($this->getDownloadTitle($subscription));

		if (!is_user_logged_in()) {
			$log->setUser($subscription->customer->user_id ?? null);
		}

		$log->setUser($payment->user_id ?? null)
			->addMeta('Customer ID', $payment->customer_id)
			->addMeta('Subscription ID', $subscription_id);

			if (isset($expiration) && $expiration) {
				try {
					$log->addMeta('Expiration Date', date('M d Y', strtotime($expiration)));
				} catch (\Exception $e) {
				}
			}

		$log->addMeta('Payment ID', $payment_id)
			->send();
	}

	public function subscriptionCreated($subscription_id, $args)
	{
		$subscription = new EDD_Subscription($subscription_id);

		$log = Logtivity_Logger::log()
			->setAction('Subscription Created')
			->setContext($this->getDownloadTitle($subscription))
			->addMeta('Customer ID', $subscription->customer_id)
			->addMeta('Subscription ID', $subscription_id);

		if (isset($args['expiration']) && $args['expiration']) {
			try {
				$log->addMeta('Expiration Date', date('M d Y', strtotime($args['expiration'])));
			} catch (\Exception $e) {
			}
		}

		$log->send();
	}

	public function subscriptionUpdated($subscription_id, $args, $subscription)
	{
		$log = Logtivity_Logger::log()
			->setAction('Subscription Updated');

		$current_product  = $subscription->product_id;
		$current_price_id = $subscription->price_id;

		if (isset($args['product_id']) && $current_product != $args['product_id']) {
			$log->setContext('Product changed from '.get_the_title($current_product).' to '.get_the_title($args['product_id']));
		}

		if ( isset( $args['price_id'] ) && ! is_null( $args['price_id'] ) && $current_price_id != $args['price_id'] ) {
			$prices = edd_get_variable_prices($args['product_id'] ?? $current_product);

			if ($prices && count($prices)) {
				if (isset($prices[$args['price_id']]) && isset($prices[$current_price_id])) {
					$log->setContext('Price changed from '.$prices[$current_price_id]['name'].' to '.$prices[$args['price_id']]['name']);
				}
			} else {
				$log->setContext('Price ID changed from '.$current_price_id.' to '.$args['price_id']);
			}
		}

		if (!$log->context) {
			return;
		}

		$log->addMeta('Customer ID', $subscription->customer_id)
			->addMeta('Subscription ID', $subscription_id)
			->addMeta('Product', get_the_title($args['product_id'] ?? $current_product));

		if (!is_user_logged_in()) {
			$log->setUser($subscription->customer->user_id ?? null);
		}

		if ($subscription->expiration) {
			try {
				$log->addMeta('Expiration Date', date('M d Y', strtotime($subscription->expiration)));
			} catch (\Exception $e) {
			}
		}

		$log->send();
	}

	public function subscriptionStatusChanged($old_status, $new_status, $subscription)
	{
		if ($old_status === $new_status) {
			return;
		}

		$log = Logtivity_Logger::log()
			->setAction('Subscription '. ucfirst($new_status))
			->setContext($this->getDownloadTitle($subscription))
			->addMeta('Old Status', $old_status)
			->addMeta('Customer ID', $subscription->customer_id)
			->addMeta('Subscription ID', $subscription->id);

		if (!is_user_logged_in()) {
			$log->setUser($subscription->customer->user_id ?? null);
		}

		if ($subscription->expiration) {
			try {
				$log->addMeta('Expiration Date', date('M d Y', strtotime($subscription->expiration)));
			} catch (\Exception $e) {
			}
		}

		$log->send();
	}

	public function subscriptionDeleted($subscription)
	{
		$log = Logtivity_Logger::log()
			->setAction('Subscription Deleted')
			->setContext($this->getDownloadTitle($subscription))
			->addMeta('Customer ID', $subscription->customer_id)
			->addMeta('Subscription ID', $subscription->id);

		if (!is_user_logged_in()) {
			$log->setUser($subscription->customer->user_id ?? null);
		}

		if ($subscription->expiration) {
			try {
				$log->addMeta('Expiration Date', date('M d Y', strtotime($subscription->expiration)));
			} catch (\Exception $e) {
			}
		}

		$log->send();
	}

	public function setupPaymentMethodUpdate($user_id, $subscription_id, $verified)
	{
		$subscription = new EDD_Subscription($subscription_id);

		add_action( 'edd_recurring_update_' . $subscription->gateway .'_subscription', [$this, 'paymentMethodUpdated'], 999999999999, 2);
	}

	public function paymentMethodUpdated($subscriber, $subscription)
	{
		$errors = edd_get_errors();

		if (!empty($errors)) {
			return;
		}

		$log = Logtivity_Logger::log()
			->setAction('Payment Method Updated')
			->setContext($subscription->gateway)
			->addMeta('Customer ID', $subscription->customer_id)
			->addMeta('Subscription ID', $subscription->id);

		if (!is_user_logged_in()) {
			$log->setUser($subscription->customer->user_id ?? null);
		}

		if ($subscription->expiration) {
			try {
				$log->addMeta('Expiration Date', date('M d Y', strtotime($subscription->expiration)));
			} catch (\Exception $e) {
			}
		}

		$log->send();
	}
}

$Logtivity_Easy_Digital_Downloads_Recurring = new Logtivity_Easy_Digital_Downloads_Recurring;