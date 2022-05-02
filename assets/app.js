var LogtivityLogIndex = {
	
	init: function() {
		this.container = jQuery('#logtivity-log-index');

		if (!this.container.length) {
			return;
		}
		
		this.form = jQuery('#logtivity-log-index-search-form');

		this.listenForPagination();

		this.listenForChange();

		this.filter();

		this.listenForViewLog()

		this.listenForCloseModal();
	},

	listenForCloseModal: function() {

		var $this = this;

		jQuery("body").on( "click", ".js-logtivity-notice-dismiss", function(e) {
			e.preventDefault();

			$this.hideModal();

		});

		jQuery(document).on('keyup', function(e) {

			if (e.key == "Escape") {
				$this.hideModal();
			}

		});

		jQuery(document).mouseup(function(e) {

			if (!$this.modalOpen) {
				return;
			}

			var container = jQuery('.logtivity-modal-dialog');

			// if the target of the click isn't the container nor a descendant of the container
			if (!container.is(e.target) && container.has(e.target).length === 0) {
				$this.hideModal();
			}

		});

	},

	listenForViewLog: function() {

		var $this = this;

		jQuery("body").on( "click", ".js-logtivity-view-log", function(e) {
			e.preventDefault();

			$this.showLogModal(jQuery(this).next().html());
		});

	},

	showLogModal: function(modalContent) {

		jQuery('.logtivity-modal').addClass('active');

		this.modalOpen = true;

		jQuery('.logtivity-modal-content').html(modalContent);

	},

	hideModal: function() {

		jQuery('.logtivity-modal').removeClass('active');

		this.modalOpen = false;

	},

	listenForChange: function() {

		var $this = this;

		var timeout = null;

		jQuery("body").on( "input", "#logtivity-log-index-search-form input", function(e) {
			e.preventDefault();

			jQuery('#logtivity_page').val('');

		    // Clear the timeout if it has already been set.
		    // This will prevent the previous task from executing
		    // if it has been less than <MILLISECONDS>
		    clearTimeout(timeout);

		    // Make a new timeout set to go off in 1000ms
		    timeout = setTimeout(function () {
		        
				$this.filter();

		    }, 1000);

		});

	},

	loading: function() {

		this.container.html(
			'<div style="text-align: center; padding-bottom: 20px"><div class="spinner is-active" style="float:none;width:auto;height:auto;padding:10px 0 10px 50px;background-position:20px 0;"></div></div>'
			);
	
	},

	listenForPagination: function() {

		var $this = this;

		jQuery("body").on( "click", ".js-logtivity-pagination", function(e) {
			e.preventDefault();

			jQuery('#logtivity_page').val(jQuery(this).attr('data-page'));

			$this.filter();
		});

	},

	filter: function() {

		this.loading();

		var $this = this;

		jQuery.ajax({
		    url: $this.form.attr('action'),
		    type: 'GET',
		    data: $this.form.serialize(),
		    success: function(result) {
			    $this.container.html(result.view);
			},
			error: function(error) {
				console.log(error);
			}
		});

	}

};

var DismissUrlHasChanged = {

	init: function() {

		jQuery( document ).on( 'click', '.notice-dismiss', function () {
			var type = jQuery(this).closest('.is-dismissible').attr('notice');
			var dismissUntil = jQuery(this).closest('.is-dismissible').attr('dismiss-until');

			if (type) {
				jQuery.ajax( ajaxurl, {
					type: 'POST',
					data: {
						action: 'logtivity_dismiss_notice',
						type: type,
						dismiss_until: dismissUntil,
					}
				});
			}

		});

	}

};

jQuery(function() {
	LogtivityLogIndex.init();
	DismissUrlHasChanged.init();
});