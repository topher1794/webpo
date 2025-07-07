
import ClsAsync from './ClsAsync.js';

var clsSync = new ClsAsync('test');
var myTable = "";

$("form#frmData").submit(function (e) {

    e.preventDefault();
    var sweet_loader = '<div class="sweet_loader"><svg viewBox="0 0 140 140" width="140" height="140"><g class="outline"><path d="m 70 28 a 1 1 0 0 0 0 84 a 1 1 0 0 0 0 -84" stroke="rgba(0,0,0,0.1)" stroke-width="4" fill="none" stroke-linecap="round" stroke-linejoin="round"></path></g><g class="circle"><path d="m 70 28 a 1 1 0 0 0 0 84 a 1 1 0 0 0 0 -84" stroke="#71BBFF" stroke-width="4" fill="none" stroke-linecap="round" stroke-linejoin="round" stroke-dashoffset="200" stroke-dasharray="300"></path></g></svg></div>';
    swal.fire({
        html: '<h5>Loading...</h5>',
        showConfirmButton: false,
        allowOutsideClick: false,
        allowEscapeKey: false,
        onRender: function () {
            // there will only ever be one sweet alert open.
            $('.swal2-content').prepend(sweet_loader);
        }
    });

    var formData = new FormData(this);
    $.ajax({
        url: "updateSettings",
        type: 'POST',
        data: formData,
        success: function (data) {
            var parseJson = JSON.parse(data);
            var mes = parseJson.result;

            if (mes == "error") {
                var error_message = parseJson.error_message;

                swal.fire({
                    icon: 'error',
                    html: '<h4>Error!</h4>'
                });

            }
            if (mes == "success") {

                swal.fire({
                    icon: 'success',
                    html: '<h4>Success!</h4>'
                });

                $('#modal-xl').modal('hide');

                myTable.ajax.reload();
            }

        },
        cache: false,
        contentType: false,
        processData: false
    });

});


$(function () {
    loadList();
})

function loadList() {

    myTable = $('#tblData').DataTable({
        searching: true,
        pageLength: 15,
        'paging': true,
        "aaSorting": [],
        "ordering": false,
        ajax: {
            url: "getSettings",
            type: 'POST',
            data: {
                "status": "Active",
            }
        },
        columns: [

            { data: 'settingstype' },
            {
                data: null,
                render: function (data, type) {
                    return '<input type="hidden" name="settingstype[]" value="'+data.settingstype+'" /><textarea name="attributes[]" class="form-control">' + (data.attributes) + '</textarea>';
                }
            },

        ],
        "aoColumnDefs": [
            // { "bSortable": false, "aTargets": [0, 1] },
            // { "bSearchable": false, "aTargets": [0, 1] }
        ],

    });

}


$(document).on('change', '#company', async function () {
    myTable.destroy();
    loadList($(this).val(), $('#source').val());

});
