(function($) {
	if (!$.exist) {
		$.extend({
			exist: function(elm) {
				if (typeof elm == null || elm == undefined) return false;
				
				if (typeof elm == "object" && elm instanceof jQuery && elm.length) {
					if ($.contains(document.documentElement, elm[0])) return true;
				}
				else if (typeof elm == "string") {
					if ($(elm).length) return true; 
				}
				
				return false;
			}
		});
		$.fn.extend({ exist: function() { return $.exist($(this)); } });
	}
})(jQuery);