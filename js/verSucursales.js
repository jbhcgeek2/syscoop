function editSuc(sucData){
    console.log(sucData);
    let auxData = sucData.split("|");
    let idSuc = auxData[1];
    let nombreSuc = auxData[2];
    let estatusSuc = auxData[3];
    let statusSuc = '';

    //ahora mandamos llamar al modal de editar

    if(estatusSuc == "Activa"){
        statusSuc = 1;
    }else{
        statusSuc = 0;
    }

    document.getElementById('nameSucEdit').value = nombreSuc;
    document.getElementById('estatusSucEdit').value = statusSuc;
    document.getElementById('dataSucEdit').value = idSuc;

    var elemSel = document.querySelectorAll('select');
    var instanceSel = M.FormSelect.init(elemSel, options);

    M.updateTextFields();
}

let btnUpdateSuc = document.getElementById('sendUpdate');
btnUpdateSuc.addEventListener('click', function(){
    // metodo para actualizar el nombre y estatus de una sucursal

    let newNameSuc = document.getElementById('nameSucEdit').value;
    let newStatusSuc = document.getElementById('estatusSucEdit').value;
    let dataSuc = document.getElementById('dataSucEdit').value;

    if(newNameSuc != "" && newStatusSuc != "" && dataSuc != ""){
        //generamos el formData
        let datos = new FormData();
        datos.append("newNameSuc",newNameSuc);
        datos.append("newStatusSuc",newStatusSuc);
        datos.append("dataSuc",dataSuc);

        let envio = new XMLHttpRequest();
        envio.open('POST','../includes/operations/sucursales.php',false);
        envio.send(datos);
        
        if(envio.status == 200){
            let response = JSON.parfse(envio.responseText);
            if(response.status == 'ok'){
                //hubo todo bien
                Swal.fire(
                    'Sucursal Actualizada',
                    'Se actualizo la sucursal correctamente.',
                    'success'
                ).then(function(){
                    location.reload();
                })
            }else{
                //hubo un error
                Swal.fire(
                    'Ha ocurrido un Error',
                    response.mensaje,
                    'error'
                )
            }
        }else{
            //servidor inalcansable
            Swal.fire(
                'Error',
                'Servidor inalcanzable',
                'error'
            )
        }
    }else{
        //campos incompletos
        Swal.fire(
            'Campos invalidos',
            'Asegurate de capturar los campos correctamente',
            'error'
        )
    }
})