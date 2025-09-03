import ClsAsync from './ClsAsync.js';

var clsSync = new ClsAsync('test');


$(document).on('click', '#accttype', async function () {
    $("#acctname").html("");

    let id =  $(this).children('option:selected').data('id') ;
    let opt = await loadOptAccount(id);
    if(opt != null){
        let parseJson = JSON.parse(opt);
        let optValue = "";
        for(let i=0; i < parseJson.length; i++){
            optValue += "<option data-id=\"" + parseJson[i].files  + "\">" + parseJson[i].acctname  + "</option>";
        }
        $("#acctname").html("<option></option>"+ optValue);
    }
});


$(document).on('click', '#acctname', async function () {
    $("#acctfile").html("");
    let files =  $(this).children('option:selected').data('id') ;
    if(files != null) {
        let splitFiles = files.split(",");
        let optValue = "";
        for(let i= 0; i < splitFiles.length; i ++){
            if(splitFiles[i]  != null){
                optValue += "<option>" + splitFiles[i] + "</option>";
            }
        }
        $("#acctfile").html("<option></option>"+ optValue);
    }


});

async function loadOptAccount(accttype){
    let url = "getAcctName";
    let result = await clsSync.getAjaxAsync(url, 'POST', {accttype: accttype});
    return result;
}
