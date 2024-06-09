function editSuc(sucData){
    console.log(sucData);
    let auxData = sucData.split("|");
    let idSuc = auxData[1];
    let nombreSuc = auxData[2];
    let estatusSuc = auxData[3];
    let statusSuc = '';

    //ahora mandamos llamar al modal de editar

    if(estatusSuc == "Activa"){
        statusSuc = 1;
    }else{
        statusSuc = 0;
    }

    document.getElementById('nameSucEdit').value = nombreSuc;
    document.getElementById('estatusSucEdit').value = statusSuc;
    document.getElementById('dataSucEdit').value = idSuc;

    var elemSel = document.querySelectorAll('select');
    var instanceSel = M.FormSelect.init(elemSel, options);

    M.updateTextFields();
}