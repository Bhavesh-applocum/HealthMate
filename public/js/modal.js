$(document).ready(function () {
    var tl = anime.timeline({
        //easing: 'easeOutExpo',
        //duration: 750,
        autoplay: true,
        loop: false,
    });

    tl.add({
        targets: ".success",
        scale: [0.001, 1],
        rotate: [100, 360],
        opacity: [0.001, 1],
        //easing: 'easeOutExpo',
        //translateY: 50,
        duration: 1000,
    })

        .add(
            {
                targets: ".checkmark",
                // transformOrigin: ['50% 50% 0px', '50% 50% 0px'],
                // scale:[0.001, 1],
                duration: 500,
                easing: "easeInOutSine",

                strokeDashoffset: [anime.setDashoffset, 0],
            },
            200
        )

        .add(
            {
                targets: ".line1",
                transformOrigin: ["50% 50% 0px", "50% 50% 0px"],
                opacity: {
                    value: [0, 1],
                    delay: 50,
                },
                scale: [0.0, 1],
                duration: 1000,
            },
            400
        )

        .add(
            {
                targets: ".line2",
                transformOrigin: ["50% 50% 0px", "50% 50% 0px"],
                opacity: {
                    value: [0, 1],
                    delay: 50,
                },
                scale: [0.001, 1],
                duration: 1000,
            },
            300
        )

        .add(
            {
                targets: ".line3",
                transformOrigin: ["50% 50% 0px", "50% 50% 0px"],
                opacity: {
                    value: [0, 1],
                    delay: 50,
                },
                scale: [0.001, 1],
                duration: 1000,
            },
            400
        )

        .add(
            {
                targets: ".line4",
                transformOrigin: ["50% 50% 0px", "50% 50% 0px"],
                opacity: {
                    value: [0, 1],
                    delay: 50,
                },
                scale: [0.001, 1],
                duration: 1000,
            },
            400
        )

        .add(
            {
                targets: ".line5",
                transformOrigin: ["50% 50% 0px", "50% 50% 0px"],
                opacity: {
                    value: [0, 1],
                    delay: 50,
                },
                scale: [0.001, 1],
                duration: 1000,
            },
            300
        )

        .add(
            {
                targets: ".line6",
                transformOrigin: ["50% 50% 0px", "50% 50% 0px"],
                opacity: {
                    value: [0, 1],
                    delay: 50,
                },
                scale: [0.001, 1],
                duration: 1000,
            },
            400
        )

        .add(
            {
                targets: ".line7",
                transformOrigin: ["50% 50% 0px", "50% 50% 0px"],
                opacity: {
                    value: [0, 1],
                    delay: 50,
                },
                scale: [0.001, 1],
                duration: 1000,
            },
            300
        )

        .add(
            {
                targets: ".line8",
                transformOrigin: ["50% 50% 0px", "50% 50% 0px"],
                opacity: {
                    value: [0, 1],
                    delay: 50,
                },
                scale: [0.001, 1],
                duration: 1000,
            },
            400
        )

        .add(
            {
                targets: [
                    ".line1",
                    ".line2",
                    ".line3",
                    ".line4",
                    ".line5",
                    ".line6",
                    ".line7",
                    ".line8",
                ],
                opacity: [1, 0],
                //delay:100,
                duration: 300,
                easing: "easeInSine",
                // endDelay:500
            },
            "-=300"
        )

        .add(
            {
                targets: [".success"],
                opacity: [1, 0],
                delay: 200,
                duration: 300,
                easing: "easeInSine",
                endDelay: 500,
            },
            "-=300"
        );

    // open delete cofirmation modal
    $(document).on("click", ".delete-btn", function (e) {
        let id = $(this).data("id");
        let url = $(this).data("url");
        $("#delete_confimation_modal").find("#d_id").val(id);
        $("#delete_confimation_modal").find("#d_url").val(url);
        $("#delete_confimation_modal").modal("show");
    });
    $(document).on(
        "click",
        "#delete_confimation_modal .confirm-btn",
        function () {
            let id = $("#delete_confimation_modal").find("#d_id").val();
            let url = $("#delete_confimation_modal").find("#d_url").val();

            $.ajax({
                url: url,
                type: "DELETE",
                data: {
                    id: id,
                },
                headers: {
                    "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr(
                        "content"
                    ),
                },
                success: function (response) {
                    $("#delete_confimation_modal")
                        .find(".success-popover")
                        .removeClass("hide")
                        .show();
                    tl.restart();
                    setTimeout(() => {
                        $("#delete_confimation_modal").modal("hide");
                        $("#dataTable").DataTable().draw("page");
                    }, 2000);
                },
                error: function (xhr, error, thrown) {
                    console.log(xhr);
                },
            });
        }
    );
    $("#delete_confimation_modal").on("hidden.bs.modal", function () {
        $("#delete_confimation_modal")
            .find(".success-popover")
            .addClass("hide")
            .hide();
    });
    $(document).on('click','#delete_confimation_modal',function(e){
        if(e.target.id == 'delete_confimation_modal'){
            $("#delete_confimation_modal").addClass('vibrate');
            setTimeout(() => {
                $("#delete_confimation_modal").removeClass('vibrate');
            }, 1000);
        }
    })
});
