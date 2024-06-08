

function buscaUsuario(valor){
  if(valor.length > 0){
    //generamos el formData
    let datos = new FormData();
    datos.append("usuBuscado",valor);

    let envio = new XMLHttpRequest();
    envio.open('POST','../includes/operations/empUser.php',false);
    envio.send(datos);

    if(envio.status == 200){
      let response = envio.responseText;
      if(response.split("DataError|").length == 1){
        if(response == "NoResult"){
          // Swal.fire(
          //   'Sin Resultados',
          //   'Verifica que el dato este correctamente escrito',
          //   'warning'
          // )
          //generamos una tabla en vez del swal
          let tablaNueva = `
          <tr>
            <td colspan="4">No hay resultados</td>
          </tr>
          `;
          document.getElementById("resultUser").innerHTML = tablaNueva;
        }else{
          let datos = JSON.parse(response);
          console.log(datos.length);
          //creamos la nueva tabla
          let tablaNueva = ``;
          for (let i = 0; i < datos.length; i++) {
            let nombreUs = datos[i].nombre_usuario;
            let nombreEpleado = datos[i].nombre+" "+datos[i].paterno+" "+datos[i].materno;
            let departamento = datos[i].nombre_departamento;
            let userDat = datos[i].id_usuario;
            tablaNueva += `
            <tr>
              <td>${nombreUs}</td>
              <td>${nombreEpleado}</td>
              <td>${departamento}</td>
              <td>
                <a href='verInfoUsuario.php?dataSet=${userDat}'>
                  <i class='material-icons red-text'>manage_accounts</i>
                </a>
              </td>
            </tr>
            `;
            
          }//fin del for

          document.getElementById("resultUser").innerHTML = tablaNueva;
        }
      }else{
        //ocurrio algun error
        console.log("no");
      }
    }else{
      //problemas de comunicacion
    }
  }
}