let sucInput = document.getElementById("sucCodes");

sucInput.addEventListener("change", function(){
  if(sucInput.value != ""){
    //verificamos las clasificaciones de esa sucursal
    let dato = new FormData();
    dato.append("sucShow",sucInput.value);

    let envia = new XMLHttpRequest();
    envia.open("POST","../includes/operations/inventario.php",false);

    envia.send(dato);

    if(envia.status == 200){
      let res = JSON.parse(envia.responseText);
      if(res.length > 0){
        let combo = "<option value='' selected disabled>Seleccione...</option>";
        for(let x = 0; x < res.length; x++){
          let valor = res[x]['clasificacion'];
          combo += "<option value='"+valor+"'>"+valor+"</option>";
        }
        document.getElementById("clasiBySuc").innerHTML = combo;
        let btnGeneraBySuc = "<div clas='row'><div class='col s12 center-align'><a href='#!'"+
        " class='btn btnGrenNormal' id='genQrBySuc' onclick='getQrCodes(this.id);'>Generar Por Sucursal</div></div></div>";
        document.getElementById("resTab").innerHTML = btnGeneraBySuc;
        let options = "";
        var elemSel = document.querySelectorAll('select');
        var instanceSel = M.FormSelect.init(elemSel, options);
      }else{
        let error = envia.responseText.split("DataError|");
        Swal.fire(
          'Error',
          'Ha ocurrido un error inesperado: '+error,
          'warning'
        )
      }
      
    }else{
      Swal.fire(
        'Servidor inalcansable',
        'Verifica tu conexion a internet',
        'warning'
      )
    }
  }
})


let clasiInput = document.getElementById("clasiBySuc");
clasiInput.addEventListener("change", function(){
  //buscamos las areas donde se encuentren productos disponibles
  if(clasiInput.value != ""){
    let suc = document.getElementById("sucCodes").value;
    let datos = new FormData();
    datos.append("clasiShow",clasiInput.value);
    datos.append("sucByClasi",suc);

    let envia = new XMLHttpRequest();
    envia.open("POST", "../includes/operations/inventario.php", false);
    envia.send(datos);

    if(envia.status == 200){
      let res  = JSON.parse(envia.responseText);
      if(res.length >= 1){
        let lugares = "<option value=''>Seleccione...</option>";
        for (let i = 0; i < res.length; i++) {
          let lugar = res[i]['lugar'];
          lugares += "<option value='"+lugar+"'>"+lugar+"</option>";
        }//fin del for
        document.getElementById("lugarByClasi").innerHTML = lugares;
        let btnGeneraBySuc = "<div clas='row'>"+
        "<div class='col s12 m4 center-align'>"+
        "<a href='#!' id='genQrBySections' class='btn btnGrenNormal' "+
        " id='genQrBySuc' onclick='getQrCodes(this.id);'>Generar Por Sucursal</a>"+
        "</div><div class='col s12 m4 center-align'>"+
        "<a href='#!' class='btn btnGrenNormal' id='genQrByCla' onclick='getQrCodes(this.id);'>"+
        "Generar Por Clasificacion</a></div></div></div>";
        document.getElementById("resTab").innerHTML = btnGeneraBySuc;
        let options = "";
        var elemSel = document.querySelectorAll('select');
        var instanceSel = M.FormSelect.init(elemSel, options);

      }else{
        //sin resultados
      }
    }else{
      Swal.fire(
        'Servidor inalcansable',
        'Verifica tu conexion a internet',
        'warning'
      )
    }
  }
})


let lugarByClasi = document.getElementById("lugarByClasi");
lugarByClasi.addEventListener("change", function(){
  //consultaremos los articulos que caigan en este supuesto
  //seran mostrados en lista junto con la opcion de generar el PDF
  let sucursal = document.getElementById("sucCodes").value;
  let clasi = document.getElementById("clasiBySuc").value;
  let area = document.getElementById("lugarByClasi").value;
  

  if(sucursal != "" && clasi != "" && area != ""){
    //generamos el formdata
    let datos = new FormData();
    datos.append("getObjByAreaSuc",sucursal);
    datos.append("getObjByAreaClas",clasi);
    datos.append("getObjByArea2",area);

    let envio = new XMLHttpRequest();
    envio.open("POST","../includes/operations/inventario.php",false);
    envio.send(datos);

    if(envio.status = 200){
      let res = envio.responseText.split("DataError|");
      if(res.length == 1){
        res = JSON.parse(res);
        let cuerpo = `<div class='row center-align'>
          <h5>Resultado de seleccion</5>
        </div>
        <div clas='row'>
          <div class='col s12 m4 center-align'>
            <a href='#!' id='genQrBySuc' class='btn btnGrenNormal'
            onclick='getQrCodes(this.id);'>Generar Por Sucursal</a>
          </div>
          <div class='col s12 m4 center-align'>
            <a href='#!' id='genQrByCla' class='btn btnGrenNormal'
            onclick='getQrCodes(this.id);'>Generar Por clasificacion</a>
          </div>
          <div class='col s12 m4 center-align'>
            <a href='#!' id='genQrBySections' class='btn btnGrenNormal' 
            onclick='getQrCodes(this.id);'>Generar Por Lugar</a>
          </div>
        </div>
        <table class='centered'><thead>
        <tr><th>Codigo</th><th>Nombre</th><th>Empleado</th></tr>
        </thead><tbody>`;
        for (let i = 0; i < res.length; i++) {
          let nombrEmpleado = res[i]['paterno']+" "+res[i]['materno']+" "+res[i]['nombre'];
          let codigo = res[i]['codigo'];
          let nombreObj = res[i]['nombre_objeto'];

          cuerpo += "<tr><td>"+codigo+"</td><td>"+nombreObj+"</td><td>"+nombrEmpleado+"</td></tr>";
          
        }//fin del for de datos
        cuerpo += "</tbody></table>";

        document.getElementById("resTab").innerHTML = cuerpo;
      }else{
        Swal.fire(
          'Error',
          'Ocurrio un error inesperado: ',
          'warning'
        )
      }
    }else{
      Swal.fire(
        'Servidor inalcansable',
        'Verifica tu conexion a internet',
        'warning'
      )
    }
  } 

})


function getQrCodes(a){
  let tipo = a;
  let suc = document.getElementById("sucCodes").value;
  let cla = document.getElementById("clasiBySuc").value;
  let lug = document.getElementById("lugarByClasi").value;

  let datos = new FormData();
  datos.append("generaBy3","por3Datos");
  datos.append("sucursal",suc);
  datos.append("clasifica",cla);
  datos.append("lugar",lug);
  datos.append("tipoGen",tipo);

  let enviar = new XMLHttpRequest();

  enviar.open("POST","generaQrMasiBy3.php",false);
  enviar.send(datos);

  if(enviar.status == 200){
    let aux = enviar.responseText;
    if(aux.split("|")[0] == "OperationSuccess"){
      //se genero correctamente el pdf
      let ruta = aux.split("|")[1];
      window.open(ruta,"Generacion masiva");
    }else{
      let error = aux.split("|")[1];
      Swal.fire(
        'Ocurrio un error inesperado',
        'Verificar: '+error,
        'error'
      )
    }
  }else{
    Swal.fire(
      'Servidor Inalcansable',
      'Verifdica tu conexion a internet',
      'error'
    )
  }
}