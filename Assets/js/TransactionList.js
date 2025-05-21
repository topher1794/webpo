
$(function () {
    loadList();
})

function loadList() {
    const queryString = window.location.search
    const urlParams = new URLSearchParams(queryString);
    let $page = '';

    if (urlParams.get('status') == 'Closed') {
        $page = 'CLOSED';
    } else {
        $page = 'OPEN';
    }

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
        columns: [
            {
                data: null,
                render: function (data, type) {
                    let atag = "viewCustomer?shipto=" + btoa(data.transactno);
                    return '<a href="' + atag + '" >' + data.transactno + '</a>';
                }
            },
            { data: 'acctName' },
            { data: 'accttype' },
            { data: 'synctime' },
            { data: 'syncno' },
            { data: 'response' },


        ],
    });

}