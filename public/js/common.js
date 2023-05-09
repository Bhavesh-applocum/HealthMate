$(document).ready(function () {
    $(document).on("click", function (e) {
        if (
            !$(e.target).hasClass("user-dropdown-wrapper") &&
            !$(e.target).parents(".user-dropdown-wrapper").length
        ) {
            $(".user-dropdown-wrapper").removeClass("show");
        }
    });
    $(document).on("click", ".user-dropdown-btn", function () {
        $(this).parent().toggleClass("show");
    });
    // hide dropdown when clicked outside

    $(document).on("click", ".sidebar-menu-btn", function (e) {
        $(".sidebar").toggleClass("sidebar--expanded");
        $("body").toggleClass("sidebar--expanded");
    });
    // Add slideDown animation to Bootstrap dropdown when expanding.
    $(".dropdown").on("show.bs.dropdown", function () {
        $(this).find(".dropdown-menu").first().stop(true, true).slideDown(200);
    });

    // Add slideUp animation to Bootstrap dropdown when collapsing.
    $(".dropdown").on("hide.bs.dropdown", function () {
        $(this).find(".dropdown-menu").first().stop(true, true).slideUp(200);
    });

    // select2
    $(".single-select2").each(function () {
        var $select = $(this);
        var $wrapper = $select.closest(".select-wrapper");
        $select.select2({
            width: "100%",
            // add search if select has data-search="1"
            minimumResultsForSearch: $select.data("search") ? 1 : -1,
            dropdownParent: $wrapper,
        });
        // .on("select2:closing", function (e) {
        //     e.preventDefault();
        //     setTimeout(function () {
        //         $(".select2-dropdown").slideUp(200, function () {
        // $('#select2-drop-mask').select2("close");
        //         });
        //     }, 0);
        // });
    });
    // $(".single-select2").on("select2:open", function (e) {
    //     $(".select2-dropdown").hide();
    //     $(".select2-dropdown").slideDown(200);
    // });
    // close sleect2-dropdown2 ith slideUp
    // $(document).on("click", function (e) {
    //     if (
    //         !$(e.target).hasClass("select-wrapper") &&
    //         !$(e.target).parents(".select-wrapper").length
    //     ) {
    //         $(".select2-dropdown").slideUp(200);
    //     }
    // });

});
