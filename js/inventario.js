var elemSel = document.querySelectorAll('select'); 
var instanceSelec = M.FormSelect.init(elemSel, options);
var valuePage = 1;
var backPage = 2;

//realizamos las operaciones para filtros
let comboClasi = document.getElementById("clasificacion");
let comboSuc = document.getElementById("sucursal");
let inputNom = document.getElementById("buscarObjeto");

comboClasi.addEventListener("change", function(){
  //realiazmos la consulta de todos los equipos
  let valorclasi = comboClasi.value;
  let valorSuc = document.getElementById("sucursal").value;
  let valorNomb = document.getElementById("buscarObjeto").value;

  filtraObjetos(valorclasi,valorSuc,valorNomb);
});
comboSuc.addEventListener("change", function(){
  //realiazmos la consulta de todos los equipos
  let valorSuc = comboSuc.value;
  let valorClasi = document.getElementById("clasificacion").value;
  let valorNomb = document.getElementById("buscarObjeto").value;
  filtraObjetos(valorClasi,valorSuc,valorNomb);
});
inputNom.addEventListener("keypress",function(){
  let valorSuc = document.getElementById("sucursal").value;
  let valorClasi = document.getElementById("clasificacion").value;
  let valorNomb = document.getElementById("buscarObjeto").value;
  filtraObjetos(valorClasi,valorSuc,valorNomb);
});

function filtraObjetos(clasi,suc,nombre){
  //funcion para realizar la busqueda de objetos
  let datosForm = new FormData();
  datosForm.append("buscaClasi",clasi);
  datosForm.append("buscaSuc",suc);
  datosForm.append("buscaNombre",nombre);
  datosForm.append("buscarObjeto","verdadero");

  let envio = new XMLHttpRequest();
  envio.open('POST','includes/operations/inventario.php',false);
  envio.send(datosForm);

  if(envio.status == 200){
    if(envio.responseText != "NoDataResult"){
      let datosBusqueda = JSON.parse(envio.responseText);
      let cuerpoTabla = "";

      for(let i = 0; i < datosBusqueda.length; i++){
        let poliza = datosBusqueda[i].id_factura;
        let nombre = datosBusqueda[i].nombre_objeto_aux;
        let clasificacion = datosBusqueda[i].clasificacion;
        let sucursal = datosBusqueda[i].sucursal_resguardo;
        cuerpoTabla = cuerpoTabla + '<tr>'+
        '<td>'+poliza+'</td>'+
        '<td>'+nombre+'</td>'+
        '<td>'+clasificacion+'</td>'+
        '<td>'+sucursal+'</td>'+
        '<td><a href="ver-objeto.php?objId='+poliza+'">'+
        '<i class="material-icons">screen_share</i></a></td>'+
        '</tr>';
      }
      //console.log(datosBusqueda);
      document.getElementById("resultBusquedas").innerHTML = cuerpoTabla;

    }else{
      //sin resultados
      swal.fire(
        'Sin Resultados',
        'No se encontraron coincidencias en la busqueda deseada',
        'warning'
      )
    }
  }else{
    //erro de comunicacion
  }
}//fin funcion filtraObjetos


function getPage(pageId){
  if(pageId == "backPage"){
    //restamos 1 al actual
    if(valuePage > 1){
      let actPage = "page|"+valuePage;
      let backPage = valuePage -1;
      let textPage = "page|"+backPage;
      document.getElementById(actPage).classList.remove("active");
      document.getElementById(textPage).classList.add("active");
      valuePage = valuePage-1;
    }
  }else if(pageId == "nextPage"){
    let maxPage = document.getElementById("maxPages").value;//numero a mostrar de paginas
    if(valuePage < maxPage){
      let actPage = "page|"+valuePage;
      let backPage = valuePage +1;
      let textPage = "page|"+backPage;
      document.getElementById(actPage).classList.remove("active");
      document.getElementById(textPage).classList.add("active");
      valuePage = valuePage+1;
    }else{
      let nexGroupPage = document.getElementById("realNumPages").value;//numero total de paginas
      let nexNum = valuePage+1;
      valuePage = valuePage+1;
      let auxN = Number(valuePage)+Number(maxPage)-1;
      let maxNumNexPage = 0;
      if(valuePage >= nexGroupPage){
        maxNumNexPage = valuePage+1;
      }else{
        maxNumNexPage = valuePage + Number(maxPage);
      }
      let cuerpoLista = `<li class="waves-effect" id="backPage" onclick="getPage(this.id)">
        <a href="#!"><i class="material-icons">chevron_left</i></a>
      </li>`;
      while(nexNum < maxNumNexPage){
        cuerpoLista += `<li class="" id='page|${nexNum}' onclick="getPage(this.id)">
          <a href="#!">${nexNum}</a>
        </li>`;
        nexNum++;
      }
      cuerpoLista += `<li class="waves-effect" id="nextPage" onclick="getPage(this.id)">
      <a href="#!"><i class="material-icons">chevron_right</i></a>
      </li>`;
      document.getElementById("contentPaginator").innerHTML = cuerpoLista;
      let pageNu = "page|"+valuePage;
      document.getElementById(pageNu).classList.add("active");
      document.getElementById("maxPages").value = auxN;
    }
  }else{
    let auxPage = pageId.split("|")[1];//pagina elegida
    let previusPage = "page|"+valuePage;
    document.getElementById(previusPage).classList.remove("active");
    document.getElementById(pageId).classList.add("active");
    valuePage = Number(auxPage);
  }

  let maxRows = document.getElementById("maxRows").value;
  //consultamos la informacion del indice
  let datos = new FormData();
  datos.append("showPage",valuePage);
  datos.append("maxRes",maxRows);

  let envio = new XMLHttpRequest();
  envio.open('POST','includes/operations/inventario.php',false);
  envio.send(datos);

  if(envio.status == 200){
    let res = JSON.parse(envio.responseText);
    let cuerpoTabla = "";
    console.log(res);
    for (let i = 0; i < res.length; i++) {
      let polizaInterna = res[i]['factura_objeto'];
      let nombreObjeto = res[i]['nombre_objeto_general'];
      let clasi = res[i]['clasificacion'];
      let sucur = res[i]['sucursal_resguardo'];
      

      cuerpoTabla +=`<tr>
        <td>${res[i]['factura_objeto']}</td>
        <td>${nombreObjeto}</td>
        <td>${clasi}</td>
        <td>${sucur}</td>
        <td>
          <a href='ver-objeto.php?objId=${polizaInterna}'>
            <i class='material-icons'>screen_share</i>
          </a>
        </td>
      </tr>`;
    }//fin del for
    document.getElementById("resultBusquedas").innerHTML = cuerpoTabla;
    
  }else{
    Swal.fire(
      'Error de comunicacion',
      'Verifica tu conexion a internet',
      'error'
    )
  }
 
}

