$(document).ready(function () {
    const init_dataTable = () => {
        // if (!from_search_btn) {
        //     query_str = "";
        // }
        //
        //
        //
        //
        //
        // datatable script
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
                        return "CAN-" + ("000" + row.id).substr(-3);
                    },
                },
                {
                    data: "name",
                    name: "name",
                },
                {
                    data: "gender",
                    name: "gender",
                },
                {
                    data: "role",
                    name: "role",
                },
                {
                    data: "email",
                    render: function (data, type, row) {
                        return `<a href="mailto:${row.email}" class="can_mail" target="_blank">${row.email}</a>`;
                    },
                    name: "email",
                },
                {
                    data: "phone",
                    name: "phone",
                },
                {
                    data: "created_at",
                    name: "created_at",
                },
                {
                    data: "action",
                    name: "action",
                    orderable: false,
                    searchable: false,
                },
            ],
            "dom": 'r<"table-responsive"t><"bottom"<"dataInfo"li><"dataPaginate"p>><"clear">',// <"top">
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
                sInfoFiltered: "",
                paginate: {
                    sNext: '<img src="/images/angle-right.svg">',
                    sPrevious: '<img src="/images/angle-left.svg">',
                }
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
});
