let buscarActa = document.getElementById('buscaActa');
buscarActa.addEventListener('keyup', function(){
  let acta = buscarActa.value;
  if(acta.length > 1){
    let datos = new FormData();
    datos.append('buscarActa',acta);
    let enviar = new XMLHttpRequest();

    enviar.open("POST","../includes/operations/consejos.php",false);
    enviar.send(datos);

    if(enviar.status == 200){
      console.log(enviar.responseText);
      let res = enviar.responseText;
      if(res.split("DataError|").length == 1){
        if(res == "NoDataResult"){
          Swal.fire(
            'Sin resultados',
            'No se encontraron resultados para tu busqueda',
            'warning'
          )
        }else{
          let resultado = JSON.parse(res);
          let contenidoTabla = "";
          for (let x = 0; x < resultado.length; x++) {
            let actaNum = resultado[x]['acta_num']+" "+resultado[x]['numeral'];
            let consejo = resultado[x]['nombre_consejo'];
            let fechaReg = resultado[x]['fecha_acta'];
            let acta = resultado[x]['id_acta'];

            contenidoTabla += `<tr>
              <td>${consejo}</td>
              <td>${actaNum}</td>
              <td>${fechaReg}</td>
              <td>
                <a href='verInfoActa.php?actaNum=${acta}' class='btn waves-effect red'>
                  <i class='material-icons'>edit</i>
                </a>
              </td>
            </tr>`;
          }//fin del for
          document.getElementById('resultBusqueda').innerHTML = contenidoTabla;
        }
      }else{
        //error de consulta
        let err = res.split("DataError|")[1];
        Swal.fire(
          'Error',
          'Verificar: '+err,
          'error'
        )
      }

    }else{
      Swal.fire(
        'Servidor inalcansable',
        'Verifica tu conexion a internet',
        'error'
      )
    }
  }

})