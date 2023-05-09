let form = $("#login_form");
let validated_form = form.validate({
    errorElement: "span", //default input error message container
    errorClass: "error-block", // default input error message class
    focusInvalid: false, // do not focus the last invalid input
    ignore: "",
    onkeyup: false,
    onclick: false,
    onfocusout: false,
    rules: {
        username: {
            required: true,
        },
        password: {
            required: true,
            minlength: 8,
            checkPasswordFormat: true,
        },
    },
    messages: {
        email: {
            required: "User Name is required.",
        },
        password: {
            required: "Password is required.",
            minlength: "Password must be at least 8 characters long.",
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
