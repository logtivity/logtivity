var LogtivityLogIndex = {
	
	init: function() {
		this.container = jQuery('#logtivity-log-index');
		
		this.form = jQuery('#logtivity-log-index-search-form');

		this.listenForPagination();

		this.listenForSubmit();

		this.listenForChange();

		this.form.submit();
	},

	listenForChange: function() {

		var $this = this;

		var timeout = null;

		jQuery("body").on( "input", "#logtivity-log-index-search-form input", function(e) {
			e.preventDefault();

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

	listenForSubmit: function() {

		var $this = this;

		jQuery("body").on( "submit", "#logtivity-log-index-search-form", function(e) {
			e.preventDefault();

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

jQuery(function() {
	LogtivityLogIndex.init();
});