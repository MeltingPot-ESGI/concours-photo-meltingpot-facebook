
//Function administrateur
function get_data_admin(action, params){
    console.log(action, params);
    var dataForm = 'action='+action+'&params='+params;

    $.ajax({
        type: "POST",
        processData: true,
        url: './ressource/ajax/ajx_data_admin.php',
        data: dataForm,
        dataType: 'html'
    })
    .done( function (Data) {
        $('#wrapper_admin').hide('slow', function(){
            $('#wrapper_admin').html(Data);
            $('#wrapper_admin').show('slow');
        });
     })
    .fail( function (Data) {
        console.log("Erreur avec l'action : "+action);
        console.log(Data);
    });
}
