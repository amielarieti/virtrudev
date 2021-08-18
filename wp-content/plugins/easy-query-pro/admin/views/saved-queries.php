<div class="admin cnkt" id="ewpq-saved-queries">	
	<div class="wrap">
		<div class="header-wrap">
         <h1>
            <?php echo EQ_TITLE; ?>: <strong><?php _e('Saved Queries', 'easy-query'); ?></strong>
            <em><?php _e('Create, view or modify your saved custom queries.', 'easy-query'); ?></em>
         </h1>  
		</div>
		<?php
      	global $wpdb;
         $table_name = $wpdb->prefix . "easy_query";
         $rows = $wpdb->get_results("SELECT * FROM $table_name WHERE type = 'saved' ORDER BY id DESC"); // Get all saved queries       
      ?>
		<div class="cnkt-main <?php if(!$rows) echo 'full'; ?>">
		   
		   <!-- Display Query -->
		   		   
			<?php if(!$rows){ ?>  
			<div class="group postbox">
   			<div class="saved-query-alert">
			      <h2><?php _e('You don\'t have any saved queries!', 'easy-query'); ?></h2>
               <p><?php _e('Visit the <a href="options-general.php?page=easy-query&tab=query-builder">Query Builder</a> section or <a class="launch-create-modal" href="javascript:void(0);">create a query</a> now!', 'easy-query'); ?></p>
   			</div>
			</div>
			<?php } else { ?>
			<div class="postbox fake-sidebar saved-query-display">
   			<h3><?php _e('Your Saved Query', 'easy-query'); ?> <span id="query-id"></span></h3>
				<div class="group">
					<div class="saved-query-display--options">
					   <div>
						   <label class="template-title" for="query-alias">
							   <?php _e('Query Alias', 'easy-query'); ?>	
						   	<span><?php _e('Enter a unique and memorable alias for the query.', 'easy-query'); ?></span>
						   </label>
						   <input type="text" id="query-alias" name="query-alias" value="" />			
					   </div>	   
						<div>
						   <label class="template-title" for="query-shortcode">
						   	<?php _e('Shortcode', 'easy-query'); ?>
						   	<span><?php _e('Use the shortcode to render query in templates.', 'easy-query'); ?></span>
						   </label>
						   <input type="text" id="query-shortcode" class="disabled-input" name="query-alias" value="" disabled="disabled" />
						</div>
					</div>
					<div>
					   <label class="template-title" for="query-output">
					   	<?php _e('The Query', 'easy-query'); ?>
					   	<span><?php _e('Enter PHP and HTML code for this Query.', 'easy-query'); ?></span>
					   </label>
				      <textarea id="query-output" name="query-output"></textarea>		
					</div>
				</div>				   
   		   <div id="major-publishing-actions">
   			   <button data-id="" class="button button-primary update-saved-query" type="button"><?php _e('Update Query', 'easy-query'); ?></button>	
   			   <div class="saved-response">&nbsp;</div>	
               <div class="clear"></div>
            </div>
			</div>
			<?php } ?>
						   		   
	   </div>	
	      
	   <!-- List Saved Queries -->
	   <div class="cnkt-sidebar">
         
         <div class="table-of-contents">
   	      <div class="cta postbox query-header">
   			   <h3><?php _e('Saved Queries', 'easy-query'); ?></h3>
   			   <?php if($rows){ ?>
   			   <button class="button button-primary launch-create-modal launch-create-modal-header" type="button"><?php _e('New', 'easy-query'); ?></button>
   			   <?php } ?>
   			   <div class="cta-wrap">   
	   			   <?php if($rows){ ?>  
      			   <div class="query-list-wrap">                		
               		<ul class="query-list">
      	         	<?php
      	         		foreach( $rows as $query )  { 
               		   echo "<li><a href='javascript:void(0);' data-id='$query->id' title='$query->alias'>$query->alias</a><span title='".__('Delete Saved Query', 'easy-query'). "' class='fa fa-remove' data-remove='$query->id'></span></li>";
               		}?>
               		</ul>
               	</div>
            		<?php } else { ?> 
            		<p><?php _e('No queries to show.', 'easy-query'); ?></p>
            		<button class="button button-primary launch-create-modal" type="button"><?php _e('Create Query', 'easy-query'); ?></button>
            		<?php } ?>      			   
         	   </div>   
   	      </div>
         </div>	  
	      
	   </div>	   
	   	
	</div>
	
	<div class="clear"></div>
	
	<div class="eq-create-query-modal">		
		<div class="eq-create-query-modal--inner">
			<div>
				<h3><?php _e('Create New Query', 'easy-query'); ?></h3>
			</div>
			<div>
				<label for="create-alias"><?php _e('Alias', 'easy-query'); ?></label>
				<input type="text" id="create-alias" value="" placeholder="<?php _e('Enter a query alias', 'easy-query'); ?>">
			</div>
			<div>
				<button class="button button-primary create-saved-query" type="button"><?php _e('Create Query', 'easy-query'); ?></button>
				<button class="button cancel-saved-query" type="button" style="float: right;"><?php _e('Cancel', 'easy-query'); ?></button>
			</div>
		</div>		
	</div>
	
</div>