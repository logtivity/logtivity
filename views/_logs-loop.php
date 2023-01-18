<table class="form-table logtivity-table">
	<thead>
		<tr>
			<th>Action</th>
			<th>Context</th>
			<th>User</th>
			<th colspan="2">Date</th>
		</tr>
	</thead>
	<tbody>
		
		<?php if (count($logs)): ?>
			<?php foreach ($logs as $key => $log): ?>

				<tr>
					<td><?php echo sanitize_text_field($log->action) ?></td>
					<td><?php echo sanitize_text_field($log->context) ?></td>
					<td>
						<div style="margin-bottom: 5px;"><?php echo sanitize_text_field($log->action_username ?? $log->action_user_id) ?></div>
						<?php echo sanitize_text_field($log->ip_address) ?>
					</td>
					<td>
						<?php echo sanitize_text_field(date('M d Y', strtotime($log->occurred_at))) ?> - <?php echo sanitize_text_field(date('H:i:s', strtotime($log->occurred_at))); ?>
					</td>
					<td>
						<button class="button js-logtivity-view-log">View</button>
						<div style="display: none;" class="js-modal-content">
							<?php echo logtivity_view('_log-show', [
								'log' => $log
							]) ?>
						</div>
					</td>
				</tr>

			<?php endforeach ?>
		<?php else: ?>
			<?php if (isset($message)): ?>
				<tr>
					<td colspan="6"><?php echo sanitize_text_field($message) ?></td>
				</tr>
			<?php else: ?>
				<tr>
					<td colspan="6">No results found.</td>
				</tr>
			<?php endif ?>
		<?php endif ?>

	</tbody>
</table>

<?php if (isset($meta) && $meta->current_page): ?>
	<div data-current-page="<?php echo $meta->current_page ?>" style="text-align: center; padding: 20px">
		<button <?php echo ( $meta->current_page == 1 ? 'disabled' : ''); ?> class="js-logtivity-pagination button-primary" data-page="<?php echo sanitize_text_field($meta->current_page - 1) ?>">Previous</button>
		
		<button <?php echo ( ! $hasNextPage ? 'disabled' : ''); ?> class="js-logtivity-pagination button-primary" data-page="<?php echo sanitize_text_field($meta->current_page + 1) ?>">Next</button>
	</div>
<?php endif ?>

<div class="logtivity-modal">
	<div class="logtivity-modal-dialog">
		<div class="logtivity-modal-content">
			<!-- Populated with JS -->
		</div>
	</div>
</div>