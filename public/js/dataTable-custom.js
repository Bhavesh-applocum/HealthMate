// hide close-icon on click
$(document).on("click", ".dataTable-search-clear", function () {
    $(this).removeClass("show");
    $("#dataTable-search").val("");
    $("#dataTable").DataTable().search("").draw();
});
// hide close-icon on click

// dataTable Search
$("#dataTable-search").keyup(function (e) {
    valLen = $(this).val().length;
    if (valLen > 2) {
        e.preventDefault();
        $("#dataTable").DataTable().search($(this).val()).draw();
        $(this).next().addClass("show");
    }
    if ($(this).val() == "") {
        e.preventDefault();
        $("#dataTable").DataTable().search($(this).val()).draw();
        $(this).next().removeClass("show");
    }
});
// dataTable Search

const adjustPagination = (oSettings) => {
    if (oSettings._iDisplayLength > oSettings.fnRecordsDisplay()) {
        $(oSettings.nTableWrapper).find(".dataTables_paginate").hide();
    } else {
        $(oSettings.nTableWrapper).find(".dataTables_paginate").show();
    }
};
$(document).ready(function () {
    const check_Previous_DataTable_State = () => {
        // get current url
        let storagekey = `DataTables_dataTable_${window.location.pathname}`;

        let PreviousData = JSON.parse(localStorage.getItem(storagekey));
        let searchVal = "";
        if (PreviousData) {
            searchVal = PreviousData.search.search;
            if (searchVal) {
                $("#dataTable-search").val(searchVal);
                $("#dataTable-search").next().addClass("show")
                $("#dataTable-search").change();
            }
        }
    };
    check_Previous_DataTable_State();
});
