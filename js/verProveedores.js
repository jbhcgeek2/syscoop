if(document.getElementById('updateInfoProv')){
  let btnSend = document.getElementById('updateInfoProv');

  btnSend.addEventListener('click',function(){
    //captamos los camposeditables

    let formEdit = new FormData();
    let nombre = document.getElementById("nombreProv").value;
    let rfcProv = document.getElementById("rfcProv").value;
    let telProv = document.getElementById("telProv").value;
    let direccionProv = document.getElementById("direccionProv").value;
    let idProv = document.getElementById("idProv").value;

    if(nombre != "" && rfcProv != "" && direccionProv != "" && telProv != ""){
      //procedemosa enviar los datos
      formEdit.append("nombreProvEdit", nombre);
      formEdit.append("rfcProvEdit", rfcProv);
      formEdit.append("telProvEdit", telProv);
      formEdit.append("direcProvEdit",direccionProv);
      formEdit.append("provIdEdit",idProv);


      let envio = new XMLHttpRequest();
      envio.open('POST','includes/operations/proveedores.php',false);
      envio.send(formEdit);

      if(envio.status == 200){
        if(envio.responseText == "updateProvComplete"){
          Swal.fire(
            'Actualizacion Correcta',
            'Se actualizo correctamente el proveedor',
            'success',
          ).then(function(){
            window.location = 'proveedores.php';
          })
        }else{
          //algo salio mal
          let error = envio.responseText.split("DataError|")[1];
          Swal.fire(
            'Algo salio mal',
            'Ver: '+error,
            'error'
          )
        }
      }else{
        //error de comunicacion
        Swal.fire(
          'Error',
          'Verifica tu conexion a internet',
          'warning'
        )
      }
    }else{
      Swal.fire(
        'Error',
        'Verirfica que los campos esten capturados de maneracorrecta',
        'error'
      )
    }

  });
}
