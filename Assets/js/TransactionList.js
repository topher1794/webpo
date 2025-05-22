
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

    $('#tblData').DataTable({
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
                "page": $page
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

    for (let x = 0; x < myJson_val.length; x++) {
        tableData += "<tr>";
        tableData += "<td>" + myJson_val[x]['accttype'] + "</td>";
        tableData += "<td>" + myJson_val[x]['productid'] + "</td>";
        tableData += "<td>" + myJson_val[x]['materialcode'] + "</td>";
        tableData += "<td>" + (myJson_val[x]['modelid'] == null ? '' : myJson_val[x]['modelid']) + "</td>";
        tableData += "<td>" + myJson_val[x]['productname'] + "</td>";
        tableData += "<td>" + myJson_val[x]['qty'] + "</td>";
        tableData += "<td>" + (myJson_val[x]['orig_qty'] == null ? '' : myJson_val[x]['orig_qty']) + "</td>";
        tableData += "</tr>";
    }

    $('#Mtbody').html(tableData);

    $('#exampleModal').modal('show')
})

async function ajaxSend(url, method, data) {

    const result = await

        $.ajax({
            type: method,
            url: url,
            data: data,
        });

    return result;
}
