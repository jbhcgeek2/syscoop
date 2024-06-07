var btnEnviar = document.getElementById('enviaForm');

btnEnviar.addEventListener('click', function(){
  let formulario = new FormData(document.getElementById('formNewObj'));
  let valida = 0;
  //console.log(formulario);
  for(let [name, value] of formulario) {
    //validamos que los campos contengan informcion
    if(value.length == 0 || value == " "){
      //no pasa
      console.log(`${name}`);
      document.getElementById(`${name}`).classList.add('campoMal');
      valida++;
    }
    //alert(`${name} = ${value}`); // key1 = value1, luego key2 = value2
  }//fin for validacion

  if(valida == 0){
    //preparamos para enviatr el formulario

    let envio = new XMLHttpRequest();
    envio.open("POST","includes/operations/inventario.php",false);
    envio.send(formulario);

    if(envio.status == 200){
      let respuesta = envio.responseText;
      if(respuesta == "operationSuccess"){
        Swal.fire(
          'Objeto guardado',
          'Se guardo correctamente el objeto',
          'success'
        ).then(function(){
          location.reload();
        });
      }else{
        //ocurrio un error al realizar el movimiento
        let errorMsg = respuesta.split("DataError|")[1];
        Swal.fire(
          'Error',
          'Verificar: '+errorMsg,
          'error'
        );
      }
    }else{
      //fallo de comunicacion
      Swal.fire(
        'Verifica tu conexion',
        'Algo salio mal al conectar con el servidor',
        'warning'
      );
    }
  }else{
    //no pasa, verificar valores
    Swal.fire(
      'Error',
      'Verificar la informacion capturada',
      'error'
    );
  }
});//fin boton enviar formulario


let polizaInput = document.getElementById("polizaRegistro");
polizaInput.addEventListener("change", function(){
  if(polizaInput.value.trim() != "" || polizaInput.value.trim() != " "){
    let dato = new FormData();
    dato.append("checkPoliza",polizaInput.value);

    let envio = new XMLHttpRequest();
    envio.open("POST","includes/operations/inventario.php",false);

    envio.send(dato);
    if(envio.status == 200){
      if(envio.responseText == "noPoliInsert"){

      }else if(envio.responseText == "poliExist"){
        //la poliza ya existe
        Swal.fire(
          'Poliza Duplicada',
          'La poliza ingresada ya existe, verifiquelo',
          'error'
        )
        polizaInput.value = "";
      }else{
        let error = envio.responseText.split("DataError|")[1];
        Swal.fire(
          'Error',
          'Ocurrio un error al verificar la poliza indicada',
          'warning'
        )
      }
    }else{
      Swal.fire(
        'Verifica tu conexion',
        'Algo salio mal al conectar con el servidor',
        'warning'
      )
    }
  }
})
