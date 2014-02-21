<script type='text/javascript'>
$("#form").submit( function () {
	document.getElementById("submitForm").disabled = true;
	alert('yes');
	$.post (
		'../controller/controller.php?action=<?php echo $page_name; ?>',
		$(this).serialize(),
		function(response, status) {
			$("#formResult").stop().show();
			if (status == 'success') {
				if (response.indexOf("<!-- ERROR -->") >= 0) {
					alert('no error');
					$("#formResult").html(response);
				}
				else {
					alert('change context');
					ChangeContext_Page('records');
				}
			}
			else {
				alert('else');
				$("#formResult").html(CreateUnexpectedErrorWeb("Status = " + status));
			}
			document.getElementById("submitForm").disabled = false;

			setTimeout(function() {
				$("#formResult").fadeOut("slow", function () {
					$('#formResult').empty();
				})
			}, 4000);
		}
	);
	return false;
});

$( "#datePicker" ).datepicker({
	showOn: "both",
	buttonImage: "calendar.gif",
	buttonImageOnly: true,
	dateFormat: "yy-mm-dd"
});
</script>