let form = $("#create_todo");
let validated_form = form.validate({
    errorElement: "span", //default input error message container
    errorClass: "error-block", // default input error message class
    focusInvalid: false, // do not focus the last invalid input
    ignore: "",
    onkeyup: false,
    onclick: false,
    onfocusout: false,
    rules: {
        todo: {
            required: true,
        },
        created_by_user: {
            required: true,
        },  
    },
    messages: {
        todo: {
            required: "Todo is required.",
        },
        created_by_user: {
            required: "Please select user.",
        },

    },
    submitHandler: function (form) {
        form.submit();
    },
    highlight: function (element) {
        $(element).closest(".input-group").addClass("has-error"); // set error class to the control group
    },
    errorPlacement: function (error, element) {
        error.insertAfter($(element).closest(".input_wrapper")); // for other inputs, just perform default behavoir
    },
    invalidHandler: function (event, validator) {
        // $(".form-common-alert", $("#login_form")).show();
        // if(validator.errorList.length > 0) {
        //     $(".form-common-alert", $("#login_form")).html(validator.errorList[0].message);
        // }
    },
});
$(document).ready(function () {

    $(document).on("change", "#create_todo_for", function (e) {
        let create_for_myself = parseInt($(this).val());
        let user_list_dropdown = $(".users_list");
        if (!create_for_myself) {
            user_list_dropdown.removeClass("hide");
            user_list_dropdown.find("select").prop("disabled", false);
            $('#created_by_admin').show()
        } else {
            $('#created_by_admin').hide()
            user_list_dropdown
                .find("select")
                .prop("disabled", true)
                .val(null)
                .trigger("change");
            user_list_dropdown.addClass("hide");
        }
    });
    $("#create_todo_for").trigger("change");
});
