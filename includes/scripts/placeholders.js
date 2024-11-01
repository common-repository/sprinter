(function ($) {
	jQuery.fn.setPlaceholders = function(){
	   var input = document.createElement("input");
	   if(('placeholder' in input) === false){
		  this.each(function(){
			 var placeholder = $(this).attr('placeholder');
			 $(this).val(placeholder);
			 $(this).bind('focus', function(){
				if ($(this).val() == placeholder){ $(this).val(""); }
			 }).bind('blur', function(){
				if ($(this).val() === ""){ $(this).val(placeholder); }
			 });
		  });
	   }
	};
})(jQuery);