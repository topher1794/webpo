import ClsAsync from './ClsAsync.js';

var clsSync = new ClsAsync('test');

var myTable = "";
$(function () {
    loadList();
})

function loadList() {
    const queryString = window.location.search
    const urlParams = new URLSearchParams(queryString);
    let $page = '';
    let columns = [
        {
            data: null,
            render: function (data, type) {
                // let atag = "viewCustomer?shipto=" + btoa(data.transactno);
                return '<a href="#" class="transactNo" data-value="' + data.transactno + '|' + data.materialcode + '">' + data.transactno + '</a>';
            }
        },
        { data: 'inputdate' },
        { data: 'materialcode' },
        { data: 'company' },
        { data: 'source' },
        { data: 'user' },
    ];
    let columnDefsVal = [];
    let columnDefs = "";

    if (urlParams.get('status') == 'Closed') {
        $page = 'CLOSED';
        columns.push({ data: 'completedate' });
    } else {
        $page = 'OPEN';
    }

    for (let x = 0; x < columns.length; x++) {
        if (x !== 0) {
            columnDefsVal.push(x);
        }
    }

    columnDefs = [{ targets: columnDefsVal, className: 'text-center' }];

    myTable = $('#tblData').DataTable({
        searching: true,
        pageLength: 15,
        'paging': true,
        "aaSorting": [],
        // "responsive": true,

        ajax: {
            url: "stocktransaction",
            type: 'POST',
            data: {
                "status": "Active",
                "page": $page,
                "from": $('#from').val(),
                "to": $('#to').val()
            }
        },
        columns: columns,
        columnDefs: columnDefs,
    });

}


$(document).on('click', 'a.transactNo', async function () {

    let myData = $(this).data('value');
    myData = myData.split("|");

    let data = { transactNo: myData[0], sku: myData[1] };
    let url = "getDetails";
    let method = "POST";
    let json = await ajaxSend(url, method, data);
    json = JSON.parse(json);
    let myJson_val = json['data'];

    let tableData = "";

    $('#exampleModalLabel').text(myData[0])

    for (let x = 0; x < myJson_val.length; x++) {
        tableData += "<tr>";
        tableData += "<td>" + myJson_val[x]['accttype'] + "</td>";
        tableData += "<td>" + myJson_val[x]['productid'] + "</td>";
        tableData += "<td>" + myJson_val[x]['materialcode'] + "</td>";
        tableData += "<td>" + (myJson_val[x]['modelid'] == null ? '' : myJson_val[x]['modelid']) + "</td>";
        tableData += "<td title=\"" + myJson_val[x]['productname'] + "\">" + (myJson_val[x]['productname'].length <= 40 ? myJson_val[x]['productname'] : myJson_val[x]['productname'].substring(0, 40) + "...") + "</td>";
        tableData += "<td class=\"text-center\">" + myJson_val[x]['qty'] + "</td>";
        tableData += "<td class=\"text-center\">" + (myJson_val[x]['orig_qty'] == null ? '' : myJson_val[x]['orig_qty']) + "</td>";
        tableData += "</tr>";
    }

    $('#Mtbody').html(tableData);

    $('#exampleModal').modal('show')
})

$(document).on('click', '#btnSearch', function (e) {
    e.preventDefault();
    let from = $('#from').val();
    let to = $('#to').val();

    console.log('ASASAJSASJHSA' + to)

    if (from == '' && to == '') {
        Swal.fire({
            title: "Invalid Date Range",
            text: "Please fill up date range!",
            icon: "warning",
            allowOutsideClick: false
        });
        return;
    } else {
        myTable.destroy();
        loadList();
    }
})


$(document).on('click', '#btnDelete', function () {
    $('#from').val('');
    $('#to').val('');
})

$(document).on('click', '#newSync', function () {
    $('#newSyncModal').modal('show');
})


$(document).on('click', '#btnSubmit', async function () {
    let frmData = $("#frmData").serializeArray();
    // frmData.push(
    //     { name: 'salesorg', value: company }
    // );

    let url = "syncviaform";
    let result = await clsSync.getAjaxAsync(url, 'POST', frmData);

    let myResult = JSON.parse(result);

    if (myResult.result == 'error') {
        $("#newSyncModal").modal('hide');
    }

    Swal.fire({
        title: myResult.message,
        icon: myResult.result,
        draggable: true
    }).then((res) => {

        if (res.isConfirmed) {
            if (myResult.result == 'error') {
                $("#newSyncModal").modal('show');
                return;
            }
             $("#newSyncModal").modal('hide');
             myTable.destroy();
            loadList();

        }

    });


});


async function ajaxSend(url, method, data) {

    const result = await

        $.ajax({
            type: method,
            url: url,
            data: data,
        });

    return result;
}
