var _ewpq_admin = _ewpq_admin || {};

jQuery(document).ready(function($) {
   "use strict";  
   
   if($('#query-output').length){
	   var queryGenerator = CodeMirror.fromTextArea(document.getElementById("query-output"), {
          mode:  "application/x-httpd-php",
          lineNumbers: true,
          lineWrapping: true,
          indentUnit: 0,
          matchBrackets: true,
          viewportMargin: Infinity,
          extraKeys: {"Ctrl-Space": "autocomplete"},
      });
   }
   
   
   
   /*
   *  query_generator
   *  Generate a unique WP_Query
   *
   *  @since 1.0.0
   */
   
   _ewpq_admin.buildQuery = function(data){
      var placement = $('#query-output'),
          container_type = $('input[name=container_type]:checked').val(),
          classes = $('input#classes').val();
          
     	if(classes.length){
	     	classes = ' class="'+classes+'"';
     	}       
        
      // Post Types  
      var post_types = '',
          post_type_count = 0;
      $('.post_types input[type=checkbox]:checked').each(function(e){         
         post_type_count++;
         if(post_type_count>1){
            post_types += ",'" + $(this).data('type') +"'";
         }else{
            post_types += "'" + $(this).data('type') + "'"; 
         }
      });        
      
      // Category In
      var cat = $('#category-select').val(); 
      var category_type = $('.categories input[name=category-select-type]:checked').val();  
      if(cat ){
         var category = '',
         	 category__type_count = 0;
			$(cat).each(function(e){         
	         category__type_count++;
	         category += (category__type_count > 1) ? ",'" + cat[e] +"'" : "'" + cat[e] + "'";
	      });
		}
		 
		// Category not in  
      var cat_not_in = $('#category-exclude-select').val();
      if(cat_not_in){
         var category__not_in = '',
         	 category__not_in_count = 0;
			$(cat_not_in).each(function(e){         
	         category__not_in_count++;
	         if(category__not_in_count>1){
	            category__not_in += ",'" + cat_not_in[e] +"'";
	         }else{
	            category__not_in += "'" + cat_not_in[e] + "'"; 
	         }
	      });
		}
      
      // Tag in
      var tag = $('#tag-select').val(); 
      var tag_type = $('.tags input[name=tag-select-type]:checked').val();
      if(tag){
         var tags = '',
         	 tag_count = 0;
			$(tag).each(function(e){         
	         tag_count++;
	         tags += (tag_count > 1) ? ",'" + tag[e] +"'" : "'" + tag[e] + "'";
	      });
		}
        
      // Tag not in   
      var tag_not_in = $('#tag-exclude-select').val();
      if(tag_not_in){
         var tag__not_in = '',
         	 tag__not_in_count = 0;
			$(tag_not_in).each(function(e){         
	         tag__not_in_count++;
	         if(tag__not_in_count>1){
	            tag__not_in += ",'" + tag_not_in[e] +"'";
	         }else{
	            tag__not_in += "'" + tag_not_in[e] + "'"; 
	         }
	      });
		}
      
      // Taxonomy Query Obj
      var tax_query = {
         'taxonomy' : '',
         'taxonomy_terms' : '',
         'taxonomy_operator' : '',
         'relation' : $('select.tax-relation').val()
      }
      // Loop all tax query
      $('.tax-query-wrap').each(function(e){
         var el = $(this);
         var taxonomy = $('select.taxonomy-select', el).val();
         var taxonomy_operator = $('select.taxonomy-operator', el).val(); 
         
         var tax_term_count = 0;
         var taxonomy_terms = '';
         $('.tax-terms-container input[type=checkbox]', el).each(function(e){         
            if($(this).is(":checked")) {
               tax_term_count++;
               if(tax_term_count > 1){
                  taxonomy_terms += ', ' + $(this).data('type');
               }else{
                  taxonomy_terms += $(this).data('type');     
               }
            }
         });                
         
         if(taxonomy && taxonomy_operator && taxonomy_terms){            
            tax_query.taxonomy += (tax_query.taxonomy.length) ? ':' + taxonomy : taxonomy;
            tax_query.taxonomy_terms += (tax_query.taxonomy_terms.length) ? ':' + taxonomy_terms : taxonomy_terms;   
            tax_query.taxonomy_operator += (tax_query.taxonomy_operator.length) ? ':' + taxonomy_operator : taxonomy_operator;         
         }                
      });     
      
      
		// Date Query
      var year = $('#input-year').val();
      var monthnum = $('#input-month').val();
      var day = $('#input-day').val();
      
      
      // Meta Query Obj
      var meta_query = {
         'key' : '',
         'value' : '',
         'compare' : '',
         'type' : '',
         'relation' : $('select.meta-relation').val()
      }
      // Loop all meta query
      $('.meta-query-wrap').each(function(e){
         var el = $(this);
         var key = $.trim($('.meta-key', el).val());
         var value = $.trim($('.meta-value', el).val());
         var compare = $('select.meta-compare', el).val();
         var type = $('select.meta-type', el).val();         
         if(key && value && compare && type){            
            meta_query.key += (meta_query.key.length) ? ':' + key : key;
            meta_query.value += (meta_query.value.length) ? ':' + value : value;
            meta_query.compare += (meta_query.compare.length) ? ':' + compare : compare;
            meta_query.type += (meta_query.type.length) ? ':' + type : type;            
         }                
      });
      
      var author = $('#author-select').val();
      
      var search = $.trim($('#search-term').val());
      
      var custom_args = $.trim($('#custom-args').val());
      
      var post_status = $('#post-status').val();
      
      var order = $('#post-order').val();
      var orderby = $('#post-orderby').val();
      
      var include_posts = $('#include-posts').val();
		if(include_posts) 
		   var include_posts = include_posts.split(',');
      
      var exclude = $('#exclude-posts').val();
		if(exclude) 
		   exclude = exclude.split(',');
		   
      var offset = $('#offset-select').val();
      if(offset === '') 
         offset = 0;
            
      var posts_per_page = $('#display_posts-select').val();
         if(posts_per_page == 0)
            posts_per_page = "-1";
      
      // Paging Styles and Settings
      var is_paged = $('.paging input[name=enable_paging]:checked').val();  
      var paging_style = ' paging-style-'+ $('select#paging-style-select').val();
      var paging_color = ' '+ $('select#paging-color-select').val(); 
      var paging_arrows = '';
      if(document.getElementById('chk-show-arrows').checked) {
         paging_arrows = '';
      } else {
         paging_arrows = ' no-arrows';
      }  
      if(is_paged != 'true'){
         paging_style = '';
         paging_color = '';
         paging_arrows = '';
      }
      
      
      // ************************
   	// Build the query	
      // ************************
      
      var $q = '';
      $q += "<?php ";
      $q += "\n";      
      $q += "/* \n";
      $q += " * Easy Query Shortcode\n";
      $q += " * "+$('#shortcode_output').html() +"\n";
      $q += " */";
      
      $q += "\n";  
      $q += "\n";      
      $q += "$paged = (get_query_var('paged')) ? get_query_var('paged') : 1; \n";
      
      $q += "$args = array(\n";
      
      $q += "  'post_type' => array("+ post_types + "), \n";
     
		// Category 
      if(category)
      $q += "  '"+ category_type +"' => array("+ category + "), \n";     
      
      // Cat Not In
      if(category__not_in)
      $q += "  'category__not_in' => array("+ category__not_in + "), \n"; 
     
      // Tag
      if(tags)
      $q += "  '" + tag_type +"' => array("+ tags + "), \n";    
      
      // Tag Not In
      if(tag__not_in)
      $q += "  'tag__not_in' => array("+ tag__not_in + "), \n";  
     
      // Date
      if(year)
      $q += "  'year' => '"+ year + "', \n";   
     
      if(monthnum)
      $q += "  'monthnum' => '"+ monthnum + "', \n";   
     
      if(day)
      $q += "  'day' => '"+ day + "', \n"; 
      
      
      // Taxonomy      
      if(tax_query.taxonomy.length && tax_query.taxonomy_terms.length && tax_query.taxonomy_operator.length){
	      
	      var tax = tax_query.taxonomy.split(':');
         var tax_terms = tax_query.taxonomy_terms.split(':');
         var tax_operator = tax_query.taxonomy_operator.split(':');
         
         $q += "  'tax_query' => array(\n";         
         
         // Relation
         if(tax.length > 1){
            $q += "      'relation' => '"+ tax_query.relation +"',\n"
         }      
         for(var m = 0; m < tax.length; m++){
	         
	         // Parse tax terms into array
	         var tax_terms_parsed = tax_terms[m].split(',');
	         var tax_terms_result = '';
	         for(var tt = 0; tt < tax_terms_parsed.length; tt++){
		         if(tt > 0){
			         tax_terms_result += ',';
		         }
		         tax_terms_result += "'"+ tax_terms_parsed[tt].trim() +"'";
		      }
	         
            $q += "      array(\n";
            $q += "          'taxonomy' => '"+ tax[m] + "', \n";
				$q += "          'field'    => 'slug', \n";
            $q += "          'terms'    => array("+ tax_terms_result + "), \n";
            $q += "          'operator' => '"+ tax_operator[m] + "', \n";       
            $q += "      ),\n";
         }   
         $q += "  ),\n";
	   
	   }
      
      
      // Custom Fields (Meta Query)
      if(meta_query.key.length && meta_query.value.length && meta_query.compare.length && meta_query.type.length){
         
         var meta_key = meta_query.key.split(':');
         var meta_value = meta_query.value.split(':');
         var meta_compare = meta_query.compare.split(':');
         var meta_type = meta_query.type.split(':');
         
         $q += "  'meta_query' => array(\n";
         
         
         // Relation
         if(meta_key.length > 1){
            $q += "      'relation' => '"+ meta_query.relation +"',\n"
         }      
         for(var m = 0; m < meta_key.length; m++){
            $q += "      array(\n";
            $q += "          'key'     => '"+ meta_key[m] + "', \n";
            $q += "          'value'   => '"+ meta_value[m] + "', \n";
            $q += "          'compare' => '"+ meta_compare[m] + "', \n";   
            $q += "          'type'    => '"+ meta_type[m] + "', \n";    
            $q += "      ),\n";
         }   
         $q += "  ),\n";
         
      }
     
      // Author
      if(author)
      $q += "  'author' => '"+ author + "', \n";
     
      // Search
      if(search)
      $q += "  's' => '"+ search + "', \n";
     
      // Custom Args
      if(custom_args){
         var custom_args_arr = custom_args.split(';'); // Split all args
         for(var i = 0; i < custom_args_arr.length; i++){
            var custom_argument = custom_args_arr[i].split(':'); // Split current argument
            $q += "  '"+custom_argument[0]+"' => '"+ custom_argument[1] + "', \n";
         }
      }
      //$q += "  's' => '"+ search + "', \n";
      
      // Include Posts
      if(include_posts)
      $q += "  'post__in' => array("+ include_posts + "), \n";
      
      // Exclude Posts
      if(exclude)
      $q += "  'post__not_in' => array("+ exclude + "), \n";
      
      // Post Status
      if(post_status)
      $q += "  'post_status' => '"+ post_status + "', \n";     
      	
      // Order
      if(order)
      $q += "  'order' => '"+ order + "', \n";      
      
      // OrderBy	
      if(orderby)
      $q += "  'orderby' => '"+ orderby + "', \n";
      	
      // Posts Per Page	
      if(posts_per_page)      
      $q += "  'posts_per_page' => "+ posts_per_page + ", \n";
      
      // Paged
      $q += "  'paged' => $paged, \n";
      $q += ");\n";
      
      // Offset
      if(offset > 0){ 
         $q += "\n";
         $q += "// Offset/Pagination fix \n";        
         $q += "$page = $paged - 1; \n";
         $q += "$offset = "+offset+"; \n";
         $q += "$posts_per_page = "+posts_per_page+"; \n";
         $q += "if($offset > 0){ \n";
         $q += "   $args['offset'] = $offset + ($posts_per_page*$page); \n";
         $q += "}\n";
      }      
      
      $q += "\n";
      
      // WP_QUERY 
      $q += "// WP_Query";
      $q += "\n";
      $q += "$eq_query = new WP_Query( $args );";
      $q += "\n";
      $q += "if ($eq_query->have_posts()) : // The Loop";
      $q += "\n";
      $q += "$eq_count = 0;";
      $q += "\n";
      $q += "?>";
      $q += "\n";
      $q += "<div class=\"wp-easy-query"+paging_style+""+paging_color+""+paging_arrows+"\">"
      $q += "\n";
      $q += "<div class=\"wp-easy-query-posts\">"
      $q += '\n';
      $q += '<' + container_type + classes +'>';
      $q += "\n";
      $q += "<?php ";
      $q += "\n";
      $q += "while ($eq_query->have_posts()): $eq_query->the_post();"; 
      $q += "\n";
      $q += "$eq_count++;"; 
      $q += "\n";  
      $q += "?>";
      $q += "\n";
      $q += data;
      $q += "\n"; 
      $q += "<?php endwhile; wp_reset_query(); ?> ";   
      $q += "\n"; 
      $q += "</" + container_type + ">"   
      $q += "\n"; 
      $q += "</div>";
      $q += "\n";
      
      // Paging
      if(is_paged === 'true'){
         $q += "<?php include(EQ_PAGING); ?>";
         $q += "\n";     
      } 
      
      $q += "</div>"
      $q += "\n";
      
      $q += "<?php endif; ?> ";
      
      // Set CodeMirror and textarea Val
      queryGenerator.setValue($q);      
      placement.val($q);      
   	$('.CodeMirror').removeClass('loading');
	}
	
	
	
	/*
    *  _ewpq_admin.getTemplateValue
    *  Get value of template from DB for placement in query generator
    *  
    *  @since 1.0.0
    */  
	
	_ewpq_admin.getTemplateValue = function(template) {	   							
		$.ajax({
			type: 'POST',
			url: ewpq_admin_localize.ajax_admin_url,
			data: {
				action: 'ewpq_query_generator',
				template: template,
				nonce: ewpq_admin_localize.ewpq_admin_nonce,
			},
			success: function(response) {	
			   var data = response;
			   _ewpq_admin.buildQuery(data);							
			},
			error: function(xhr, status, error) {
            console.log('An error has occurred while retrieving template data.');
            $('.CodeMirror').removeClass('loading');
			}
      });
      
	}
	
	// Generate query button click
	$('#generate-query').click(function(e){
   	$('.CodeMirror').addClass('loading');
   	e.preventDefault();
   	var template = $('select#template-select').val();	
   	_ewpq_admin.getTemplateValue(template);
	});	
	
	
	
	/*
    *  _ewpq_admin.saveQuery
    *  Save the value of the wp_query
    *  
    *  @since 1.0.0
    */  
	
	_ewpq_admin.saveQuery = function(value, alias) {	   							
		$.ajax({
			type: 'POST',
			url: ewpq_admin_localize.ajax_admin_url,
			data: {
				action: 'ewpq_save_query',
				value: value,
				alias: alias,
				nonce: ewpq_admin_localize.ewpq_admin_nonce,
			},
			success: function(response) {	
   			var responseTxt = $('.save-query-wrap .saved-response-text');
            $('.save-query-wrap input').val('');
            $('.save-query-wrap .saving').delay(150).fadeOut(250, function(){
               $('.save-query-wrap').removeClass('saving');	
               $('.CodeMirror').removeClass('loading');		
               responseTxt.show().text(response);
               setTimeout(function(){ 
                  responseTxt.hide().text(''); 
               }, 3000);
            });					
			},
			error: function(xhr, status, error) {
            console.log('An error has occurred while saving your query.');
			}
      });
	}
	
	
	
	// Generate query button click
	$('#ewpq-save-query').click(function(e){
   	e.preventDefault();
   	var alias_field = $('input#ewpq_query_alias'),
   		 value = queryGenerator.getValue(),
   		 alias = $.trim(alias_field.val());
   		 
		if(alias === ''){
			alias_field.addClass('error').focus();
			return false;
		}else{
   		$('.save-query-wrap').addClass('saving');
         $('.CodeMirror').addClass('loading');		
			alias_field.removeClass('error');
   		$('.save-query-wrap .saving').delay(50).fadeIn(250, function(){
            _ewpq_admin.saveQuery(value, alias);	
			});		
		}		
	});	
	
	
	
	/* Saved Queries */
	
	
	
	/*
    *  _ewpq_admin.viewSavedQuery
    *  Save the value of the wp_query
    *  
    *  @since 1.0.0
    */  
	
	_ewpq_admin.viewSavedQuery = function(id, alias) {	   							
		$.ajax({
			type: 'POST',
         dataType: "JSON",
			url: ewpq_admin_localize.ajax_admin_url,
			data: {
				action: 'ewpq_view_saved_query',
				id: id,
				nonce: ewpq_admin_localize.ewpq_admin_nonce,
			},
			success: function(response) {	 
            queryGenerator.setValue(response.template);
            $('#query-shortcode').val('[easy_query id="'+ id +'"]');
            $('#query-alias').val(response.alias);
            $('.update-saved-query').attr('data-id', id);
            $('.CodeMirror').removeClass('loading');
            $('span#query-id').html('ID: '+ id);			
			},
			error: function(xhr, status, error) {
            console.log('An error has occurred while saving your query.');
			}
      });
	}
	
	
	
	// Generate query button click
	$('ul.query-list li a').click(function(e){
	   var el = $(this);
	   if(!el.parent('li').hasClass('active')){ 
      	e.preventDefault();
      	var id = el.data('id'),
      		 alias = el.data('alias');
      	el.parent('li').addClass('active').siblings('li').removeClass('active');
      	$('.CodeMirror').addClass('loading');
      	_ewpq_admin.viewSavedQuery(id, alias);	
   	}
	});
	
	
	
	/*
    *  _ewpq_admin.saveQuery
    *  Save the value of the wp_query
    *  
    *  @since 1.0.0
    */  
	
	_ewpq_admin.deleteSavedQuery = function(id, el, parent) {	   							
		$.ajax({
			type: 'POST',
			url: ewpq_admin_localize.ajax_admin_url,
			data: {
				action: 'ewpq_delete_saved_query',
				id: id,
				nonce: ewpq_admin_localize.ewpq_admin_nonce,
			},
			success: function(response) {	
			   var data = response;	  
			   if(parent.hasClass('active')){
			   	window.location.reload();
			   } else {
				   parent.remove();
			   }
			},
			error: function(xhr, status, error) {
            console.log('An error has occurred while deleting your query.');
			}
      });
	}
	
	
	
	// Generate query button click
	$('ul.query-list li span').on('click', function(e){
      var el = $(this);
          parent = el.parent('li');
      var r = confirm("Are you sure you want to delete this saved query?");
      if (r == true && !$(this).hasClass('deleting')) {
         el.addClass('deleting');
         if(parent.hasClass('active')){
         	$('.CodeMirror').addClass('loading');
        		parent.css('opacity', 0.25);
         }
      	var id = el.data('remove');
      	_ewpq_admin.deleteSavedQuery(id, el, parent);
   	}
	});
	
	
	
	/*
    *  _ewpq_admin.updateSavedQuery
    *  Update the value of the wp_query
    *  
    *  @since 1.0.0
    */  
	
	_ewpq_admin.updateSavedQuery = function(id, alias, value) {	
		var container = $('.saved-query-display'),
			 responseText = $(".saved-response", container);
			 
      responseText.addClass('loading').html('Updating query...');
      responseText.animate({'opacity' : 1});   
			 							
		$.ajax({
			type: 'POST',
			url: ewpq_admin_localize.ajax_admin_url,
			data: {
				action: 'ewpq_update_saved_query',
				id: id,
				alias: alias,
				value: value,
				nonce: ewpq_admin_localize.ewpq_admin_nonce,
			},
			success: function(response) {	
			   var data = response;			
			   $('.CodeMirror').removeClass('loading');	
			   setTimeout(function() { 
				   responseText.delay(500).html(response).removeClass('loading');				
			   }, 250);
			  						  
			   setTimeout(function() { 
				   responseText.animate({'opacity': 0}, function(){
					   responseText.html('&nbsp;');
                  $('.update-saved-query').removeClass('saving');
				   });
					
				}, 3000);	
				
				$(".query-list li a[data-id='"+ id +"'").text(alias); // Update navigation text
				$(".query-list li a[data-id='"+ id +"'").attr('title', alias); // Update title text
						
			},
			error: function(xhr, status, error) {
            console.log('An error has occurred while deleting your query.');
			}
      });
	}
	
	// Generate query button click
	$('.update-saved-query').on('click', function(e){
      var el = $(this),
      	 id = el.attr('data-id'),
      	 alias = el.closest('.saved-query-display').find('#query-alias').val(),
      	 value = queryGenerator.getValue();
      if(!el.hasClass('saving')){
	      el.addClass('saving');	 
	      $('.CodeMirror').addClass('loading');
	      _ewpq_admin.updateSavedQuery(id, alias, value);
      }   	
	});
	
	// Load first query on load
	if($('ul.query-list li').length) $('ul.query-list li').eq(0).find('a').trigger('click');
	
	
		
	
	
	
	/*
    *  _ewpq_admin.createQuery
    *  Create a query
    *  
    *  @since 2.3
    */  
	
	var creatingQuery = false;
	_ewpq_admin.createQuery = function(alias) {	   							
		$.ajax({
			type: 'POST',
			url: ewpq_admin_localize.ajax_admin_url,
			data: {
				action: 'ewpq_create_query',
				alias: alias,
				nonce: ewpq_admin_localize.ewpq_admin_nonce,
			},
			success: function(response) {	
            setTimeout(function(){ 
               creatingQuery = false;
               location.reload();
            }, 1000);					
			},
			error: function(xhr, status, error) {
				location.reload();
            console.log('An error has occurred while creating your query.');
			}
      });
	}
	// Generate query button click
	$('button.create-saved-query').on('click', function(e){
		if(!creatingQuery){
			var alias = $('input#create-alias').val();
			if(alias.trim() === ''){
				$('input#create-alias').focus();
				return false;
			}
			$('.eq-create-query-modal--inner').addClass('loading');
			creatingQuery = true;
      	_ewpq_admin.createQuery(alias);      	
   	}
	});
	
	// Launch Modal
	$('button.launch-create-modal, a.launch-create-modal').on('click', function(e){
		if(!creatingQuery){
			$('.eq-create-query-modal').addClass('active');
			setTimeout(function(){ 
			$('.eq-create-query-modal input').focus();    
			}, 250); 	
   	}
	});
	
	// Close Modal
	$('button.cancel-saved-query').on('click', function(e){
		$('.eq-create-query-modal').removeClass('active');
		$('button.launch-create-modal').focus();
	});
	
	
});