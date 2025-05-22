
import ClsAsync from './ClsAsync.js';

var clsSync = new ClsAsync('test');
var myTable = "";


$(function () {
    loadList();
})

function loadList() {

    myTable = $('#tblData').DataTable({
        searching: true,
        pageLength: 15,
        'paging': true,
        "aaSorting": [],
        ajax: {
            url: "getUsers",
            type: 'POST',
            data: {
                "status": "Active",
            }
        },
        columns: [
            {
                data: null,
                render: function (data, type) {
                    // let atag = "viewCustomer?shipto=" + btoa(data.firstname);
                    return (data.name == null ? '' : data.name);
                }
            },
            { data: 'emailadd' },
            { data: 'token' },
            { data: 'token_expiration' },
            { data: 'status' },
        ],
    });

}



