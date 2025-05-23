
import ClsAsync from './ClsAsync.js';

var clsSync = new ClsAsync('test');

$(document).on('click', '#btnSubmit', async function () {
    $("#trBody").html("");

    let frmData = $("#frmData").serializeArray();
    frmData.push(
        { name: 'company', value: Company }
    );
    let url = "checkstockqty";
    let result = await clsSync.getAjaxAsync(url, 'POST', frmData);
    console.log(result) ;
    $("#trBody").html(result);
});
