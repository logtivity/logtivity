<?php echo logtivity_view('_admin-header'); ?>

<div class="postbox">
	<div class="inside">

		<h1 style="padding-top: 20px;">Settings</h1>

		<form action="<?php echo admin_url( 'admin-ajax.php' ); ?>?action=logtivity_update_settings" method="post">

			<?php wp_nonce_field( 'logtivity_update_settings', 'logtivity_update_settings' ) ?>

			<table class="form-table">
				<tbody>
					<tr class="user-user-login-wrap">
						<th><label for="logtivity_site_api_key">Site API Key</label></th>
						<td>
							<input type="text" name="logtivity_site_api_key" id="logtivity_site_api_key" value="<?php echo sanitize_text_field($options['logtivity_site_api_key']); ?>" class="regular-text">
							<?php if ($options['logtivity_api_key_check']): ?>
								<p>Status: <?php echo ( sanitize_text_field($options['logtivity_api_key_check']) == 'success' ? '<span style="color: #4caf50; font-weight: bold;">Connected</span>' : '<span style="color: #ff3232; font-weight: bold;">Not connected. Please check API key.</span>'); ?></p>
							<?php endif ?>
						</td>
						<td>
							<span class="description">You can find this value by logging into your account and navigating to/creating this site settings page.</span>
						</td>
					</tr>			
					<tr class="user-user-login-wrap">
						<th><label for="logtivity_disable_default_logging">Disable built in Event Logging.</label></th>
						<td>
							<input type="hidden" name="logtivity_disable_default_logging" id="logtivity_disable_default_logging" value="0">

							<input type="checkbox" name="logtivity_disable_default_logging" id="logtivity_disable_default_logging" value="1" class="regular-checkbox" <?php echo ( absint($options['logtivity_disable_default_logging']) ? 'checked' : ''); ?>>
						</td>
						<td>
							<span class="description">Check this box if you do not want the plugin to log actions automatically and you would prefer to manually log specific actions with code.</span>
						</td>
					</tr>
					<tr class="user-user-login-wrap">
						<th><label for="logtivity_should_store_user_id">Store User ID</label></th>
						<td>
							<input type="hidden" name="logtivity_should_store_user_id" id="logtivity_should_store_user_id" value="0">

							<input type="checkbox" name="logtivity_should_store_user_id" id="logtivity_should_store_user_id" value="1" class="regular-checkbox" <?php echo ( absint($options['logtivity_should_store_user_id']) ? 'checked' : ''); ?>>
						</td>
						<td>
							<span class="description">If you check this box, when logging an action, we will include the users User ID in the logged action.</span>
						</td>
					</tr>
					<tr class="user-user-login-wrap">
						<th><label for="logtivity_should_log_profile_link">Store Users Profile Link</label></th>
						<td>
							<input type="hidden" name="logtivity_should_log_profile_link" id="logtivity_should_log_profile_link" value="0">

							<input type="checkbox" name="logtivity_should_log_profile_link" id="logtivity_should_log_profile_link" value="1" class="regular-checkbox" <?php echo ( absint($options['logtivity_should_log_profile_link']) ? 'checked' : ''); ?>>
						</td>
						<td>
							<span class="description">If you check this box, when logging an action, we will include the users profile link in the logged action.</span>
						</td>
					</tr>
					<tr class="user-user-login-wrap">
						<th><label for="logtivity_should_log_username">Store Users Username</label></th>
						<td>
							<input type="hidden" name="logtivity_should_log_username" id="logtivity_should_log_username" value="0">

							<input type="checkbox" name="logtivity_should_log_username" id="logtivity_should_log_username" value="1" class="regular-checkbox" <?php echo ( absint($options['logtivity_should_log_username']) ? 'checked' : ''); ?>>
						</td>
						<td>
							<span class="description">If you check this box, when logging an action, we will include the users username in the logged action.</span>
						</td>
					</tr>
					<tr class="user-user-login-wrap">
						<th><label for="logtivity_should_store_ip">Store Users IP Address</label></th>
						<td>
							<input type="hidden" name="logtivity_should_store_ip" id="logtivity_should_store_ip" value="0">

							<input type="checkbox" name="logtivity_should_store_ip" id="logtivity_should_store_ip" value="1" class="regular-checkbox" <?php echo ( absint($options['logtivity_should_store_ip']) ? 'checked' : ''); ?>>
						</td>
						<td>
							<span class="description">If you check this box, when logging an action, we will include the users IP address in the logged action.</span>
						</td>
					</tr>
					<tr class="user-user-login-wrap">
						<th><label for="logtivity_enable_debug_mode">Enable debug mode (recommended off by default)</label></th>
						<td>
							<input type="hidden" name="logtivity_enable_debug_mode" id="logtivity_enable_debug_mode" value="0">

							<input type="checkbox" name="logtivity_enable_debug_mode" id="logtivity_enable_debug_mode" value="1" class="regular-checkbox" <?php echo ( absint($options['logtivity_enable_debug_mode']) ? 'checked' : ''); ?>>
						</td>
						<td>
							<span class="description">This will log the latest response from the Logtivity API. This can be useful for debugging the result from an API call when storing a log. We <strong>recommend setting this to off by default</strong> as this will allow us to send logs asynchronously and not wait for a response from the API. This will be more performant.</span>
						</td>
					</tr>
				</tbody>
			</table>

			<p class="submit">
				<input type="submit" name="submit" id="submit" class="button button-primary" value="Update Settings">
			</p>

		</form>

	</div>
</div>

<?php if (absint( $options['logtivity_enable_debug_mode'] )): ?>

	<div class="postbox">
		<div class="inside">

			<h3>Latest Response</h3>

			<?php if ($latest_response = $options['logtivity_latest_response']): ?>

				<h4>Date: <?php echo sanitize_text_field($latest_response['date']); ?></h4>

				<?php if ($latest_response['response']): ?>
					<code style="display: block; padding: 20px;">
							
						<?php echo sanitize_text_field($latest_response['response']); ?>

					</code>
				<?php endif ?>
					
			<?php else:  ?>

				<p>The latest logging response will appear here after an event has been logged.</p>

			<?php endif ?>
			
		</div>
	</div>

<?php endif ?>

<?php echo logtivity_view('_admin-footer'); ?>