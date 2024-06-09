function editSuc(sucData){
    console.log(sucData);
    let auxData = sucData.split("|");
    let idSuc = auxData[0];
    let nombreSuc = auxData[1];
    let estatusSuc = auxData[2];

    //ahora mandamos llamar al modal de editar

    document.getElementById('nameSucEdit').value = nombreSuc;
    document.getElementById('estatusSucEdit').value = estatusSuc;
    document.getElementById('dataSucEdit').value = idSuc;
}