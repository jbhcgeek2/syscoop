let primerFiltro = document.getElementById("primFiltro");
var elemsTol = document.querySelectorAll('.tooltipped');
var options = "";
var instances = M.Tooltip.init(elemsTol, options);

let fecha1 = document.getElementById("fechaDesde");
let fecha2 = document.getElementById("fechaHasta");

primerFiltro.addEventListener('change',function(){
  enviarDatos();
})

let btnUpdate = document.getElementById("updateDep");
btnUpdate.addEventListener('click', function(){
  let fechaDesde = document.getElementById("fechaDesde").value;
  let fechaHasta = document.getElementById("fechaHasta").value;

  if(fechaDesde && fechaHasta){
    enviarDatos();
  }else{
    swal.fire(
      'Datos Invalidos',
      'Verificaque las fechas contengan datos correctos',
      'warning'
    )
  }
})


function enviarDatos(){
  let valor = primerFiltro.value;
  let fechaDesde = document.getElementById("fechaDesde").value;
  let fechaHasta = document.getElementById("fechaHasta").value;
  
  let datos = new FormData();
  datos.append("tipoFiltro","primero");
  datos.append("valorFiltro",valor);
  datos.append("fechaDesFiltro",fechaDesde);
  datos.append("fechaHasFiltro",fechaHasta);

  let enviar = new XMLHttpRequest();
  enviar.open("POST","../includes/operations/depreciados.php",false)
  enviar.send(datos);

  if(enviar.status == 200){
    alert(enviar.responseText.split("DataError|").length);
    if(enviar.responseText.split("DataError|").length == 1){
      document.getElementById("resultReport").innerHTML = enviar.responseText;
    }else{
      swal.fire(
        'Error',
        ' ',
        'warning'
      )
    }
    
    var elemsTol = document.querySelectorAll('.tooltipped');
    var options = "";
    var instances = M.Tooltip.init(elemsTol, options);
  }else{
    //error de comunicacion
  }
}