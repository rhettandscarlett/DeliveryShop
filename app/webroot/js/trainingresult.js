(function ($) {
	$(document).ready(function () {
		$('.showCollapse').click(function () {
			var me = this;
			var target = $(me).data('target');
			$(target).collapse('show');
		});
		$('.hideCollapse').click(function () {
			var me = this;
			var target = $(me).data('target');
			$(target).collapse('hide');
		});

		$('.collapse').on('shown.bs.collapse', function () {
			var img = $(this).find('img')[0];
			if(img){
				var src = $(img).data('realsrc');
				if (src.length > 0) {
					$(img).attr('src', src);
					$(img).data('realsrc', '');
				}
			}
		});
	});
})(jQuery);
