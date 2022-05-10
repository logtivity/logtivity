<?php echo logtivity_view('_admin-header', compact('options')); ?>

<div class="postbox">
	<div class="logtivity-settings">

		<div class="inside">
			<h1 style="padding-top: 10px;">Logs</h1>
		</div>

		<form id="logtivity-log-index-search-form" method="GET" action="<?php echo admin_url( 'admin-ajax.php' ) ?>">
			<input type="hidden" name="action" value="logtivity_log_index_filter">
			<input id="logtivity_page" type="hidden" name="page">

			<div class="logtivity-row">
				<div class="logtivity-col-md-4">
					<input type="search" name="search_action" class="large-text" placeholder="Search action">
				</div>
				<div class="logtivity-col-md-4">
					<input type="search" name="search_context" class="large-text" placeholder="Search context">
				</div>
				<div class="logtivity-col-md-4">
					<input type="search" name="action_user" class="large-text" placeholder="User ID / Username / IP Address">
				</div>
			</div>
		</form>

		<div style="padding-top: 15px" id="logtivity-log-index">
			<!-- Populated with AJAX -->
		</div>

	</div>
</div>

<?php echo logtivity_view('_admin-footer', compact('options')); ?>