let buscar = document.getElementById("buscarProov");

buscar.addEventListener("keyup",function(){
  let prov = buscar.value;
  if(prov.split("").length >= 1){
    
    let datos = new FormData();
    datos.append("buscarProv",prov);

    let enviar = new XMLHttpRequest();
    enviar.open("POST","../includes/operations/proveedores.php",false);
    enviar.send(datos);

    if(enviar.status == 200){
      let res = enviar.responseText;
      let tableContent = ""; 
      if(res.split("DataError|").length == 1){
        if(res != "noResults"){
          let infoProv = JSON.parse(res);
          for (let i = 0; i < infoProv.length; i++) {
            let nombreProv = infoProv[i]['nombre_proveedor'];
            let telProv = infoProv[i]['telefono_proveedor'];
            let rfcProv = infoProv[i]['rfc_proveedor'];
            let idProv = infoProv[i]['id_proveedor'];
            //let nombreProv = infoProv[i]['nombre_proveedor'];
            tableContent += "<tr><td>"+idProv+"</td><td>"+nombreProv+"</td><td>"+telProv+"</td><td>"+rfcProv+"</td>"+
            "<td><a href='ver-proveedor.php?provId="+idProv+"'><i class='material-icons'>screen_share</i></a></td></tr>";
            
          }//fin del for
        }else{
          tableContent = "<tr><td>Sin resultados</td></tr>";
        }
        

        document.getElementById("resProvs").innerHTML = tableContent;
      }else{
        //error de consulta
        let error = res.split("DataError|")[1];
        Swal.fire(
          'Ha ocurrido un error',
          'Verirficar: '+error,
          'error'
        )
      }
    }else{
      Swal.fire(
        'Servidor inalcansable',
        'Ocurrio un error de comunicacion, intente mas tarde',
        'error'
      )
    }
  }
});