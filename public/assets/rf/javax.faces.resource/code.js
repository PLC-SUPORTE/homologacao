var AjaxStatus = {
 
  'show': function() {
	  	$('.pointer').each(function(i, element){ $(element).hide(); });
		PF('statusDialog2').show();
  },
 
  'hide': function() {
	  $('.pointer').each(function(i, element){ $(element).show(); });
		PF('statusDialog2').hide();

		AjaxStatusManager.scrollTo();
  },
 
  'info': function (info) {
    $('#auxText').html(info);
  }
 
};
 

function center (element, width, height) {
    var win = $(window),
    left = (win.width() / 2 ) - (width / 2),
    top = (win.height() / 2 ) - (height / 2);

    $(element).css({
    	'left': left,
    	'top': top
    });
};

function enableModality(element) {
    $('#system-overlay').css({
        'width': $(document).width(),
        'height': $(document).height(),
        'z-index': $(element).css('z-index') - 1,
        'display': 'block'
    });
};

function gotoMessages() {
	/*$('html,body').animate({
		scrollTop: $('#messages').offset().top }
	);*/
//	AjaxStatus.hide();
}

function e(elementId) {
	return document.getElementById(elementId);
}

function randomIdParam() {
    try {
        return "randomNumberId=" + Math.floor(Math.random()*99999999);
    }catch (ex1) {
        try {
            return "randomNumber=" + new Date().getTime();
        } catch (ex2) {
            return "randomNumber=1";
        }
    }
}

/*function refreshInfoPanel() {
    var div = document.getElementById("infoPanel");
    //div.innerHTML = "<div class='loading'><img src='../images/loading.gif'> Aguarde. Carregando...</div>";
    $.ajax({
        url: "../ticket/infoPanel?" + randomIdParam(), 
        dataType: 'html', 
        async: true,
        success: function(html) {
            div.innerHTML = html;
        },
        error: function (request, textStatus, errorThrown) {
        }    
    });
}*/

function refreshAverageTimeAnalyseData() {
    var form = document.getElementById("filterForm");
    var div = document.getElementById("report");
    div.innerHTML = "<div class='loading'><img src='../images/loading.gif'> Aguarde. Carregando...</div>";
    $.ajax({
        url: "../ticket/averageTimeAnalyseData?" + randomIdParam(), 
        data:$(form).serialize(), 
        dataType: 'html', 
        async: true,
        success: function(html) {
            executeJavascriptsInHtmlString(html);
            div.innerHTML = html;
        },
        error: function (request, textStatus, errorThrown) {
        }    
    });
    return false;
}

function refreshTicketListData(asyncParam) {
    var form = document.getElementById("filterForm");
    var div = document.getElementById("listTickets");
    if (asyncParam)
        div.innerHTML = "<div class='loading'><img src='../images/loading.gif'> Aguarde. Carregando...</div>";
    $.ajax({
        url: "../ticket/listData?" + randomIdParam(), 
        data:$(form).serialize(), 
        dataType: 'html', 
        async: asyncParam,
        success: function(html) {
            executeJavascriptsInHtmlString(html);
            div.innerHTML = html;
        },
        error: function (request, textStatus, errorThrown) {
        }    
    });
    return false;
}

function exportTicketList() {
    var form = document.getElementById("filterForm");
    var url = "../ticket/exportData?" + randomIdParam() + "&encode=ISO-8859-1&" + $(form).serialize();
    document.location = url;
}

function cleanCombo(comboId) {
    var combo = document.getElementById(comboId);
    if (combo != null) {
        for (var i = combo.length - 1; i >= 0 ; i--) {
            combo.remove(i);
        }
    }
}

function formatDate(campo,teclapres){
    var tecla = teclapres.keyCode;
    vr = campo.value;
    vr = vr.replace( ".", "" );
    vr = vr.replace( "/", "" );
    vr = vr.replace( "/", "" );
    tam = vr.length + 1;

    if ( tecla != 9 && tecla != 8 ) {
        if ( tam > 2 && tam < 5 ) {
            campo.value = vr.substr( 0, tam - 2 ) + '/' + vr.substr( tam - 2, tam );
        }

        if ( tam >= 5 && tam <= 10 ) {
            campo.value = vr.substr( 0, 2 ) + '/' + vr.substr( 2, 2 ) + '/' + vr.substr( 4, 4 );
        }
    }
}

function showFilterDiv() {
    
    var filtersDisplay = document.getElementById('formListView:filters').style.display;
    if ( filtersDisplay == 'none' ) {
        filtersDisplay = "block";
       txtShowFilter = "Ocultar filtro <span class='arrow_up'></span>";
    } else {
        filtersDisplay = "none";
        txtShowFilter = "Exibir filtro <span class='arrow_down'></span>";
    }
    document.getElementById('showFilter').innerHTML = txtShowFilter;
    document.getElementById('formListView:filters').style.display = filtersDisplay;
    $('#listTickets').show();
    $('#generatedReport').hide();
}

/**
 * Executa todos os scripts de um determinado HTML passado como String. Usado para executar os scripts que vieram no HTML de uma chamada AJAX.
 */
function executeJavascriptsInHtmlString(html) {
    try {
        var source = html;
        var scripts = new Array();
        while (source.indexOf("<script") > -1 || source.indexOf("</script") > -1) {
            var s = source.indexOf("<script");
            var s_e = source.indexOf(">", s);
            var e = source.indexOf("</script", s);
            var e_e = source.indexOf(">", e);
            scripts.push(source.substring(s_e + 1, e));
            source = source.substring(0, s) + source.substring(e_e+1);
        }
        for (var x=0; x<scripts.length; x++) {
            try {
                eval(scripts[x]);
            } catch(e) {}
        }
    } catch (ex) {
        
    }
}

function passwordMatch(){
    var password, confirm, result;
    password = document.getElementById("user.password").value;
    confirm = document.getElementById("password.confirm").value;
    if (password == confirm) {
        document.getElementById("password.match").className = "sucess";
        document.getElementById("password.match").innerHTML = "Senha Confere!";
        result = true;
    } else {
        document.getElementById("password.match").className = "error";
        document.getElementById("password.match").innerHTML = "Senha n&atilde;o Confere!";
        result = false;
    }
    return result;
}

function validateTicketUpdateForm() {
	
	
	alert('aqui');
	return false;
    if (document.getElementById("addressType").selectedIndex == 0) {
        alert("Selecione o tipo de endereÃ§o e o logradouro.");
        return false;
    }
    if (document.getElementById("addressStreet").selectedIndex == 0) {
        alert("Selecione o logradouro.");
        return false;
    }
    if (verifySoSublists()) {
        removeInvalidsImages();
        return true;
    } else
        return false;
}



/**
 * Remove as imagens que estiverem sem arquivos selecionados. Isso precisa ser feito para que funcione no Safari, pois o safari nÃ£o envia
 * para o servidor no POST os inputs FILE que estiverem sem imagens, gerando distorÃ§Ã£o e erros no servidor.
 */
function removeInvalidsImages() {
    var i = 0;
    while (true) {
        var fileField = document.getElementsByName("images[" + i + "]");
        if (!fileField || fileField.length == 0)
            break;
        if (!fileField[0].value || fileField[0].value == "") {
            //Remove os inputs de imagem desse Ã­ndice
            var divImage = document.getElementById("ticketImage_" + i);
            divImage.innerHTML = "";
        }
        i++;
    }
}

function validateTicket() {
    
    var fields = new String("");
    
    var categoryCombo = document.getElementById("ticket.subject.category.categoryId");
    var categoryId = categoryCombo.options[categoryCombo.selectedIndex].value;
    
    if (categoryId == -1) {
        fields += "- Natureza\n";
        document.getElementById("verifyCategory").innerHTML = "*";
    }
    
    var subjectCombo = document.getElementById("ticket.subject.subjectId");
    
    if (subjectCombo.selectedIndex == -1){
        fields += "- Objeto Gerador\n";
        document.getElementById("verifySubject").innerHTML = "*";
    }
    
    if (document.getElementById("ticketList[0].addressType").selectedIndex <= 0) {
        fields += "- Tipo de EndereÃ§o\n";
        document.getElementById("verifyAddressType").innerHTML = "*";
    }

    if (document.getElementById("ticketList[0].addressStreet").selectedIndex <= 0) {
        fields += "- EndereÃ§o\n";
        document.getElementById("verifyAddressStreet").innerHTML = "*";
    }
    
    if (document.getElementById("ticketList[0].addressNumber").value == ""){
        fields += "- NÃºmero\n";
        document.getElementById("verifyAddressNumber").innerHTML = "*";
    }
    
    if (fields.length == 0){
        removeInvalidsImages();
        return true;
    } else {
        alert("Os campos: \n" + fields + "nÃ£o foram selecionados.");
        return false;
    }
}

function listAll(asyncParam){
    document.getElementById("limit").value = -1;
    document.getElementById("listAll").innerHTML = "";
    refreshTicketListData(asyncParam);
}

function validateUser(insert) {
    var fields = new String("");
    
    
    if (document.getElementById("user.name").value == ""){
        fields += "- Nome\n";
        document.getElementById("nameVerify").innerHTML ="*";
    }
    
    
    if (document.getElementById("user.email").value == ""){
        fields += "- E-mail\n";
        document.getElementById("emailVerify").innerHTML ="*";
    }
    
    
    if (document.getElementById("user.login").value == ""){
        fields += "- Login\n";
        document.getElementById("loginVerify").innerHTML ="*";
    }
    
    if (insert){
        if (document.getElementById("user.password").value == ""){
            fields += "- Password\n";
            document.getElementById("passorwdVerify").innerHTML ="*";
        }
    
    
        if (document.getElementById("password.confirm").value == ""){
            fields += "- ConfirmaÃ§Ã£o de Senha\n";
            document.getElementById("confirmVerify").innerHTML ="*";
        }
    }
    
    if (document.getElementById("password.confirm").value != document.getElementById("user.password").value) {
        alert("Senhas nÃ£o conferem");
        return false;
    }
    
    if (fields.length == 0){
        return true;
    }
    
    alert ("Os campos: \n" + fields + "nÃ£o foram selecionados.");
    
    return false;
}

function validateProfile(){
    var fields = new String("");
    
    if (document.getElementById("profile.description").value == ""){
        fields += "- DescriÃ§Ã£o.";
        document.getElementById("descriptionVerify").innerHTML = "*";
    }
    
    if (fields.length == 0){
        return true;
    }
    
    alert ("Os campos: \n" + fields + "nÃ£o foram selecionados.");
    
    return false;
    
}

function insertNewInsertInputFile(tableid, rowIndex, cellIndex){    
    var index = parseInt(document.getElementById("indexOfInputImage").value);
    document.getElementById("indexOfInputImage").value = index + 1;
    index = document.getElementById("indexOfInputImage").value;

    var html = "<br/><input type=\"file\" name=\"images["+ index + "]\"/> " +
        "<select name=\"ticketList[0].ticketImages[" + index + "].imageType\">" +
            "<option value=\"1\" selected>Foto</option>" +
            "<option value=\"2\">Documento</option>" +
        "</select>" +
        "<input type=\"hidden\" name=\"ticketList[0].ticketImages[" + index + "].externalFile.ExternalFileType\" value=\"1\" />" ;
    
    var div = document.createElement('span');
    div.guid = "ticketImage_" + index;
    div.innerHTML = html;

    var insertPanel = document.getElementById(tableid).rows[rowIndex].cells[cellIndex];
    insertPanel.appendChild(div);
}

function insertNewInputFile(divid){    
    var index = parseInt(document.getElementById("indexOfInputImage").value);
    document.getElementById("indexOfInputImage").value = index + 1;
    index = document.getElementById("indexOfInputImage").value;
    
    var html = "<br/><input type=\"file\" name=\"images[" + index + "]\"/>" +
        "<select name=\"ticket.ticketImages[" + index + "].imageType\">" +
            "<option value=\"1\" selected>Foto</option>" +
            "<option value=\"2\">Documento</option>" +
        "</select>" +
        "<input type=\"hidden\" name=\"ticket.ticketImages[" + index + "].externalFile.ExternalFileType\" value=\"1\" />" ;

    var div = document.createElement('span');
    div.guid = "ticketImage_" + index;
    div.innerHTML = html;
    var insertPanel = document.getElementById(divid);
    insertPanel.appendChild(div);
}

function verifyTrafficSoAlreadyExists(trafficSoNumber, ticketId) {
    try {
        var url = "../trafficSo/existsOthersWithSameSoNumberInOtherTickets?trafficSo.soNumber=" + trafficSoNumber + "&trafficSo.ticket.ticketId=" + ticketId + "&randonId=" + randomIdParam();
        $.getJSON(url, function(json) {
            if (json && json.recordExists && json.recordExists.exists == true)
                alert('JÃ¡ existe uma OS de TrÃ¢nsito com o nÃºmero de OS ' + trafficSoNumber + '.');
        });
    } catch(e) {
    }
}

function newOption(value, text, selectedValue) {
    var option = document.createElement("option");
    option.text = text;
    option.value = value;
    if (selectedValue == value)
        option.selected = true;
    return option;
}

function addOptionToSelect(selectObject, value, text, selectedValue) {
    selectObject.add(newOption(value, text, selectedValue), selectObject.options[null]);
}

function fillStreetNames(streetTypeSelectId, streetNameSelectId, selectedOption) {
    var streetNameSelect = document.getElementById(streetNameSelectId);
    var streetTypesSelect = document.getElementById(streetTypeSelectId);

    var typeIdx = streetTypesSelect.selectedIndex;
    var streetType = streetTypesSelect.options[typeIdx].value;
    var streetNames = getStreetNamesByStreetType(streetType);
    
    cleanCombo(streetNameSelectId);
    addOptionToSelect(streetNameSelect, "", "", "-");
    for (var i in streetNames)
        addOptionToSelect(streetNameSelect, streetNames[i], streetNames[i], selectedOption);
}

// MÃ©todo para popular a lista de tipos de endereÃ§o
function fillStreetTypes(streetTypeSelectId, selectedOption) {
    var types = getStreetTypes();
    var streetTypeSelect = document.getElementById(streetTypeSelectId);
    cleanCombo(streetTypeSelectId);
    addOptionToSelect(streetTypeSelect, "", "", "-");
    for (var i in types)
        addOptionToSelect(streetTypeSelect, types[i], types[i], selectedOption);
}

function getStreetNamesByStreetType(streetType) {
    var names = streetNames();
    var streets = [];
    if (!streetType || streetType == '')
        return streets;
    for (var i in names)
        if (names[i].indexOf(streetType) == 0)
            streets.push(names[i].substring(streetType.length + 1));
    return streets;
}

function getStreetTypes() {
    var names = streetNames();
    var types = [];
    for (var i in names) {
        var type = names[i].substring(0, names[i].indexOf(' '));
        var found = false;
        for (var t in types) {
            if (types[t] == type) {
                found = true;
                break;
            }
        }
        if (!found)
            types.push(type);
    }
    return types;
}

function showHide(original, edit, bool) {
	var e = document.getElementById(original);
	var f = document.getElementById(edit);
	if(bool == 1) {
		e.style.display = "none"; 
		f.style.display = "block";
	}
	if(bool == 0) {
		e.style.display = "block"; 
		f.style.display = "none";
	}	
}

function show(elementOrId) {
	if (typeof(elementOrId) == 'string')
		elementOrId = e(elementOrId);
	elementOrId.style.display = 'block'; 
}

function hide(elementOrId) {
	if (typeof(elementOrId) == 'string')
		elementOrId = e(elementOrId);
	elementOrId.style.display = 'none'; 
}

function waiting(elementOrId) {
	if (typeof(elementOrId) == 'string')
		elementOrId = e(elementOrId);
	elementOrId.innerHTML = '<div class="loading"><img src="../images/loading.gif" >Aguarde. Carregando...</div>'; 
}

function showInline(elementOrId) {
	if (typeof(elementOrId) == 'string')
		elementOrId = e(elementOrId);
	try{
		elementOrId.style.display = 'inline';
	} catch(e) {}
}

function replaceAll(string, token, newtoken) {
	while (string.indexOf(token) != -1) {
 		string = string.replace(token, newtoken);
	}
	return string;
}

function removeTextBetween(string, beginMark, endMark) {
	var idxBegin = string.indexOf(beginMark);
	if (idxBegin < 0)
		return string;
	var idxEnd = string.indexOf(endMark, idxBegin + beginMark.length);
	if (idxBegin >= 0 && idxEnd > 0) {
		var beginText = string.substring(0, idxBegin);
		var endText = string.substring(idxEnd + endMark.length);
		return beginText + endText;
	}
	return string;
}

function gotoNextPage() {
	var pageField = e("formListView:filterPageNumber");
	var value = pageField.value;
	value = parseInt(value) + 1;
	pageField.value = value;
	e('paggingPageNumber').innerHTML = pageField.value;
	//refreshTicketListData(true);

	// executa o filtro
	PF('filterAction').jq.click();
}

function gotoPreviousPage() {
	var pageField = e("formListView:filterPageNumber");
	var value = pageField.value;
	value = parseInt(value) - 1;
	if (value <= 0) {
		alert('VocÃª jÃ¡ estÃ¡ na primeira pÃ¡gina.');
		return;
	}
	pageField.value = value;
	e('paggingPageNumber').innerHTML = pageField.value;
	//refreshTicketListData(true);

	// executa o filtro
	PF('filterAction').jq.click();
}