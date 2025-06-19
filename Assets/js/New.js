
import ClsAsync from './ClsAsync.js';

var clsSync = new ClsAsync('test');

$(function () {
})

$(document).on('click', '#btnSubmit', async function () {
    let frmData = $("#frmData").serializeArray();
    // frmData.push(
    //     { name: 'salesorg', value: company }
    // );
    let url = "syncviaform";
    let result = await clsSync.getAjaxAsync(url, 'POST', frmData);


});
