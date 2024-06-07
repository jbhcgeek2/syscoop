
function validaObjetoInv(idAudi,idObj){
  //verificamos la informacion de la auditoria
  let idAuditoria = idAudi;
  let idObjeto = idObj;
  let observ = document.getElementById("observacionesAudiObj").value;
  let estadoObjeto = document.getElementById("estadoObjeto").value;
  let lugarOriginal = document.getElementById("lugarRes").value;
  let sucurOriginal = document.getElementById("sucRes").value;
  
  let datos = new FormData();
  datos.append("idVeri",idAuditoria);
  datos.append("obserValida",observ);
  datos.append("estadoObj",estadoObjeto);
  datos.append("idObjetoInventa",idObjeto);
  datos.append("lugarOriginal",lugarOriginal);
  datos.append("sucurOriginal",sucurOriginal);


  let enviar = new XMLHttpRequest();
  enviar.open("POST","../includes/operations/auditaInventario.php",false);
  enviar.send(datos);

  if(enviar.status == 200){
    if(enviar.responseText == "operationSuccess"){
      Swal.fire(
        'Registro correcto',
        'Se inserto correctamente la validacion',
        'success'
      ).then(function(){
        window.location = 'ver-auditoria.php?info='+idAuditoria;
      })
    }else{
      //ocurrio un error al registrar el objeto en la auditoria
      let error = enviar.responseText.split("DataError|")[1];
      Swal.fire(
        'Ha ocurrido un error',
        'Verificar: '+error,
        'error'
      )
    }
  }else{
    Swal.fire(
      'Servidor Inalcansable',
      'Verifica tu conexion a internet',
      'error'
    )
  }
}


if(document.getElementById("fecBaja")){
  Swal.fire(
    'Objeto en Baja',
    'Este objeto ya fue dado de baja con anterioridad',
    'warning'
  )
}

