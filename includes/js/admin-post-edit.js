/**
 * Custom js script at post edit screen
 *
 * @package   GravityView
 * @license   GPL2+
 * @author    Katz Web Services, Inc.
 * @link      http://gravityview.co
 * @copyright Copyright 2014, Katz Web Services, Inc.
 *
 * @since 1.0.0
 */


jQuery(document).ready( function( $ ) {

	/**
	 * Generate the shortcode to insert, and reset the form to default state.
	 */
	function InsertViewShortcode(){
		var view_id = $("#gravityview_view_id").val();

		if( view_id === '' ) {
			alert( gvGlobals.alert_1 );
			$("#gravityview_view_id").focus();
			return false;
		}

		var shortcode = '[gravityview id="' + view_id +'"';
		$("#gravityview_view_id").val('');

		//page size
		var page_size = parseInt( $("#gravityview_page_size").val() );
		if( page_size > 0 && page_size != 25 ) {
			shortcode += ' page_size="' + page_size + '"';
			$("#gravityview_page_size").val(25);
		}

		//lightbox
		if( $("#gravityview_lightbox").prop('checked') === false ) {
			shortcode += ' lightbox="0"';
			$("#gravityview_lightbox").prop('checked', 'checked');
		}

		//show only approved
		if( $("#gravityview_only_approved").prop('checked') === true ) {
			shortcode += ' show_only_approved="1"';
			$("#gravityview_only_approved").prop('checked', null );
		}

		// sorting
		var sort_field = $("#gravityview_sort_field:enabled").val();
		if( sort_field && sort_field !== '' ) {
			var sort_direction = $("#gravityview_sort_direction").val();
			shortcode += ' sort_field="' + sort_field + '"' + ' sort_direction="' + sort_direction + '"';
		}

		// date filtering
		var start_date = $("#gravityview_start_date").val();
		if( '' !== start_date ) {
			shortcode += ' start_date="' + start_date + '"';
		}
		var end_date = $("#gravityview_end_date").val();
		if( '' !== end_date ) {
			shortcode += ' end_date="' + end_date + '"';
		}

		$("#gravityview_sort_field,#gravityview_start_date,#gravityview_end_date").val('');


		shortcode += ']';
		//var win = window.dialogArguments || opener || parent || top;
		window.send_to_editor( shortcode );
		return false;
	}

	//datepicker
	$('.gv-datepicker').datepicker({
		dateFormat: "yy-mm-dd",

		// Allow users to type in values like "-1 year" or "now"
		constrainInput: false
	});


	// Select view id -> populate sort fields
	$("#gravityview_view_id").change( function() {

		if( $("#gravityview_view_id").val() === '' ) {
			$('#select_gravityview_view_form').find('.hide-if-js').fadeOut();
			return;
		}

		// While it's loading, disable the field, remove previous options, and add loading message.
		$("#gravityview_sort_field").prop('disabled', 'disabled').empty().append('<option>'+ gvGlobals.loading_text + '</option>');

		var data = {
			action: 'gv_sortable_fields',
			viewid: $(this).val(),
			nonce: gvGlobals.nonce,
		};

		$.post( ajaxurl, data, function( response ) {
			if( response ) {
				$("#gravityview_sort_field").empty().append( response ).prop('disabled', null );
			}
		});

		$('#select_gravityview_view_form').find('.hide-if-js').fadeIn();
	});

	// capture form submit -> add shortcode to editor
	$('#insert_gravityview_view').on( 'click', function(e) {
		e.preventDefault();
		InsertViewShortcode();
		$('#select_gravityview_view_form').find('.hide-if-js').hide();
		return false;
	});

});
