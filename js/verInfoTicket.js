document.addEventListener('DOMContentLoaded', function() {
  var elems = document.querySelectorAll('.collapsible');
  var instances = M.Collapsible.init(elems, options);
});


let campoComent = document.getElementById("newComent");
campoComent.addEventListener('change', function(){
  //seccion para insertar un comentario
  let comentario = campoComent.value;
  let ticket = document.getElementById("infoTicket").value;
  
  let datos = new FormData();
  datos.append("coment",comentario);
  datos.append("ticket", ticket);

  let envio = new XMLHttpRequest();

  envio.open("POST","../includes/operations/tickets.php",false);
  envio.send(datos);

  if(envio.status == 200){
    let res = envio.responseText;
    if(res == "OperationSuccess"){
      //comentario insertado
      Swal.fire(
        'Comentario Insertado',
        'Se inserto el comentario',
        'Success'
      ).then(function(){
        location.reload();
      })
    }else{
      //error al insertar el comentario
      let err = res.split("DataError|");
      Swal.fire(
        'Error en el proceso',
        'Verificar: '+err,
        'error'
      )
    }
  }else{
    //error en la insersion de datos

  }
})

if(document.getElementById("btnTomarTicket")){
  let btnTomar = document.getElementById('btnTomarTicket');
  btnTomar.addEventListener("click", function(){
    //preguntamos si realmente desea tomar el ticket
    function loaderSwal(){
      Swal.fire({
        title: 'Actualizando Datos',
        text: 'Espere un momento',
        icon: 'warning',
      })
    }
    Swal.fire({
      title: 'Deseas tomar este ticket?',
      text: 'Una vez aceptando no podra cancelar el proceso.',
      iconHtml: '?',
      confirmButtonText: 'Si, tomar',
      cancelButtonText: 'No, Cancelar',
      showCancelButton: true,
    }).then((result)=>{
      if(result.isConfirmed){
        let datos = new FormData();
        let idTicket = document.getElementById("infoTicket").value;
    
        datos.append("tomarTicket",idTicket);
    
        let enviar = new XMLHttpRequest();
        enviar.addEventListener('loadstart', loaderSwal);
        enviar.open('POST','../includes/operations/tickets.php',false);
        enviar.send(datos);
    
        if(enviar.status == 200){
          let res = enviar.responseText;
          if(res == "OperationSuccess"){
            //decimos que se asigna el ticket
            Swal.fire(
              'Operacion Exitosa',
              'Se ha finalizado correctamente la operacion',
              'success'
            ).then(function(){
              location.reload();
            })
          }else{
            //ha fallado el proceso
            let err = res.split("DataError|")[1];
            Swal.fire(
              'Ha ocurrido un error',
              'Reportar a sistemas: '+err,
              'error'
            )
          }
          // console.log(enviar.responseText);
        }else{
          //error de comunicacion con el servidor
          Swal.fire(
            'Servidor Inalcansable',
            'Verifica tu conexion a internet',
            'error'
          )
        }
      }else{
        //no procedemos
      }
    })
    
  })//fin de la funcion
}//fin btnTomarTicket


let statusTicket = document.getElementById('estatusTicket');
statusTicket.addEventListener('change', function(){
  //seccion para actualizar el estatus de los tickets
  function loaderSwal(){
    Swal.fire({
      title: 'Actualizando Datos',
      text: 'Espere un momento',
      icon: 'warning',
    })
  }
  let newStatus = statusTicket.value;
  let ticketData = document.getElementById('infoTicket').value;
  Swal.fire({
    title: 'Cambio de estatus',
    text: 'Estas seguro de cambiar el estatus del ticket?',
    icon: 'warning',
    showCancelButton: true,
    confirmButtonText: 'Si, Cambiar',
    denyButtonText: 'Cancelar'
  }).then((result) => {
    if(result.isConfirmed){
      //seccion para cmabiar el estatus\
      let datos = new FormData();
      datos.append("statusUpdate",newStatus);
      datos.append("ticketChange",ticketData);

      let envio = new XMLHttpRequest();
      envio.addEventListener('loadstart', loaderSwal);
      envio.open("POST","../includes/operations/tickets.php",false);
      envio.send(datos);

      if(envio.status == 200){
        let res = envio.responseText;
        if(res == "OperationSuccess"){
          Swal.fire(
            'Estatus Actualizado',
            'Se modifico correctamente el estatus',
            'success'
          ).then(function(){
            location.reload();
          })
        }else{
          let err = res.split("DataError|")[1];
          Swal.fire(
            'Ha ocurrido un error',
            'Verificar: '+err,
            'error'
          )

        }
      }else{
        //error de comunicacion
        Swal.fire(
          'Servidor Inalcansable',
          'Veririca tu conexion a internet',
          'error'
        )
      }
      

    }else{
      // alert('se cancela');
    }
  })
});


let btnUploadDoc = document.getElementById('btnUploadAnexo');
btnUploadDoc.addEventListener('click', function(){
  //seccion para subir un documento al ticket

  let anexo = document.getElementById('nuevoAnexoTicket').files[0];
  // console.log(anexo);
  // let file = anexo.files[0];
  let ticketAnexo = document.getElementById('infoTicket').value;
  let datos = new FormData();
  datos.append("docAnexo",anexo);
  datos.append("AnexoTicket",ticketAnexo);

  let enviar = new XMLHttpRequest();
  enviar.open('POST','../includes/operations/tickets.php',false);
  enviar.send(datos);

  if(enviar.status == 200){
    let res = enviar.responseText;

    if(res == "OperationSuccess"){
      Swal.fire(
        'Documento Subido',
        'Se ha procesado el documento correctamente',
        'success'
      ).then(function(){
        location.reload();
      })
    }else{
      let err = res.split("DataError|")[1];
      Swal.fire(
        'Ha ocurrido un error',
        'Verificar: '+err,
        'error'
      )
    }
    console.log(enviar.responseText);
  }else{
    //error de comunicacion
    Swal.fire(
      'Servidor Inalcansable',
      'Verirfica tu conexion a internet',
      'error'
    )
  }

})

if(document.getElementById("btnCloseTicket")){
  //seccion para cerrar el ticket
  let btnCloseTicket = document.getElementById('btnCloseTicket');
  function loaderSwal(){
    Swal.fire({
      title: 'Actualizando Datos',
      text: 'Espere un momento',
      icon: 'warning',
    })
  }
  btnCloseTicket.addEventListener('click', function(){
    Swal.fire({
      title: 'Cerrar Ticket?',
      text: 'Una vez cerrado, no podras reanudar el ticket.',
      iconHtml: '?',
      showCancelButton: true,
      cancelButtonText: 'No, Cancelar',
      confirmButtonText: 'Si, Cerrar'
    }).then((result)=>{
      if(result.isConfirmed){
        //si lo va a cerrar
        // alert('se cierra');
        let ticket = document.getElementById('infoTicket').value;

        let datos = new FormData();
        datos.append('infoTicketClose',ticket);

        let envio = new XMLHttpRequest();
        envio.addEventListener('loadstart', loaderSwal);
        envio.open("POST","../includes/operations/tickets.php",false);
        envio.send(datos);

        if(envio.status == 200){
          let res = envio.responseText;
          if(res == "OperationSuccess"){
            Swal.fire(
              'Ticket Actualizado',
              'Se ha procesada correctamente tu informacion',
              'success'
            ).then(function(){
              location.reload();
            })
          }else{
            //error al hacer algo
            let err = res.split("DataError|")[1];
            Swal.fire(
              'Ha ocurrido un error',
              'Verificar: '+err,
              'error'
            )
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
        //no hacemos nada, 
      }
    })
  })
}


if(document.getElementById('btnTerminar')){
  let btnTerminar = document.getElementById('btnTerminar');
  function loaderSwal(){
    Swal.fire({
      title: 'Actualizando Datos',
      text: 'Espere un momento',
      icon: 'warning',
    })
  }

  btnTerminar.addEventListener('click', function(){
    //seccion para dar por solucionado el ticket
    Swal.fire({
      title: 'Dar por solucionado?',
      text: 'Esto cambiara el estatus del ticket a solucionado',
      iconHtml: '?',
      showCancelButton: true,
      cancelButtonText: 'No, Cancelar',
      confirmButtonText: 'Si, Cerrar'
    }).then((result)=>{
      if(result.isConfirmed){
        //si se modifica
        
        let ticketSol = document.getElementById('infoTicket').value;
        let datos = new FormData();
        datos.append('infoTicketSolucionado',ticketSol);
        

        let envio = new XMLHttpRequest();
        envio.addEventListener('loadstart', loaderSwal);
        envio.open("POST","../includes/operations/tickets.php");
        

        envio.addEventListener('load', function(){
          if(envio.status == 200){
            // console.log(envio.responseText);
            let res = envio.responseText;
            if(res == "OperationSuccess"){
              Swal.fire(
                'Ticket Actualizado',
                'Se ha notificado la solucion del ticket',
                'success'
              ).then(function(){
                location.reload();
              })
            }else{
              //ocurrio un error
              let err = res.split("DataError|")[1];
              Swal.fire(
                'Ha ocurrido un error',
                'Verificar: '+err,
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
        })//fin onload 
        envio.send(datos);

        
      }else{
        //no hacemos nada
      }
    })
  })
}