let selecTipo = document.getElementById('tipoDocFiltro');

selecTipo.addEventListener('change', function(){
  let tipoDoc = selecTipo.value;

  let datos = new FormData();
  datos.append('busTipoDoc',tipoDoc);

  let enviar = new XMLHttpRequest();

  enviar.open('POST',"../includes/operations/documentos.php",false);
  enviar.send(datos);

  if(enviar.status == 200){
    let res = enviar.responseText;
    if(res == "NoDataResult"){
      Swal.fire(
        'Sin datos',
        'No se encontraron documentos del tipo seleccionado.',
        'warning'
      )
    }else{
      if(res.split("DataError|").length == 1){
        let resDoc = JSON.parse(res);
        let contenidoTabla = "";
        for (let x = 0; x < resDoc.length; x++) {
          let fecha = "";
          if(resDoc[x]['fecha_ultima_mod'] == "0000-00-00"){
            fecha = resDoc[x]['fecha_registro'];
          }else{
            fecha = resDoc[x]['fecha_ultima_mod'];
          }
          contenidoTabla += `<tr>
            <td>${resDoc[x]['nombre_man_form']}</td>
            <td>${resDoc[x]['tipo_doc']}</td>
            <td>${fecha}</td>
            <td>${resDoc[x]['nombre_puesto']}</td>
            <td>
              <a href='verInfoDoc.php?docInfo=${resDoc[x]['id_man_form']}'>
                <i class='medium material-icons red-text'>find_in_page</i>
              </a>
            </td>
          </tr>`;
        }//fin del for

        document.getElementById("resTablaDocs").innerHTML = contenidoTabla;
      }else{
        //error
        let err = res.split("DataError|")[1];
        Swal.fire(
          'Error',
          'Verificar: '+err,
          'error'
        )
      }
    }
  }else{
    Swal.fire(
      'Servidor inalcansable',
      'Verifica tu conexion a internet',
      'error'
    )
  }
})

let campoBusqueda = document.getElementById("buscaManual");
campoBusqueda.addEventListener('keyup', function(){
  //verificamos si se indica un tipo de documento
  let docName = document.getElementById("buscaManual").value;

  if(docName.trim().length > 2){
    let tipoDoc = document.getElementById("tipoDocFiltro").value;
    let datos = new FormData();
    datos.append("tipoDocBus",tipoDoc);
    datos.append("nombreBusca",docName);

    let enviar = new XMLHttpRequest();
    enviar.open("POST","../includes/operations/documentos.php",false);
    enviar.send(datos);

    if(enviar.status == 200){
      let res = enviar.responseText;
      if(res == "NoDataResult"){
        //sin resultados
        Swal.fire(
          'Sin datos',
          'No se encontraron documentos del tipo seleccionado.',
          'warning'
        )
      }else{
        if(res.split("DataError").length == 1){
          let resDoc = JSON.parse(res);
          let contenidoTabla = "";
          for (let x = 0; x < resDoc.length; x++) {
            let fecha = "";
            if(resDoc[x]['fecha_ultima_mod'] == "0000-00-00"){
              fecha = resDoc[x]['fecha_registro'];
            }else{
              fecha = resDoc[x]['fecha_ultima_mod'];
            }
            contenidoTabla += `<tr>
              <td>${resDoc[x]['nombre_man_form']}</td>
              <td>${resDoc[x]['tipo_doc']}</td>
              <td>${fecha}</td>
              <td>${resDoc[x]['nombre_puesto']}</td>
              <td>
                <a href='verInfoDoc.php?docInfo=${resDoc[x]['id_man_form']}'>
                  <i class='medium material-icons red-text'>find_in_page</i>
                </a>
              </td>
            </tr>`;
          }//fin del for

          document.getElementById("resTablaDocs").innerHTML = contenidoTabla;
        }else{
          let err = res.split("DataError|")[1];
          Swal.fire(
            'Error',
            'Verificar: '+err,
            'error'
          )
        }
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