var imagesToUpload = 0;

var UploadManager = {

	showDialog: function (id) {
		var iframeName = 'iframe_' + id;
		var dialog = $('#' + iframeName);
		enableModality(dialog);
		center(dialog, 640, 300);
		dialog.show();

		var iframeInstance = window.frames[iframeName];
		if(typeof(iframeInstance) == 'undefined' || iframeInstance.UploadManager.selectedFiles() == 0) {
			// abre a tela de selecao de fotos caso nao haja fotos selecionadas!
			$(iframeInstance.PF('upload').buttonBar.children()[0]).children()[2].click();
		}
	},

	hideDialog: function (id) {
		var dialog = $('#iframe_' + id);
		$('.ui-widget-overlay').hide();
		dialog.hide();
	},

	updateSelectedImagesCount: function() {
		for(var i=0; i<window.frames.length; i++) {
			if(typeof(window.frames[i]) != 'undefined') {
				try {
					window.frames[i].UploadManager.updateSelectedImagesCount();
				} catch (error) {}
			}
		}
	},

	getTotalFilesToUpload: function() {
		var count = 0;
		for(var i=0; i<window.frames.length; i++) {
			if(typeof(window.frames[i]) != 'undefined') {
				try {
					count += window.frames[i].UploadManager.selectedFiles();
				} catch (error) {}
			}
		}
		return count;
	},

	start: function(saveAndClose) {

		var totalFilesToUpload = UploadManager.getTotalFilesToUpload();

		if(totalFilesToUpload > 0) {

			AjaxStatus.show();
			$('#' + PF('generalProgress').guid).show();

			// prepara o progress
			window['progress'] = setInterval(function() {

				var uploadManagerFilesToUpload = UploadManager.getTotalFilesToUpload();
				
				var current = totalFilesToUpload - uploadManagerFilesToUpload;
				if(current <= 0) {
					current = 1;
				}

				AjaxStatus.info('Enviando imagem ' + current + ' de ' + totalFilesToUpload);
				var percentual = (current * 100) / totalFilesToUpload;

				// incrementa o progress
				PF('generalProgress').setValue(percentual);  

	            if(percentual == 100 && uploadManagerFilesToUpload == 0) {
	                clearInterval(window['progress']);
	                
	                // salva o ticket
	                if(!saveAndClose) {
	                	PF('ticketSave').jq.click();
	                } else {
	                	PF('ticketSaveAndClose').jq.click();
	                }
	                
	                window.location.hash = 'page';
    				window.location.reload(true);
	            }

	        }, 500);

			for(var i=0; i<window.frames.length; i++){
				if(typeof(window.frames[i]) != 'undefined') {
					try {
						window.frames[i].PF('upload').buttonBar.children()[1].click();
					} catch (error) {  }
				}
			}
			
		} else {

            if(!saveAndClose) {
            	PF('ticketSave').jq.click();

            } else {
            	PF('ticketSaveAndClose').jq.click();
            }

		}

	},

	finish: function(xhr, status, args) {
		if (typeof(args) != 'undefined' && typeof(args.hasError) == 'undefined') {
			// recarrega a pagina
			window.location.hash = 'page';
			window.setTimeout(function(){
				window.location.reload(true);
			}, 1000);
		}
		if(typeof(args.validationFailed) != 'undefined') {
			AjaxStatus.hide();
			$('html,body').animate({
				scrollTop: $('#messages').offset().top }
			);
			return;
		}

//		setTimeout(function(){
//			AjaxStatus.hide();
//		}, 2000);
//
//		AjaxStatus.info("");
//		generalProgress.setValue(0);

		// recarrega a pagina
//		window.location.hash = 'page';
//		window.setTimeout(function(){
//			window.location.reload(true);
//		}, 1000);
	}
};