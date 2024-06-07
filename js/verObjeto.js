
let update = document.querySelectorAll(".updateCamnpo");

//update.addEventListener("change", function(){
  //alert(thid.id);
//})


function updateCampo(campo,valor){
  let poliza = document.getElementById("polizaOriginal").value;

  Swal.fire({
    title: 'Confirmacion',
    text:'Estas seguro de actualizar el campo',
    showCancelButton: true,
    showCancelButtonText: 'Actualizar',
    denyButtonText: 'No guardar',
  }).then((result) => {
    if(!result.dismiss){
      //realizamos el formulario y enviamos la modificacion
      let datos = new FormData();
      if(campo.split("|")[0] == "file_resguardo"){
        let archivo = document.getElementById(campo).files[0];
        datos.append("campoUpdate",campo);
        datos.append("valorCampo",archivo);
        datos.append("polizaUpdate",poliza);
      }else{
        datos.append("campoUpdate",campo);
        datos.append("valorCampo",valor);
        datos.append("polizaUpdate",poliza);
      }
      
      let envio = new XMLHttpRequest();
      envio.open('POST','../includes/operations/inventario.php',false);
      envio.send(datos);

      if(envio.status == 200){
        let res = envio.responseText;
        if(res == "UpdateSuccess"){
          if(campo.split("|")[0] == "resguardo_empleado"){
            //le recordamos actualizar el resguardo
            Swal.fire(
              'Accion requerida',
              'Recuerde actualizar el documento de resguardo a la nueva persona',
              'warning'
            ).then(function(){
              M.toast({
                html: 'Actualizacion completa',
                classes: 'orange',
              })
              location.reload();
            })
            
          }else{
            M.toast({
              html: 'Actualizacion completa',
              classes: 'orange',
            })
            location.reload();
          }
          
        }else{
          M.toast({
            html: 'Actualizada con errores',
            classes: 'red',
          })
        }
      }else{
        //error de comunicacion
      }

      
    }else{
      //no hacemos nada
    }
  });

}

let btnUpdateImg = document.getElementById("SendUpateImg");
btnUpdateImg.addEventListener("click", function(){
  let formImg = new FormData(document.getElementById("updateImgObj"));

  let enviar = new XMLHttpRequest();

  enviar.open("POST","../includes/operations/inventario.php",false);
  enviar.send(formImg);

  if(enviar.status == 200){
    if(enviar.responseText == "operationSuccess"){
      Swal.fire(
        'Operacion Exitosa',
        'Se actualizo correctamente la imagen',
        'success'
      ).then(function(){
        location.reload();
      })
    }else{
      let error = enviar.responseText.split("DataError|")[1];
      Swal.fire(
        'Error',
        'Verificar: '+error,
        'error'
      )
    }
  }else{
    Swal.fire(
      'Error de comunicacion',
      'Verifica tu conexion a internet',
      'error'
    )
  }

});

let btnAgregaObjeto = document.getElementById("btnAddObjeto");
btnAgregaObjeto.addEventListener('click', function(){
  //indicamos que agregar un objeto puede ocacionar errores en el objeto
  var objetoAgrega = document.getElementById("polizaOriginal").value;
  console.log("Valor: "+objetoAgrega);

  Swal.fire({
    title: 'Estas Seguro de Procesar?',
    text: 'Eso agregara un objeto del mismo modelo y afectara la tabla de depreciacion',
    icon: 'warning',
    showCancelButton: true,
    confirmButtonColor: '#3085d6',
    cancelButtonColor: '#d33',
    confirmButtonText: 'Si, Procesar',
  }).then((result)=> {
    console.log(result);
    if(result.value == true){
      //generamos el FormData
      let datos = new FormData();
      datos.append("objetoPadreAnexar",objetoAgrega);
      datos.append("anexaObjeto","anexar");

      let envia = new XMLHttpRequest();
      envia.open("POST","../includes/operations/inventario.php",false);
      envia.send(datos);

      if(envia.status == 200){
        //verificamos la respuesta
        let res = envia.responseText;
        if(res = "operationSuccess"){
          Swal.fire(
            'Operacion Exitosa',
            'Se inserto correctamente  el nuevo objeto',
            'success'
          ).then(function(){
            location.reload();
          })
        }else{
          //sucedio un error
          let err = res.split("|")[1];
          Swal.fire(
            'Error',
            'Verificar: '+err,
            'error'
          )
        }
        console.log(envia.responseText);
      }else{
        //no se resivio respuesta del servidor
        Swal.fire(
          'Servidor Inalcansable',
          'Verifica tu conexion a internet',
          'error'
        )
      }

    }else{
      //cancelamos la operacion
      
    }
  })
});

let btnBajaProd = document.getElementById("bajaObjetoTotal");
btnBajaProd.addEventListener("click", function(){
  //pedimos confirmacion de dar de baja y le pediremos el motivo de baja
  let poliza = document.getElementById("facturaIdMod").value;
  Swal.fire({
    title: 'Suspender registro?',
    text: 'Esta accion omitira el registro en la tabla de depreciaciones, indique el motivo de la suspencion',
    input: 'text',
    showCancelButton: true,
    confirmButtonText: "Dar de Baja",
  }).then((result) => {
    console.log(result);
    if(!result.dismiss){
      if(result.value != ""){
        //aqui si mandamos
        let motivo = result.value;
        let datos = new FormData();
        datos.append("facturaBaja",poliza);
        datos.append("motivoBaja",motivo);
        
        let enviar = new XMLHttpRequest();
        enviar.open("POST","../includes/operations/inventario.php", false);
        enviar.send(datos);

        if(enviar.status == 200){
          let res = enviar.responseText;
          if(res == "operationSuccess"){
            Swal.fire(
              'Operacion Exitosa',
              'Se suspendio el objeto correctamente',
              'success',
            ).then(function(){
              location.reload();
            })
          }else{
            //ocurrio un error
            let err = res.split("|")[1];
            Swal.fire(
              'Ha ocurrido un error',
              err,
              'error'
            ).then(function(){
              location.reload();
            })
          }
        }else{
          //error de servidor
          Swal.fire(
            'Servidor Inalcansable',
            'Verifica tu conexion a internet',
            'error'
          )
        }

      }else{
        //indico los campos vacios
        Swal.fire(
          'Motivo Requerido',
          'Debes indicar un motivo de baja valido',
          'warning'
        )
      }
    }else{
      console.log("Se cancela");
    }
    // console.log(result);
  })
})