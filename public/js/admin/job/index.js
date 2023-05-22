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
                        return "CON-" + ("000" + row.id).substr(-3);
                    },
                },
                {
                    data: "title",
                    name: "title",
                },
                {
                    data: "client",
                    name: "client",
                },
                {
                    data: "date",
                    name: "date",
                },
                {
                    data: "time",
                    name: "start/end time",
                },
                {
                    data: "job_status",
                    name: "job_status",
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
