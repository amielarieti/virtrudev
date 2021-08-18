var _ewpq_admin = _ewpq_admin || {};

jQuery(document).ready(function($) {
	"use strict"; 
		
	
	/*
	*  _ewpq_admin.copyToClipboard
	*  Copy shortcode to clipboard
	*
	*  @since 1.0.0
	*/     
	
	_ewpq_admin.copyToClipboard = function(text) {
		window.prompt ("Copy link to your clipboard: Press Ctrl + C then hit Enter to copy.", text);
	}
	
	// Copy link on shortcode builder
	$('.cta .copy').click(function(){
		var c = $('#shortcode_output').html();
		_ewpq_admin.copyToClipboard(c);
	});
	
	// Copy link on repeater templates
	$('.eq-dropdown .copy a').on('click', function(e){
		var container = $(this).closest('.repeater-wrap'), // find closet wrap
			 el = container.data('name'); // get template name
		
		var c = $('textarea', container).val(); // Get textarea val()
		_ewpq_admin.copyToClipboard(c);
	});	
	
	
	
	/*
	*  _ewpq_admin.resizeTOC
	*  Resize sidebar of shortcode builder
	*
	*  @since 1.0.0
	*/
	
	_ewpq_admin.resizeTOC = function(){      
      var tocW = $('.cnkt-sidebar').width();
      $('.table-of-contents').css('width', tocW + 'px'); 
   }
   _ewpq_admin.resizeTOC();
   
   $(window).resize(function() {
      _ewpq_admin.resizeTOC();
   });
   
   
   $(window).scroll(function(){
      _ewpq_admin.attachSidebar();
   });	
	
	
	
	/*
	*  _ewpq_admin.attachSidebar
	*  Attached sidebar to be fixed position
	*
	*  @since 1.0.0
	*/
   
   _ewpq_admin.attachSidebar = function(){
      if($('.table-of-contents').length){
         
         var scrollT = $(window).scrollTop(),
             target = 70; 
                  
         if((theTop - scrollT) < target)
            $('.table-of-contents').addClass('attached');
         else
            $('.table-of-contents').removeClass('attached');
            
      }
   }
      
   if($('.table-of-contents').length){
      $('body').scrollTop(0);
      var theTop = $('.table-of-contents').offset().top;
      _ewpq_admin.attachSidebar();
   }
	
	
	
	/*
	*  Shortcode builder controls
	*
	*  @since 1.0.0
	*/ 
	
	$('.nav-tab-wrapper a').click(function(e){
   	e.preventDefault();
	   var el = $(this),
	       tab = $('.tab-content'),
	       classname = 'nav-tab-active',
	       index = el.index();
	       
   	if(!el.hasClass('classname')){
   	   tab.hide();
   	   tab.eq(index).show();
   	   _ewpq_admin.attachSidebar();
         _ewpq_admin.resizeTOC();
      	el.addClass(classname).siblings('a').removeClass(classname);
   	}
	});
	
	
	
	/*
   *  Get layouts
   *
   *  @since 2.1
   */ 
   $(document).on('click', '.eq-layouts .eq-dropdown li a.layout', function(e){
      e.preventDefault();
      var el = $(this),
          type = el.data('type'),
          custom = (el.hasClass('custom')) ? 'true' : 'false',
          textarea = el.closest('.repeater-wrap').find('.CodeMirror'),
          layout_btn_text = el.html(),
          name = el.closest('.repeater-wrap').data('name');
          
      if(!el.hasClass('updating')){
         
         el.addClass('updating');
         textarea.addClass('loading');
         
         // Get editor ID
         var eid = '';         
         if(name === 'default'){ // Default Template  
            eid = window['editorDefault'];         			   
   	   }else{ // Repeater Templates   	   
            eid = window['editor_'+name]; // Set editor ID	      
   	   }
   	   
   	   // Get value from Ajax
   	   $.ajax({
      		type: 'GET',
      		url: ewpq_admin_localize.ajax_admin_url,
      		data: {
      			action   : 'eq_get_layout',
      			type     : type,
      			custom   : custom,
      			nonce    : ewpq_admin_localize.ewpq_admin_nonce,
      		},
      		dataType    : "JSON",
      		success: function(data) {  
         		
               eid.setValue(data.value);
               
               // Clear button styles				  
				   setTimeout(function() { 
                  el.text(ewpq_admin_localize.template_updated).blur();                                 
                  setTimeout(function() { 
                     el.removeClass('updating').blur();	// CLose drop menu
                     textarea.removeClass('loading');									
						}, 400);										
					}, 400);
               
               
      		},
      		error: function(xhr, status, error) {
         		console.log(status);
         		textarea.removeClass('loading');
      		}
      	});
   	}
      
   });

	
	
	
	/*
   *  Expand/Collapse shortcode headings
   *
   *  @since 1.0.0
   */ 
   
	$(document).on('click', '.cnkt h3.heading', function(){
		var el = $(this);
		if($(el).hasClass('open')){
			$(el).next('.expand-wrap').slideDown(100, 'cnkt_easeInOutQuad', function(){
				$(el).removeClass('open');
			});
		}else{
			$(el).next('.expand-wrap').slideUp(100, 'cnkt_easeInOutQuad', function(){
				$(el).addClass('open');
			});
		}
	});
	
	$(document).on('click', '.cnkt .toggle-all', function(){
      var el = $(this);
		if($(el).hasClass('closed')){
		   $(el).removeClass('closed');
         $('h3.heading').removeClass('open');
			$('.expand-wrap').slideDown(100, 'cnkt_easeInOutQuad');
		}else{
		   $(el).addClass('closed');
         $('h3.heading').addClass('open');
			$('.expand-wrap').slideUp(100, 'cnkt_easeInOutQuad');
		}
   });
   
   
   
   /*
   *  Back 2 Top
   *
   *  @since 2.0
   */ 
   $('.back2top a').on('click',  function(e){
      e.preventDefault();
      $('html,body').animate({ scrollTop: 0 }, 300);
      return false; 
   })
	
});
