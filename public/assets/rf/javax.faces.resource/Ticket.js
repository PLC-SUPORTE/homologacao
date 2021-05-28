var Ticket = {

	'registerEvents': function () {

		// adiciona os eventos de mouse
		$('.taskPanelTitle').each(function(i, element){

		});

	},
		
	'addTask': function () {
		$('#addTaskForm').show();
		$('html,body').animate({
			scrollTop: $('#addTaskForm').offset().top }
		);
	},
	
	'validateNewTask': function(input) {
		// verifica se o input esta preenchido
		if(input.val() == '') {
			alert('Subtipo de atividade precisa ser definido.');
			return false;
		}

		return true;
	},

	'gotoTask': function(xhr, status, args) {

		// atualiza o contador das imagens selecionadas
		UploadManager.updateSelectedImagesCount();

		if(status == 'success') {
			if(typeof(args.anchorIndex) != 'undefined') {				
				$('#comments_' + args.anchorIndex + ' textarea').val("");

//				AjaxStatus.hide();
				window.location.hash = 'taskPanel_' + args.anchorIndex;

				$('#addTaskForm').hide();
				// reseta o form
				$('#addTaskForm select').val(1);
				$('#addTaskForm input').val("");
			}
		}
	},

	'save': function() {
		// salva os dados do ticket
		ticketSave.jq.click();
	},

	'list': function() {
		var urlParts = window.location.href.split('/');
		var type = urlParts[urlParts.length-1];
		type = 'list' + type.charAt(0).toUpperCase() + type.substring(1, type.indexOf('.'));

		switch(type) {
			case 'listOpenedTickets':
			case 'listVerifyingTickets':
			case 'listScheduledTickets':
			case 'listResolvedTickets':
			case 'listPendingTickets':
				Menu.selectItem(type);
				window[type]();
				break;

			default:
				refreshTicketsList();
				break;
		}
	},

	'listAll': function() {
		Ticket.beforeFilter();
		return void(refreshTicketsListAll());
	},

	'selectItem': function(item) {
		Menu.selectItem(item);
		return void(window.location.reload());
	},

	'beforeFilter': function(item) {
		if(typeof(item) != 'undefined') {
			Menu.selectItem(item);
		}

		$('.loading').show();
		$('#listTicketsContainer').hide();
		$('#ticketCount').html("...");
		$('.updatedDate').html("...");
	},

	'afterList': function (xhr, status, args) {
		$('.loading').hide();
		$('#listTicketsContainer').show();
		if(typeof(args) != 'undefined') {
			$('#ticketCount').html(args.totalList);
			$('.updatedDate').html(args.lastUpdated);
			
			if(document.getElementById('formListView:limit') != null) {
				var limit = document.getElementById('formListView:limit').value;
				if(args.totalList == limit) {
					$('#listAll').html('<a href="javascript:Ticket.listAll();">Mostrar todas as visitas</a>');
				}
			}
		}
	}
};

function Task(index, inEditMode, isMainTask, taskId, categoryId, subcategory) {

	this.index = index;
	this.taskId = taskId;
	this.editTask = $('#editTask_' + this.index);
	this.removeTask = $('#removeTask_' + this.index);
	this.confirmTaskEdit = $('#confirmTaskEdit_' + this.index);
	this.category = this.editTask.prev().prev().prev().val(categoryId);
	this.subcategory = this.editTask.prev().val(subcategory);
	this.categoryLabel = this.editTask.prev().prev().prev().prev();
	this.subcategoryLabel = this.editTask.prev().prev();
	this.mainTask = $('#mainTask_' + this.index);
	this.taskIdValue = $('#taskIdValue_' + this.index);
	this.isMainTask = isMainTask;

	this.taskPanel = $('#taskPanel_' + this.index);
	this.inEditMode = false;
	
	this.createIframePhotos = function () {

		var urls = [
		    {url: 'taskId='+ this.taskId +'&index='+ this.index +'&isPosterior=false', id: 'iframe_' + this.taskId + '_' + this.index + '_false'},
		    {url: 'taskId='+ this.taskId +'&index='+ this.index +'&isPosterior=true', id: 'iframe_' + this.taskId + '_' + this.index + '_true'}
		];

		for(var i=0; i<urls.length; i++) {
			if($('#' + urls[i]['id']).size() == 0) {
				var iframeHtml = '<div id="'+ urls[i].id +'" class="iframe-box" style="display: none; position: fixed; z-index: 2"><iframe name="'+ urls[i].id +'"  src="'+ appContext +'/pages/ticket/upload.xhtml?' + urls[i].url + '" frameborder="0" style="width: 640px; height: 300px; border: 0pt none; display: block;"></iframe></div>';
				$('#iframes').append(iframeHtml);
			}
		}
	};

	this.addMouseEvents = function() {

		if(this.isMainTask)
			return;

		var element = '#taskPanelTitle_' + this.index;
		var task    = this;

		$(element).mouseover(function(){
			task.showTitleOptions(true);
		});

		$(element).mouseout(function(){
			task.showTitleOptions(false);
		});

		var editTaskElement = '#editTask_' + this.index;
		var confirmaTaskElement = '#confirmTaskEdit_' + this.index;
		var removeTaskElement = '#removeTask_' + this.index;
		
		$(editTaskElement).click(function(){
			task.startEditMode();
		});
		
		$(confirmaTaskElement).click(function(){
			task.endEditMode(true);
		});
		
		$(removeTaskElement).click(function(){
			taskManager.removeTask(task.taskId, task.index);
		});
	};
	
	this.showTitleOptions = function(showOptions) {
		if (this.isMainTask) {
			this.editTask.hide();
			this.removeTask.hide();
			this.confirmTaskEdit.hide();
			return;
		}
		if (this.inEditMode) {
			this.editTask.hide();
			this.confirmTaskEdit.show();
			if (showOptions)
				this.removeTask.show();
			else
				this.removeTask.hide();
		} else {
			if (showOptions) {
				this.editTask.show();
				this.removeTask.show();
				this.confirmTaskEdit.hide();
			} else {
				this.editTask.hide();
				this.removeTask.hide();
				this.confirmTaskEdit.hide();
			}
		}
	};

	this.getCategoryValue = function() {
		return this.category.val();
	};

	this.getCategoryText = function() {
		if(typeof(this.category[0]) != 'undefined')
			return this.category[0].options[this.category[0].selectedIndex].text;

		return "";
	};

	this.getSubcategoryValue = function() {
		return this.subcategory.val();
	};

	this.startEditMode = function() {
		this.inEditMode = true;
		this.showTitleOptions(false);
		this.categoryLabel.hide();
		this.subcategoryLabel.hide();
		this.category.css({"display": "inline"});
		this.subcategory.value = this.categoryLabel.html();
		this.subcategory.css({"display": "inline"});
	};

	this.endEditMode = function(validate) {
		if (validate === true && this.editTask.prev().val() == '') {
			alert('Subtipo de atividade precisa ser definido.');
			return;
		}
		this.inEditMode = false;
		this.categoryLabel.html(this.getCategoryText());
		this.subcategoryLabel.html(this.getSubcategoryValue());
		this.showTitleOptions(false);
		this.categoryLabel.css({"display": "inline"});
		this.subcategoryLabel.css({"display": "inline"});
		this.category.hide();
		this.subcategory.hide();
	};

	this.clean = function() {
		this.subcategory.val("");
		this.category[0].selectedIndex = 0;
		this.categoryLabel.html("");
		this.subcategoryLabel.html("");
		this.taskIdValue.html("");
	};

	this.validate = function() {
		if (this.getCategoryValue() == '') {
			alert('Tipo da atividade precisa ser definido na atividade.');
			return false;
		}
		if (this.getSubcategoryValue() == '') {
			alert('Subtipo de atividade precisa ser definido na atividade "' + this.getCategoryText() + '".');
			return false;
		}
		return true;
	};

	this.changeEditMode = function(inEditMode) {
		if (inEditMode)
			this.startEditMode();
		else
			this.endEditMode();
	};

	if(!isMainTask) {
		this.changeEditMode(inEditMode);
	}

	this.mainTask.value = isMainTask;
}

function TaskManager() {

	this.tasks = new Array();
	this.lastIndex = 0;
	this.tasksPanelsId = "tasksPanel";

	this.addTask = function(task) {
		this.tasks.push(task);
		this.lastIndex++;
		$(document).ready(function() {
			task.createIframePhotos();
			task.addMouseEvents();
		});
	};

	this.removeTask = function(taskId, taskIndex) {
		if (confirm('Deseja realmente apagar esta atividade?')) {
			PF('removeTask' + taskIndex).jq.click();
		}
	};
	
	this.handleRemoveResponse = function(xhr, status, args, taskId, taskIndex){  
	    if(args.success == true) {  
	    	$('#taskPanel_' + taskIndex).remove();
			$('#iframe_' + taskId + '_' + taskIndex + '_false').remove();
			$('#iframe_' + taskId + '_' + taskIndex + '_true').remove();
	    }  
	};

	this.getTask = function(taskIndex) {
		return this.tasks[taskIndex];
	};

	this.deleteImage = function(taskImageId) {
		if (confirm('Deseja realmente excluir essa imagem? NÃ£o serÃ¡ possÃ­vel desfazer essa operaÃ§Ã£o.')) {
//			AjaxStatus.show();
			PF('removeImage' + taskImageId).jq.click();
			setTimeout(function(){ window.location.reload(); }, 1000);
		}
	};
};

var taskManager = new TaskManager();