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
//sidebaar
$(document).on("click", ".client-view-btn", function () {
    let url = $(this).data("url");
    $.ajax({
        url: url,
        method: "GET",
        success: function (data) {
            console.log(data);
            let clientData = data.data
            $(".editButton").attr('href',clientData.edit_link)
            $(".clientName").html(clientData.name);
            $(".clientEmail").html(clientData.email);
            $(".clientPhone").html(clientData.phone);
            $(".clientAddress").html(clientData.address);
            $(".client_profile").attr('src',clientData.avatar?"/"+clientData.avatar:'/images/user.svg')
        },
    });
    $(".Fsidebar").css({
        transform: "translateX(0)",
        opacity: "1",
    });
    setTimeout(() => {
        $(".fsideDiv").css({
            transform: "translateX(0)",
            opacity: "1",
        });
    }, 200);
});

$(document).on("click", ".candidate-view-btn", function () {
    let url = $(this).data("url");
    $.ajax({
        url: url,
        method: "GET",
        success: function (data) {
            console.log(data);
            let canData = data.data
            $(".editButton").attr('href',canData.edit_link)
            $(".candidateName").html(canData.name);
            $(".candidateEmail").html(canData.email);
            $(".candidatePhone").html(canData.phone);
            $(".candidateGender").html(canData.gender);
            $(".candidateRole").html(canData.role);
            $(".candidate_profile").attr('src',canData.avatar?"/"+canData.avatar:'/images/user.svg')
        },
    });
    $(".Fsidebar").css({
        transform: "translateX(0)",
        opacity: "1",
    });
    setTimeout(() => {
        $(".fsideDiv").css({
            transform: "translateX(0)",
            opacity: "1",
        });
    }, 200);
});

$(document).ready(function () {
    $(".right_main").scroll(function () {
        let scrolled = $(this).scrollTop();
        if (scrolled == 0) {
            $(".Fsidebar").css(
                {
                    display: "block",
                    transform: "translateX(110%)",
                    opacity: "0",
                },
                400
            );
        } else {
            $(".Fsidebar").css(
                {
                    display: "block",
                    transform: "translateX(110%)",
                    opacity: "0",
                },
                400
            );
        }
    });
});

$(".dataTable-design").on("click", function () {
    let scrolled = $(this).scrollTop();
    if (scrolled == 0) {
        $(".Fsidebar").css(
            {
                display: "block",
                transform: "translateX(110%)",
                opacity: "0",
            },
            400
        );
    } else {
        $(".Fsidebar").css(
            {
                display: "block",
                transform: "translateX(110%)",
                opacity: "0",
            },
            400
        );
    }
});

$(".sidebarCloseButton").click(function () {
    $(".fsideDiv").css({
        transform: "translateX(-100px)",
        opacity: "0",
    });
    setTimeout(() => {
        $(".Fsidebar").css({
            transform: "translateX(110%)",
            opacity: "0",
        });
    }, 200);
});

function previewImg() {
    var file = document.getElementById("avatar_img").files;
            if (file.length > 0) {
                var fileReader = new FileReader();

                fileReader.onload = function() {
                    document.getElementById("show").setAttribute("src", event.target.result)
                };

                fileReader.readAsDataURL(file[0]);
            }
}
