
function filtroTicket(){
  //cuando se accione este metodo, realizaremos el filtro 
  let estatus = document.getElementById('estatusTicket').value;
  let tipoTicket = document.getElementById('tipoTickets').value;
  let usuarioSolicita = document.getElementById('solicitaTicket').value;

  let datos = new FormData();
  datos.append("filtroTickets",'afirma');
  datos.append("filtroStatus",estatus);
  datos.append("filtroTipo",tipoTicket);
  datos.append("filtroUsuario",usuarioSolicita);

  let envio = new XMLHttpRequest();
  envio.open("POST","../includes/operations/tickets.php",false);
  envio.send(datos);

  if(envio.status == 200){
    let res = envio.responseText;
    if(res.split("DataError|").length == 1){
      let datos = JSON.parse(res);
      // console.log(datos);
      let cuerpo = "";
      if(datos.length > 0){
        for(let x = 0; x < datos.length; x++){
          // console.log(datos[x].nombreTicket);
          cuerpo = cuerpo+`<tr>
            <td>${datos[x].datos.nombreTicket}</td>
            <td>${datos[x].registra}</td>
            <td>${datos[x].responsable}</td>
            <td>${datos[x].datos.prioridad_ticket}</td>
            <td class='text-center'>
              <span class='new badge ${datos[x].datos.colorStatus}' data-badge-caption='${datos[x].datos.estatus_ticket}'>
              </span>
            </td>
            <td>${datos[x].fechaTermino}</td>
            <td>
              <a href='verInfoTicket.php?info=${datos[x].datos.id_ticket}'>
                <i class='material-icons'>developer_board</i>
              </a>
            </td>
          </tr>`;
        }//fin del for
        document.getElementById('resultFiltro').innerHTML = cuerpo;
      }else{
        //sin resultados
      }
    }else{
      //error
      Swal.fire(
        'Ha ocurrido un error',
        'Reportar a sistemas',
        'error'
      )
    }
  }else{
    //error del servidor
    Swal.fire(
      'Servidor inalcansable',
      'Verifica tu conexion a internet',
      'error'
    )
  }
  

}