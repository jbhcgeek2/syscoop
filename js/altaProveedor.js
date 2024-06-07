let btnSaveProv = document.getElementById("saveProv");

btnSaveProv.addEventListener("click", function(){
  let formulario = new FormData(document.getElementById('formNewProv'));
  let valida = 0;
  //console.log(formulario);
  for(let [name, value] of formulario) {
    //validamos que los campos contengan informcion
    if(value.length == 0 || value == " "){
      document.getElementById(`${name}`).classList.add('campoMal');
      valida++;
    }
    //alert(`${name} = ${value}`); // key1 = value1, luego key2 = value2
  }//fin for validacion

  if(valida == 0){
    let envio = new XMLHttpRequest();
    envio.open("POST","includes/operations/proveedores.php",false);
    envio.send(formulario);

    if(envio.status == 200){
      if(envio.responseText == "ProveedorGuardado"){
        Swal.fire(
          'Proveedor Guardado',
          'Se capturo correctamente el proveedor',
          'success'
        ).then(function(){
          location.reload();
        })
      }else{
        //ocurrio un error
        console.log(envio.responseText);
        let error = envio.responseText.split("DataError|")[1];
        Swal.fire(
          'Operacion no procesada',
          'Ver: '+error,
          'error'
        )
      }
    }else{
      //error de comunicacion
      Swal.fire(
        'Algo salio mal',
        'Verifica tu conexion a internet',
        'warning'
      )
    }
  }else{
    //algun campo esta invalido
    Swal.fire(
      'Error',
      'Verifica que todos los campos contengan informacion',
      'error'
    )
  }
});