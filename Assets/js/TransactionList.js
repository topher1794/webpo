
$(function(){
    loadList();
})

function loadList(){

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
            }
        },
        columns: [
            {
                data: null,
                render: function (data, type) {
                    let atag = "viewCustomer?shipto=" + btoa(data.syncno);
                    return '<a href="'+atag+'" >' + data.syncno + '</a>';
                }
            },
            { data: 'accttype' },
            { data: 'materialcode' },
            { data: 'materialcode' },
            { data: 'syncno' },
            { data: 'syncno' },


        ],
    });
    
}