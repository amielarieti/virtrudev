(function($) {
  "use strict";
  $(document).ready(function() {
  	if ( $( "#gdpr_analytics_datepicker_start" ).length > 0 ) {
			$( "#gdpr_analytics_datepicker_start" ).datepicker({
				dateFormat: "yy-mm-dd",	        
	        onSelect: function () {
	            var dt2 = $('#gdpr_analytics_datepicker_end');
	            var minDate = $(this).datepicker('getDate');
	            var dt2Date = dt2.datepicker('getDate');
	            //difference in days. 86400 seconds in day, 1000 ms in second
	            var dateDiff = (dt2Date - minDate)/(86400 * 1000);

	            //dt2 not set or dt1 date is greater than dt2 date
	            if (dt2Date == null || dateDiff < 0) {
	                    dt2.datepicker('setDate', minDate);
	            }
	        }
			});
		}

		$(document).on('change','input[name="gdpr_load_plugin"]',function(){
 			if ( $(this).val() === '3' ) {
 				$('#gdpr_hide_by_role_cnt').addClass('moove-hidden');
 			} else {
 				$('#gdpr_hide_by_role_cnt').removeClass('moove-hidden');
 			}
 		});

		$(document).on('change','.gdpr-float-number-field', function() {
      $(this).val( parseFloat( $(this).val() ).toFixed(1) );
  	});
		$('.gdpr-float-number-field').each( function() {
      $(this).val( parseFloat( $(this).val() ).toFixed(1) );
  	});

  	$(document).on('click','.button-reset-analytics',function(e){
      e.preventDefault();
      $('.gdpr-admin-popup.gdpr-admin-popup-remove-analytics').fadeIn(200);
    });

  	$(document).on('click','.gdpr_consent_version-wrap .gdpr-cv-btn', function() {
  		var input = $(this).parent().find('input[type="number"]');
  		input.focus();
  		if ( $(this).hasClass('gdpr-cv-inc') ) {
  			// Inc
  			input.val( parseFloat( input.val() ) + 0.1 );
  			input.trigger('change');
  		} else {
  			// Dec
  			if ( parseFloat( input.val() ) > 1.0 ) {
	  			input.val( parseFloat( input.val() ) - 0.1 );
	  			input.trigger('change');
  			}
  		}
  	});

		if ( $( "#gdpr_consentlog_datepicker_start" ).length > 0 ) {
			$( "#gdpr_consentlog_datepicker_start" ).datepicker({
				dateFormat: "yy-mm-dd",	        
        onSelect: function () {
            var dt2 = $('#gdpr_consentlog_datepicker_end');
            var minDate = $(this).datepicker('getDate');
            var dt2Date = dt2.datepicker('getDate');
            //difference in days. 86400 seconds in day, 1000 ms in second
            var dateDiff = (dt2Date - minDate)/(86400 * 1000);

            //dt2 not set or dt1 date is greater than dt2 date
            if (dt2Date == null || dateDiff < 0) {
                    dt2.datepicker('setDate', minDate);
            }
        }
			});
		}

		if ( $( "#gdpr_consentlog_export_start" ).length > 0 ) {
			$( "#gdpr_consentlog_export_start" ).datepicker({
				dateFormat: "yy-mm-dd",	        
        onSelect: function () {
            var dt2 = $('#gdpr_consentlog_export_end');
            var minDate = $(this).datepicker('getDate');
            var dt2Date = dt2.datepicker('getDate');
            //difference in days. 86400 seconds in day, 1000 ms in second
            var dateDiff = (dt2Date - minDate)/(86400 * 1000);

            //dt2 not set or dt1 date is greater than dt2 date
            if (dt2Date == null || dateDiff < 0) {
                    dt2.datepicker('setDate', minDate);
            }
        }
			});
		}


		if ( $( "#gdpr_analytics_datepicker_end" ).length > 0 ) {
			$( "#gdpr_analytics_datepicker_end" ).datepicker({
				dateFormat: "yy-mm-dd",
				onSelect: function () {
		            var dt1 = $('#gdpr_analytics_datepicker_start');
		            var minDate = $(this).datepicker('getDate');
		            var dt2Date = dt1.datepicker('getDate');
		            //difference in days. 86400 seconds in day, 1000 ms in second
		            var dateDiff = (dt2Date - minDate)/(86400 * 1000);

		            //dt2 not set or dt1 date is greater than dt2 date
		            if (dt2Date == null || dateDiff > 0) {
		                    dt1.datepicker('setDate', minDate);
		            }
		        }
			});
		}

		if ( $( "#gdpr_consentlog_datepicker_end" ).length > 0 ) {
			$( "#gdpr_consentlog_datepicker_end" ).datepicker({
				dateFormat: "yy-mm-dd",
				onSelect: function () {
		            var dt1 = $('#gdpr_consentlog_datepicker_start');
		            var minDate = $(this).datepicker('getDate');
		            var dt2Date = dt1.datepicker('getDate');
		            //difference in days. 86400 seconds in day, 1000 ms in second
		            var dateDiff = (dt2Date - minDate)/(86400 * 1000);

		            //dt2 not set or dt1 date is greater than dt2 date
		            if (dt2Date == null || dateDiff > 0) {
		                    dt1.datepicker('setDate', minDate);
		            }
		        }
			});
		}

		if ( $( "#gdpr_consentlog_export_end" ).length > 0 ) {
			$( "#gdpr_consentlog_export_end" ).datepicker({
				dateFormat: "yy-mm-dd",
				onSelect: function () {
		            var dt1 = $('#gdpr_consentlog_export_start');
		            var minDate = $(this).datepicker('getDate');
		            var dt2Date = dt1.datepicker('getDate');
		            //difference in days. 86400 seconds in day, 1000 ms in second
		            var dateDiff = (dt2Date - minDate)/(86400 * 1000);

		            //dt2 not set or dt1 date is greater than dt2 date
		            if (dt2Date == null || dateDiff > 0) {
		                    dt1.datepicker('setDate', minDate);
		            }
		        }
			});
		}

		

		$(document).on('click', '.gdpr-consent-log-table a.gdpr_delete_cc_single', function(e){
			e.preventDefault();
			var entry_id 		= $(this).closest('tr').attr('data-id');
			var nonce 			= $('#cc_single_delete_nonce_trash').val();
			var table_item 	= $(this).closest('.gdpr-consent-log-table');
			var ajax_url 		= table_item.attr('data-ajaxurl');
			var table_row 	= $(this).closest('tr');
			if ( entry_id && nonce ) {
				$.post(
        	ajax_url,
        	{
            action: 	"gdpr_delete_cc_single_log_entry",
            entry_id: entry_id,
            nonce: 		nonce,
        	},
        	function( msg ) {
        		var obj = JSON.parse( msg );
        		if ( obj.success ) {
        			table_row.find('td').slideUp(200);
  					}
  				}
  			);
  		}
		});
	
		if ( $('#gdpr_geo_countries').length > 0 ) {
			$('#gdpr_geo_countries').select2({ placeholder: "Select country / countries", });
		}

		$(document).on('change','.gdpr-cc-geo-wrap input[type="checkbox"]',function(){
			$('#gdpr_geo_countries').select2({ placeholder: "Select country / countries", });
		});
		

		
		$(document).on('click','#moove_gdpr_tab_fsm_settings .gdpr-disable-posts-nav.type_post_type li a', function(e) {
			e.preventDefault();
			$(this).closest('ul').find('.active').removeClass('active');
			$(this).addClass('active');
			var target = $(this).attr('href');
			$('#moove_gdpr_tab_fsm_settings .form-active').removeClass('form-active');
			$(target).addClass('form-active');
		});


		var panel_opened = '';
		$(document).on('click','.gdpr-toggle-panel .gdpr-toggle-panel-heading .buttons-container a',function(e){
			e.preventDefault();
			var button = $(this);
			var target = button.attr('href');
			if ( $(target).length > 0 ) {
				button.closest('.gdpr-toggle-panel').find('.gdpr-toggle-content').slideUp('slow');
				if ( panel_opened !== target ) {
					$(target).slideDown('slow');
					panel_opened = target;
				} else {
					panel_opened = '';
				}	
			}
		});

		$(document).on('click','#gdpr_remove_all_statistics',function(e) {
			var confirmText = "Are you sure you want to delete all the existing stats?";
			if( ! confirm( confirmText ) ) {
				e.preventDefault();
			}

		});

		$(document).on('click','#gdpr_remove_all_consent_logs',function(e) {
			var confirmText = "Are you sure you want to delete all the logs?";
			if( ! confirm( confirmText ) ) {
				e.preventDefault();
			}

		});

		function update_cookie_declaration_boxes( parent ) {
			var index = 0;
			var cookie_values = {};
			parent.find('.gdpr-cd-flexible .gdpr-cd-list').find('.gdpr-cd-box').each(function(){
				index++;
				var _this 				= $(this);
				var name 					= _this.find('td > .cd-name').val();
				var domain 				= _this.find('td > .cd-domain').val();
				var description 	= _this.find('td > .cd-description').val();
				var expiration 		= _this.find('td > .cd-expiration').val();

				_this.find('.cd-boxno').text(index);
				if ( name ) {
					var cookie_data = { 
				    name : name,
				    domain : domain,
				    desc : description,
				    exp : expiration
				  };
				  cookie_values[index] = cookie_data;
			  }
				
			});
			parent.closest('.gdpr-help-content-cnt').find('.gdpr-cd-inpval').val(JSON.stringify( cookie_values ) );
		}

		$(document).on('keyup change', '.gdpr-cookie-declaration-box input, .gdpr-cookie-declaration-box textarea',function(){
			var parent = $(this).closest('.gdpr-cookie-declaration-box');
			update_cookie_declaration_boxes( parent );
		});

		$('.gdpr-cd-list').each(function(){
			try	{
				$(this).sortable({
				  connectWith: $(this),
				  update: function(event, ui) {
				  	var parent = $(event.target).closest('.gdpr-cookie-declaration-box');
				    update_cookie_declaration_boxes(parent);
				  }
				});
			} catch( e ) {

			}
		});


		$(document).on('click','.gdpr-cookie-declaration-box .cd-add-new',function(e){
			e.preventDefault();
			var parent = $(this).closest('.gdpr-cookie-declaration-box');
			parent.find('.cd-box-layout > .gdpr-cd-box').clone().appendTo(parent.find('.gdpr-cd-flexible .gdpr-cd-list'));
			parent.find('.gdpr-cd-flexible .gdpr-cd-list').removeClass('cdempty').find('p.cdempty').remove();
			update_cookie_declaration_boxes( parent );
		});

		$(document).on('click','.gdpr-cookie-declaration-box .cd-remove',function(e){
			e.preventDefault();
			var parent = $(this).closest('.gdpr-cookie-declaration-box');
			$(this).closest('.gdpr-cd-box').remove();
			update_cookie_declaration_boxes( parent );
			if ( parent.find('.gdpr-cd-flexible .gdpr-cd-list').find('.gdpr-cd-box').length <= 0 ) {
				parent.find('.gdpr-cd-flexible .gdpr-cd-list').addClass('cdempty');
				parent.find('.cd-box-layout').find('p.cdempty').clone().appendTo(parent.find('.gdpr-cd-flexible .gdpr-cd-list'));
			}
			
		});

  });
})(jQuery);
