jQuery(document).ready(function($) {

   /*
   *  Activate License
   *
   *  @since 1.1
   */ 
   
   var isActivating = false;
   $(document).on('click', '.cnkt-license-btn', function(e){	   
      e.preventDefault();
      if(!isActivating){
	      $('.cnkt-license-wrap .msg').remove();
	      isActivating = true;
	      var el = $(this),
	      	 wrap = el.closest('.cnkt-license-btn-wrap'),
	      	 parent = el.closest('.cnkt-license'),
	      	 type = el.data('type'),
	      	 item = wrap.data('name'),
	      	 url = wrap.data('url'),
	      	 upgrade = wrap.data('upgrade-url'),
	      	 status = wrap.data('option-status'),
	      	 key = wrap.data('option-key'),
	      	 license = parent.find('input[type=text]').val();
	      	 
			$('.loading', parent).fadeIn(300);
	   	   
		   // Get value from Ajax
		   
		   $.ajax({
	   		type: 'GET',
	   		url: ewpq_admin_localize.ajax_admin_url,
				dataType: 'json',	   		
	   		data: {
	   			action: 'ewpq_license_activation',
	   			nonce: ewpq_admin_localize.ewpq_admin_nonce,
	   			type: type,
	   			item: item,
	   			status: status,
	   			url: url,
	   			upgrade: upgrade, 
	   			key: key,
	   			license: license,
	   		},
	   		
	   		success: function(data) { 
		   		
		   		if(data['msg']){
			   		$('.cnkt-license-wrap', parent).append('<div class="msg">'+data['msg']+'</div>');
		   		}
		   		
		   		if(data['license'] === 'valid'){
			   		$('.cnkt-license-key-field .status', parent).addClass('active').removeClass('inactive').text(ewpq_admin_localize.active);
			   		$('.cnkt-license-title .status', parent).addClass('valid').removeClass('invalid');
			   		$('.activate.cnkt-license-btn', parent).addClass('hide');
			   		$('.deactivate.cnkt-license-btn', parent).removeClass('hide');
			   		$('.no-license', parent).slideUp(200);	
			   		
		   		}else{
			   		$('.cnkt-license-key-field .status', parent).removeClass('active').addClass('inactive').text(ewpq_admin_localize.inactive);
			   		$('.cnkt-license-title .status', parent).removeClass('valid').addClass('invalid');	
			   		$('.activate.cnkt-license-btn', parent).removeClass('hide');
			   		$('.deactivate.cnkt-license-btn', parent).addClass('hide');	  			   		
			   		$('.no-license', parent).slideDown(200);	 		
			   		$('.expiry', parent).slideUp(200);
		   		}
		   		
					$('.loading', parent).delay(250).fadeOut(300);
					isActivating = false;
	            
	   		},
	   		error: function(xhr, status, error) {
	      		console.log(status);
	      		$('.loading', parent).delay(250).fadeOut(300);
	      		isActivating = false;
	   		}
	   	});
   	}
   	
   });
   
   
});