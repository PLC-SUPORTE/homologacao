var AjaxStatusManager = {

	'onStart': function() {
		$('.pointer').each(function(i, element){ $(element).hide(); });
		PF('statusDialog2').show();
	},

	'onComplete': function() {
		$('.pointer').each(function(i, element){ $(element).show(); });
		PF('statusDialog2').hide();

		AjaxStatusManager.scrollTo();
	},
	
	'scrollTo': function(){
		if($.trim($('#globalMessages').html()) != '') {
		    $('html, body').animate({
		        scrollTop: $("#globalMessages").offset().top
		    }, 500);
		}
	}

};