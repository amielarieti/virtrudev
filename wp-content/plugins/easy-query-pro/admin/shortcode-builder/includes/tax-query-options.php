<div class="taxonomy">	   
   <div class="control-row">				
      <div class="wrap-30 wrap-50">
      	<label class="full"><?php _e('Taxonomy:', 'ajax-load-more'); ?></label>
      	<select class="alm_element taxonomy-select">
      		<option value="" selected="selected">-- <?php _e('Select Taxonomy', 'ajax-load-more'); ?>--</option>
      	   <?php foreach( $taxonomies as $taxonomy ){ ?>
            <option name="chk-<?php echo $taxonomy->query_var; ?>" id="chk-<?php echo $taxonomy->query_var; ?>" value="<?php echo $taxonomy->query_var;?>"><?php echo $taxonomy->label; ?></option>
      	   <?php } ?>
         </select>
      </div>
   </div>
	
	<div class="taxonomy-extended">
   	<div class="tax-terms-select control-row">
         <div class="spacer lg"></div>
   		<label class="full"><?php _e('Taxonomy Terms:', 'ajax-load-more'); ?></label>
   		<div class="tax-terms-container checkboxes"></div>
   	</div>
	
      <div class="tax-operator-select control-row">
         <div class="spacer lg"></div>
         <div class="wrap-30 wrap-50" style="padding-left: 0;">
      		<label class="full"><?php _e('Taxonomy Operator:', 'ajax-load-more'); ?></label>
      		<select class="alm_element taxonomy-operator">
               <option value="IN" selected="selected">IN</option>
               <option value="NOT IN">NOT IN</option>
            </select>
         </div>
   	</div>
	</div>
   <div class="clear"></div>						
</div>
<a class="remove remove-tax-query" href="javascript:void(0);" title="<?php _e('Remove Taxonomy', 'ajax-load-more'); ?>">&times;</a>