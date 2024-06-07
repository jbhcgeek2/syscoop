

function genNewAudi(tipo){
  if(tipo != ""){
    Swal.fire({
      title: '¿Estas seguro de realizar el proceso?',
      text: 'Este proceso no podra ser eliminado una vez confirmado.',
      icon: 'warning',
      showCancelButton: true,
      confirmButtonText: 'Confirmar',
      cancelButtonText: 'Cancelar'
    }).then((result) => {
      console.log(result);
      if(result.value){
        let dato = new FormData();
        dato.append("tipoCreacion",tipo);
    
        let envio = new XMLHttpRequest();
        envio.open("POST","../includes/operations/auditaInventario.php",false);
        envio.send(dato);
        
        if(envio.status == 200){
          let res = envio.responseText;
          if(res.split("DataError|").length == 1){
            if(res.split("operacionComplete|").length > 1){
              let dat = res.split("operacionComplete|")[1];
              window.location = "ver-auditoria.php?info="+dat;
            }else{
              Swal.fire(
                'Ha ocurrido un error critico',
                'Reporte a sistemas',
                'error'
              )
            }
          }else{
            let error = res.split("DataError|")[1];
            Swal.fire(
              'Ha ocurrido un error',
              'Verificar: '+error,
              'error'
            )
          }
        }else{
          Swal.fire(
            'Servidor inalcansable',
            'Verifica tu conexion a internet.',
            'error'
          )
        }
      }else{
        Swal.fire(
          'Ok, no continuamos',
          '',
          'info'
          )
      }
    })

    
  }
}