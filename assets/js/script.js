jQuery(document).ready(function($){
	$divstr = '#popup-message, #popup-info';

	if($($divstr).length > 0){
		$popup = $('#popup-message');
		$popup.css('margin-left', ($popup.width() / 2 * -1) +'px');

		if($('.error').length > 0 && $('.success').length > 0) {
			$('.error').css({marginLeft: '0px', left: '0'});
			$('.success').css({marginLeft: '0px', left: 'auto', right: '2%'});
		}

		$speed = 1000;
		$div = $($divstr);
		$div.slideDown($speed);
		setTimeout(function(){
			$div.slideUp($speed);
		}, 4000)
	}

	if($('.all-plugins input[type=checkbox]').length > 0){
		$checked = false;
		$('#check-all-plugins').click(function(){
			$target = $('.all-plugins input[type=checkbox]');
			if($checked == false){
				$target.prop('checked', true);
				$checked = true;
				$(this).val('Avmarkera alla');
			}
			else{
				$target.prop('checked', false);
				$checked = false;
				$(this).val('Markera alla');
			}
		});

		$('#check-default-plugins').click(function(){
			$('.all-plugins input[type=checkbox]').prop('checked', false);
			$('.all-plugins input[type=checkbox].prechecked').prop('checked', true);
		});
	}



});