
//Function administrateur
function get_data_admin(elem){
	console.log('get_data_admin');
	var dataForm = 'ctxt='+elem;

	$.ajax({
		type: "POST",
		processData: true,
		url: './ajax/ajx_data_admin.php',
		data: dataForm,
		dataType: 'html'
	})
	.done(function( Data ) {
	 	//console.log('succes');
	 	//console.log(Data);
	 	 $('#wrapper_admin').hide('slow', function(){
		 	 $('#wrapper_admin').html(Data);
		 	 $('#wrapper_admin').show('slow');
	 	 });
	 	  	
	 })
	.fail(function(Data) {
		console.log("erreur load data container ctxt -> "+ctxt);
		console.log(Data);
	});
}
