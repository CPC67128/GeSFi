<h1><?= $translator->getTranslation('Renommer une désignation') ?></h1>

<form action="/" id="formRename">
<?= $translator->getTranslation('Désignation à modifier:') ?> <input type="text" name="designationSource" id="designationSource" size="30">
<br />
<br />
<?= $translator->getTranslation('Désignation de destination:') ?> <input type="text" name="designationDestination" id="designationDestination" size="30">
<br />
<br />
<input id="renameButton" value="<?= $translator->getTranslation('Renommer') ?>" type="button">
<input id="resetFormRename" name="reset" value="<?= $translator->getTranslation('Effacer') ?>" type="reset">
<div id="formRenameResult"></div>
</form>

<script type='text/javascript'>
$("#designationSource").addClass('search-textbox-label');

$("#designationSource").autocomplete({
	source: function( request, response ) {
		$.ajax({
			type: 'GET',
			url: "search_designation.php",
			contentType: "application/json; charset=utf-8",
			dataType: "json",
			data: {
					'search_string': request.term,
					'type': 0
				},
			success: function( data ) {
				response( $.map( data.items, function( item ) {
					return {
						label: item
					}
				}));
			},

			error: function(jqXHR, textStatus, errorThrown){
				alert(errorThrown);
			}

		});
	},
	minLength: 0,
	select: function( event, ui ) {
	}
});

$("#designationSource").focus(function(){
    if(this.value == $(this).attr('title')) {
        this.value = '';
        $(this).removeClass('search-textbox-label');
    }
});

// -----------------------------------------------------------------------------------------

$("#designationDestination").addClass('search-textbox-label');

$("#designationDestination").autocomplete({
	source: function( request, response ) {
		$.ajax({
			type: 'GET',
			url: "search_designation.php",
			contentType: "application/json; charset=utf-8",
			dataType: "json",
			data: {
					'search_string': request.term,
					'type': 0
				},
			success: function( data ) {
				response( $.map( data.items, function( item ) {
					return {
						label: item
					}
				}));
			},

			error: function(jqXHR, textStatus, errorThrown){
				alert(errorThrown);
			}

		});
	},
	minLength: 0,
	select: function( event, ui ) {
	}
});

$("#designationDestination").focus(function(){
    if(this.value == $(this).attr('title')) {
        this.value = '';
        $(this).removeClass('search-textbox-label');
    }
});

//-----------------------------------------------------------------------------------------

$("#renameButton").click( function () {
	document.getElementById("renameButton").disabled = true;
	$.post (
		'../controller/controller.php?action=designation_rename',
		$("#formRename").serialize(),
		function(response, status) {
			if (status == 'success') {
				if (response.indexOf("<!-- ERROR -->") >= 0) {
					$("#formRenameResult").html(response);
				}
				else {
					$("#formRenameResult").html(response);
					$("#resetFormRename").trigger("click");
				}
			}
			else {
				$("#formRenameResult").html(CreateUnexpectedErrorWeb("Status = " + status));
			}
			document.getElementById("renameButton").disabled = false;
		}
	);
});

</script>