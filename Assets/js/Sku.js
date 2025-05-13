
import ClsAsync from './ClsAsync.js';

var clsSync = new ClsAsync('test');

$("form#frmUpload").submit(function(e) {

    e.preventDefault(); 
    var sweet_loader = '<div class="sweet_loader"><svg viewBox="0 0 140 140" width="140" height="140"><g class="outline"><path d="m 70 28 a 1 1 0 0 0 0 84 a 1 1 0 0 0 0 -84" stroke="rgba(0,0,0,0.1)" stroke-width="4" fill="none" stroke-linecap="round" stroke-linejoin="round"></path></g><g class="circle"><path d="m 70 28 a 1 1 0 0 0 0 84 a 1 1 0 0 0 0 -84" stroke="#71BBFF" stroke-width="4" fill="none" stroke-linecap="round" stroke-linejoin="round" stroke-dashoffset="200" stroke-dasharray="300"></path></g></svg></div>';
    swal.fire({
        html: '<h5>Loading...</h5>',
        showConfirmButton: false,
        allowOutsideClick: false,
        allowEscapeKey: false,
        onRender: function() {
             // there will only ever be one sweet alert open.
             $('.swal2-content').prepend(sweet_loader);
        }
    });

    var formData = new FormData(this);
    $.ajax({
        url: "uploadmaster",
        type: 'POST',
        data: formData,
        success: function (data) {
            var parseJson = JSON.parse(data);
            var mes = parseJson.result;

            if(mes == "error") {
                var error_message = parseJson.error_message;
                // toastr.error(mes + " : " + error_message);

                swal.fire({
                    icon: 'error',
                    html: '<h4>Error!</h4>'
                });

            }
            if(mes == "success") {
                // toastr.info(mes + " Successfully Synced " );

                swal.fire({
                    icon: 'success',
                    html: '<h4>Success!</h4>'
                });

                $('#modal-xl').modal('hide');
            }
            
        },
        cache: false,
        contentType: false,
        processData: false
    });

});


$(function(){
    loadList();
})

function loadList(){

    $('#tblData').DataTable({
        searching: true,
        pageLength: 15,
        'paging': true,
        "aaSorting": [],
        ajax: {
            url: "getSkus",
            type: 'POST',
            data: {
                "status": "Active",
            }
        },
        columns: [
            {
                data: null,
                render: function (data, type) {
                    let atag = "viewCustomer?shipto=" + btoa(data.syncno);
                    return '<a href="'+atag+'" >' + data.company + '</a>';
                }
            },
            { data: 'accttype' },
            { data: 'parentsku' },
            { data: 'sku' },
            { data: 'productid' },

        ],
    });
    
}




    // $(function(){
    // })
    
    // $(document).on('click', '#btnSubmit', async function () {
    //     let frmData = $("#frmData").serializeArray();
    //     // frmData.push(
    //     //     { name: 'salesorg', value: company }
    //     // );
    //     let url = "syncviaform";
    //     let result = await clsSync.getAjaxAsync(url, 'POST', frmData);
    //     console.log(result);


    // });
