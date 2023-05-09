let form = $("#register_form");

let validated_form = form.validate({
    errorElement: "span", //default input error message container
    errorClass: "error-block", // default input error message class
    focusInvalid: false, // do not focus the last invalid input
    ignore: "",
    onkeyup: false,
    onclick: false,
    onfocusout: false,
    rules: {
        first_name: {
            required: true,
            minlength: 2,
            maxlength: 50
        },
        last_name: {
            required: true,
            minlength: 2,
            maxlength: 50
        },
        email: {
            required: true,
            email: true,
            minlength: 5,
            maxlength: 50,
            checkEmailFormat: true,
        },
        password: {
            required: true,
            minlength: 8,
            maxlength: 50,
            checkPasswordFormat: true,
        },
        confirm_password: {
            required: true,
            equalTo: "#password"
        },
    },
    messages: {
        first_name: {
            required: "First name is required",
            minlength: "First name must be at least 2 characters",
            maxlength: "First name must be less than 50 characters"
        },
        last_name: {
            required: "Last name is required",
            minlength: "Last name must be at least 2 characters",
            maxlength: "Last name must be less than 50 characters"
        },
        email: {
            required: "Email is required",
            email: "Email is invalid",
            minlength: "Email must be at least 5 characters",
            maxlength: "Email must be less than 50 characters"
        },
        password: {
            required: "Password is required",
            minlength: "Password must be at least 8 characters",
            maxlength: "Password must be less than 50 characters"
        },
        confirm_password: {
            required: "Confirm password is required",
        },
    },
    submitHandler: function (form) {
        form.submit();
    },
    highlight: function (element) {
        $(element).closest(".input-group").addClass("has-error"); // set error class to the control group
    },
    errorPlacement: function (error, element) {
        error.insertAfter($(element).closest('.input_wrapper')); // for other inputs, just perform default behavoir
    },
    invalidHandler: function (event, validator) {
        // $(".form-common-alert", $("#login_form")).show();
        // if(validator.errorList.length > 0) {
        //     $(".form-common-alert", $("#login_form")).html(validator.errorList[0].message);
        // }

    },
});
