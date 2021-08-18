var drops = drops || {};
jQuery(document).ready(function($) {
   "use strict"; 
    
    drops.dropdown = function(e) {
        var el = e.parent();
        var dropdown = $('.eq-dropdown', el);
        var text = $('input[type="text"]', el);
        
        if($(el).hasClass('active')){//If is currently active, hide it
            el.removeClass('active');
            $('.eq-dropdown', el).removeClass('active');
            return false;
        }
        else if($('.eq-dropdown').hasClass('active')){
            $('.eq-dropdown').each(function(i){
                $(this).removeClass('active');
                $(this).parent().removeClass('active');
            });
        }    
        
        $('.eq-dropdown').removeClass('active');//remove active states from currently open dropdowns
        el.addClass('active');
        $('.eq-dropdown', el).addClass('active');
        text.focus(); //Focus on input boxes
        
        $(window).unbind('click').bind('click', drops.closeDropDown); // Bind click event to site container   
        
        dropdown.unbind('click').bind('click', function(event){
            //event.stopPropagation(); 
        }); 
        //http://stackoverflow.com/questions/10439779/closing-modal-popup-by-clicking-away-from-it
    };
   
   drops.closeDropDown = function() {
      $('.eq-dropdown').each(function(i) {
         $(this).removeClass('active');
         $(this).parent().removeClass('active');
      });
   };    
   
   $(document).on('click', '.eq-options a.target', function(){
      var e = $(this);
      drops.dropdown(e);
      return false;
   });
});