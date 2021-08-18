var almPro = almPro || {};

jQuery(document).ready(function($) {
	"use strict";
	
	
	/*
    *  updateActivationTotal()
    *  Total Activations
    *
    *  @since 1.0
    */
	almPro.updateActivationTotal = function(){
   	let target = $('.alm-pro-listing--header span.num');
   	let addons = $('.alm-pro-listing .item');
   	let count = 0;
   	$(addons).each(function(e){
      	let status = $(this).attr('data-status');
      	if(status === 'active'){
         	count++;
      	}
   	});
   	target.text(count);
	};
	
	if($('.alm-pro-listing').length) almPro.updateActivationTotal();
	
	
	/*
    *  Activate/Deactive add-ons
    *  @since 1.0
    */
   let is_working = false;
   $(document).on('click', '.alm-pro-listing .item a.installed', function(e){
      e.preventDefault();
      
      if(is_working) return false;
      $('.alm-pro-listing').addClass('loading');
      is_working = true;
      var el = $(this),
      	 parent = el.parent(),
          status = parent.data('status'),
          slug = parent.data('slug');
          
	   $.ajax({
   		type: 'POST',
   		url: alm_admin_localize.ajax_admin_url,
   		data: {
   			action: 'alm_pro_toggle_activation',
   			nonce: alm_admin_localize.alm_admin_nonce,
   			slug: slug
   		},
   		success: function(data) {
	   		if(data.result === 'active'){
   	   		parent.attr('data-status', 'active');
		   		parent.addClass('active');
		   		parent.removeClass('inactive');
		   		$('.result .active', parent).show();
		   		$('.result .inactive', parent).hide();
		   		$('.result', parent).addClass('in-view');
		   		setTimeout(function(){
   		   		$('.result', parent).removeClass('in-view');
   		   	}, 1000);
		   		
	   		} else {
   	   		parent.attr('data-status', 'inactive');
		   		parent.removeClass('active');
		   		parent.addClass('inactive');
		   		$('.result .active', parent).hide();
		   		$('.result .inactive', parent).show();
		   		$('.result', parent).addClass('in-view');
		   		setTimeout(function(){   		   		
   		   		$('.result', parent).removeClass('in-view');
   		   	}, 1000);
	   		}
	   		
	   		// Remove flag
	   		setTimeout(function(){
   	   		is_working = false;
   	   		$('.alm-pro-listing').removeClass('loading');
   	   		almPro.updateActivationTotal();
            }, 500);
	   		
   		},
   		error: function(xhr, status, error) {
	   		is_working = false;
	   		$('.alm-pro-listing').removeClass('loading');
	   		almPro.updateActivationTotal();
      		console.log(status);
   		}
   	});

   });
	
});