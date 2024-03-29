<div class="control-row">
   <div class="wrap-30 wrap-50">
      <?php // Meta Key ?>
      <label for="meta-key" class="full"><?php _e('Key (Name):', 'easy-query'); ?></label>
      <input class="alm_element sm meta-key" name="meta-key" type="text" value="" placeholder="<?php _e('Enter custom field key(name)', 'easy-query'); ?>">   
   </div>             
   <?php // Meta Value ?>
   <div class="wrap-30 wrap-50">
      <label for="meta-value" class="full"><?php _e('Value:', 'easy-query'); ?></label>
      <input class="alm_element sm meta-value" name="meta-value" type="text" value="" placeholder="<?php _e('Enter custom field value', 'easy-query'); ?>">
   </div>   
   <div class="clear"></div>    
</div>
<div class="control-row">
   <?php // Meta Compare ?>           
   <div class="wrap-30 wrap-50 padding-top">
      <label for="meta-compare" class="full"><?php _e('Operator:', 'easy-query'); ?></label>
      <select class="alm_element meta-compare" name="meta-compare">
         <option value="IN" selected="selected">IN</option>
         <option value="NOT IN">NOT IN</option>
         <option value="BETWEEN">BETWEEN</option>
         <option value="NOT BETWEEN">NOT BETWEEN</option>
         <option value="=">= &nbsp;&nbsp; (equals)</option>
         <option value="!=">!= &nbsp; (does NOT equal)</option>
         <option value=">">> &nbsp;&nbsp; (greater than)</option>
         <option value=">=">>= &nbsp;(greater than or equal to)</option>
         <option value="<">&lt; &nbsp;&nbsp; (less than)</option>
         <option value="<=">&lt;= &nbsp;(less than or equal to)</option>
         <option value="LIKE">LIKE</option>
         <option value="NOT LIKE">NOT LIKE</option>
         <option value="EXISTS">EXISTS</option>
         <option value="NOT EXISTS">NOT EXISTS</option>
      </select>
   </div>   
   <?php // Meta Type ?>           
   <div class="wrap-30 wrap-50 padding-top">
      <label for="meta-type" class="full"><?php _e('Type:', 'easy-query'); ?></label>
      <select class="alm_element meta-type" name="meta-type">
         <option value="BINARY">BINARY</option>
         <option value="CHAR" selected="selected">CHAR</option>
         <option value="DATE">DATE</option>
         <option value="DATETIME">DATETIME</option>
         <option value="DECIMAL">DECIMAL</option>
         <option value="NUMERIC">NUMERIC</option>
         <option value="SIGNED">SIGNED</option>
         <option value="TIME">TIME</option>
         <option value="UNSIGNED">UNSIGNED</option>
      </select>
   </div> 
   <div class="clear"></div>    
</div>
<a class="remove remove-meta-query" href="javascript:void(0);" title="<?php _e('Remove Meta Query', 'ajax-load-more'); ?>">&times;</a>
