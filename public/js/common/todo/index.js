const init_dataTable = () => {
    var dataTable = $("#dataTable").DataTable({
        processing: true,
        serverSide: true,
        stateSave: true,
        fnCreatedRow: function (nRow, aData, iDataIndex) {
            $(nRow).attr("class", "data_row");
        },
        ajax: {
            url: $("#fetch_data").data("url"),
            error: function (xhr, error, thrown) {
                console.log(xhr);
            },
            beforeSend: function () {
                // $(document).find("#pageloader").show();
            },
            complete: function () {
                // $(document).find("#pageloader").hide();
            },
        },
        columns: [
            {
                data: "id",
                name: "id",
                render: function (data, type, row) {
                    return "TODO - " + (10000 + row.id);
                },
            },
            {
                data: "todo",
                name: "todo",
                render: function (data, type, row) {
                    return `<span class="line-clamp-2">${row.todo}</span>`;
                }
            },
            {
                data: "status",
                name: "status",
                render: function (data, type, row) {
                    return row.status == 1 ? "Completed" : "Pending";
                },
            },
            {
                data: "created_by",
                name: "created_by",
                render: function (data, type, row) {
                    return row.created_by;
                },
                class: `${!login_type ? "hide" : ""}`,
            },
            {
                data: "created_At",
                name: "created_At",
                render: function (data, type, row) {
                    return row.created_at;
                },
            },
            {
                data: "action",
                name: "action",
                orderable: false,
                searchable: false,
            },
        ],
        dom: 'r<"table-responsive"t><"bottom"<"dataInfo"li><"dataPaginate"p>><"clear">', // <"top">
        aoColumnDefs: [
            {
                bSortable: false,
                aTargets: ["nosort"],
            },
        ],
        fnDrawCallback: function (oSettings) {
            adjustPagination(oSettings);
        },
        language: {
            sSearch: "",
            sPlaceholder: "search in table",
            sLengthMenu: "show _MENU_ records",
            sInfo: '<span class="font-13 total_records"> Total  Records : <b> _TOTAL_ </b></span>',
            paginate: {
                sNext: '<i class="fa-solid fa-angle-right"></i>',
                sPrevious: '<i class="fa-solid fa-angle-left"></i>',
            },
        },
        initComplete: function (settings, json) {},
    });
    // datatable script
    //
    //
    //
    //
    //
};
init_dataTable();
