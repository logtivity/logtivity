<div class="logtivity-modal-strip">
	<h2 class="title">Overview</h2>
	<button type="button" class="notice-dismiss js-logtivity-notice-dismiss"><span class="screen-reader-text">Dismiss this modal.</span></button>
</div>

<table style="margin-top: 0;" class="form-table logtivity-table">
	<tbody>
		<tr>
			<th>Action</th>
			<td><?php echo sanitize_text_field($log->action) ?></td>
		</tr>
		<?php if ($log->context): ?>
			<tr>
				<th>Context</th>
				<td><?php echo sanitize_text_field($log->context) ?></td>
			</tr>
		<?php endif ?>
		<?php if ($log->action_user_id): ?>
			<tr>
				<th>User ID</th>
				<td><?php echo sanitize_text_field($log->action_user_id) ?></td>
			</tr>
		<?php endif ?>
		<?php if ($log->action_username): ?>
			<tr>
				<th>Username</th>
				<td><?php echo sanitize_text_field($log->action_username) ?></td>
			</tr>
		<?php endif ?>
		<?php if ($log->action_user_meta): ?>
			<?php foreach (json_decode($log->action_user_meta) as $key => $value): ?>
					<tr>
						<th><?php echo sanitize_text_field(ucfirst( str_replace('_', ' ', $key) )) ?></th>
						<td>
							<?php if (is_array($value)): ?>
								<pre class="mb-0"><?php var_export($value) ?></pre>
							<?php else: ?>
								<?php if (substr( $value, 0, 4 ) === "http"): ?>
						    		<a target="_blank" rel="noopener noreferrer" href="<?php echo sanitize_text_field($value) ?>"><?php echo sanitize_text_field($value) ?></a>
								<?php else: ?>
							        <?php echo sanitize_text_field($value) ?>
								<?php endif ?>
							<?php endif ?>
						</td>
					</tr>
			<?php endforeach ?>
		<?php endif; ?>
			
		<?php if ($log->ip_address): ?>
			<tr>
				<th>IP</th>
				<td><?php echo sanitize_text_field($log->ip_address) ?></td>
			</tr>
		<?php endif ?>
		<tr>
			<th>Date</th>
			<td>
				<?php echo sanitize_text_field(date('M d Y', strtotime($log->occurred_at))) ?>  <?php echo sanitize_text_field(date('H:i:s', strtotime($log->occurred_at))); ?>
			</td>
		</tr>
	</tbody>
</table>

<?php if ($log->meta): ?>
	
	<div class="logtivity-modal-strip light small">
		<h3 class="hndle">Meta</h3>
	</div>

	<table style="margin-top: 0;" class="form-table logtivity-table">
		<tbody>
			<?php foreach (json_decode($log->meta, true) as $key => $value): ?>
			
				<tr>
					<th>
						<?php if (isset($value['key'])): ?>
							<strong><?php echo sanitize_text_field($value['key']) ?></strong>
						<?php else: ?>
							<strong><?php echo sanitize_text_field($key) ?></strong>
						<?php endif ?>
					</th>
					<td>
						<?php if (isset($value['key']) && !isset($value['value'])) {
							$value = '';
						} ?>
						<?php if (isset($value['value'])): ?>
							<?php $value = $value['value']; ?>
						<?php endif; ?>
						<?php if (is_array($value) || is_object($value)): ?>
							<pre><?php var_export($value) ?></pre>
						<?php else: ?>
							<?php if (substr( $value, 0, 4 ) === "http"): ?>
								<a target="_blank" rel="noopener noreferrer" href="<?php echo sanitize_text_field($value) ?>">Visit Link <i class="ml-1 small fas fa-external-link-alt"></i></a>
							<?php else: ?>
								<?php echo sanitize_text_field($value) ?>
							<?php endif ?>
						<?php endif ?>
					</td>
				</tr>

			<?php endforeach ?>
		</tbody>
	</table>

<?php endif; ?>
