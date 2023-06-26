function LoadContent(frm) {
	$('#contenido').load(frm);
}
$('#contenido').load(frm);

$(function(){

    $.ajaxSetup({
         headers: { 'X-CSRF-Token' : $('meta[name=_token]').attr('content') }
});

	$('#btn-form').click(function(event) {
		event.preventDefault();

		var formId = '#myForm';

		$.ajax({
			url: $(formId).attr('action'),
			type: $(formId).attr('method'),
			data: $(formId).serialize(),
			dataType: 'html',
			success: function(result){
				if ($(formId).find("input:first-child").attr('value') == 'PUT') {
					var $jsonObject = jQuery.parseJSON(result);
	                                $(location).attr('href',$jsonObject.url);
				}
				else{
					$(formId)[0].reset();
					console.log('Ok');
					$("#msg-error").hide();					
				}
			},
			error: function(msg){
				console.log(msg.responseText);
				
				var obj= jQuery.parseJSON(msg.responseText);
				var req="";
				var salto="<br>";
				
				$.each( obj, function( key, value ) {
  					req = req.concat(value).concat(salto);
				});
				
				$("#msg").html(req);
				$("#msg-error").fadeIn();
			}
		});
	});

}); 