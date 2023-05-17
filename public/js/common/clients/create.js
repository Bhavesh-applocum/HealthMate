let form = $("#create_client");
let validated_form = form.validate({
    errorElement: "span", //default input error message container
    errorClass: "error-block", // default input error message class
    focusInvalid: false, // do not focus the last invalid input
    ignore: "",
    onkeyup: false,
    onclick: false,
    onfocusout: false,
    rules: {
        practice_name: {
            required: true,
            minlength: 2,
            maxlength: 50,
        },
        email: {
            required: true,
            email: true,
            minlength: 5,
            maxlength: 50,
            checkEmailFormat: true,
        },
        phone: {
            required: true,
            digits: true,
            minlength: 10,
            maxlength: 10,
        },
        address: {
            required: true,
            minlength: 2,
            maxlength: 50,
        },
        area: {
            required: true,
            minlength: 2,
            maxlength: 50,
        },
        post_code: {
            required: true,
            number: true,
            minlength: 4,
            maxlength: 7,
        },
        password: {
            required: true,
            minlength: 8,
            maxlength: 50,
            checkPasswordFormat: true,
        },
        confirmPassword: {
            required: true,
            equalTo: "#password",
        },
    },
    messages: {
        practice_name: {
            required: "Practice name is required",
            minlength: "Practice name must be at least 2 characters",
            maxlength: "Practice name must be less than 50 characters",
        },
        email: {
            required: "Email is required",
            email: "Email is invalid",
            minlength: "Email must be at least 5 characters",
            maxlength: "Email must be less than 50 characters",
        },
        phone: {
            required: "Phone Number is required",
            digits: "Phone Number is invalid",
            minlength: "Phone Number must be at least 10 characters",
            maxlength: "Phone Number must be less than 10 characters",
        },
        address: {
            required: "Address is required",
            minlength: "Address must be at least 2 characters",
            maxlength: "Address must be less than 50 characters",
        },
        area: {
            required: "Area is required",
            minlength: "Area must be at least 2 characters",
            maxlength: "Area must be less than 50 characters",
        },
        post_code: {
            required: "Post code is required",
            minlength: "Post code must be at least 4 characters",
            maxlength: "Post code must be less than 7 characters",
        },
        password: {
            required: "Password is required",
            minlength: "Password must be at least 8 characters",
            maxlength: "Password must be less than 50 characters",
        },
        confirmPassword: {
            required: "Confirm password is required",
            equalTo: "Confirm password must be the same as password",
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
// let validated_form = form.validate({
//     errorElement: 'span', //default input error message container
//     errorClass: 'error-block', // default input error message class
//     focusInvalid : false, // do not focus the last invalid input
//     ignore: "",
//     onkeyup: false,
//     onclick: false,
//     onfocusout: false,
//     rules: {
//         avatar: {
//             extension: "jpg|jpeg|png",
//             filesize: 1048576,
//         },
//         practice_name: {
//             required: true,
//             minlength: 2,
//             maxlength: 50
//         },
//         email: {
//             required: true,
//             email: true,
//             minlength: 5,
//             maxlength: 50,
//             checkEmailFormat: true,
//         },
//         phone: {
//             required: true,
//             phone: true,
//             minlength: 10,
//             maxlength: 10,
//         },
//         address: {
//             required: true,
//             minlength: 2,
//             maxlength: 50,
//         },
//         area: {
//             required: true,
//             minlength: 2,
//             maxlength: 50,
//         },
//         post_code: {
//             required: true,
//             number: true,
//             minlength: 4,
//             maxlength: 7,
//         },
//         password: {
//             required: true,
//             minlength: 8,
//             maxlength: 50,
//             checkPasswordFormat: true,
//         },
//         confirmPassword: {
//             required: true,
//             equalTo: "#password",
//         },
//     },
//     messages: {
//         avatar: {
//             extension: "Please upload file in these format only (jpg, jpeg, png)",
//             filesize: "Please upload file less than 1MB",
//         },
//         practice_name: {
//             required: "Practice name is required",
//             minlength: "Practice name must be at least 2 characters",
//             maxlength: "Practice name must be less than 50 characters"
//         },
//         email: {
//             required: "Email is required",
//             email: "Email is invalid",
//             minlength: "Email must be at least 5 characters",
//             maxlength: "Email must be less than 50 characters"
//         },
//         phone: {
//             required: "Phone Number is required",
//             phone: "Phone Number is invalid",
//             minlength: "Phone Number must be at least 10 characters",
//             maxlength: "Phone Number must be less than 10 characters"
//         },
//         address: {
//             required: "Address is required",
//             minlength: "Address must be at least 2 characters",
//             maxlength: "Address must be less than 50 characters"
//         },
//         area: {
//             required: "Area is required",
//             minlength: "Area must be at least 2 characters",
//             maxlength: "Area must be less than 50 characters"
//         },
//         post_code: {
//             required: "Post code is required",
//             Number: "Post code is invalid",
//             minlength: "Post code must be at least 4 characters",
//             maxlength: "Post code must be less than 7 characters"
//         },
//         password: {
//             required: "Password is required",
//             minlength: "Password must be at least 8 characters",
//             maxlength: "Password must be less than 50 characters"
//         },
//         confirmPassword: {
//             required: "Confirm password is required",
//             equalTo: "Confirm password must be the same as password",
//         },
//     },
//     submitHandler: function(form) {
//         form.submit();
//     },
//     highlight: function (element) {
//         $(element).closest('.input-group').addClass('has-error');
//     },
//     errorPlacement: function (error, element) {
//         error.insertAfter($(element).closest('.input_wrapper'));
//     }
// });

$(document).on("change", ".showPassword", function () {
    if ($(this).is(":checked")) {
        $(".password").attr("type", "text");
        $(this).next().attr("src", "/images/eye-slash.svg");
    } else {
        $(".password").attr("type", "password");
        $(this).next().attr("src", "/images/eye.svg");
    }
});
