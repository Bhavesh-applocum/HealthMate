$.validator.addMethod("checkEmailFormat", function (value, element) {
    let email = value;
    let emailRegex = /^[a-zA-Z0-9._-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,4}$/;
    return emailRegex.test(email);
}, "Email is invalid");

$.validator.addMethod("checkPasswordFormat", function (value, element) {
    let password = value;
    // pasword regex for at least 1 uppercase letter, 1 lowercase letter , 1 number and 8 characters long and 1 special character
    let passwordRegex = /^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{8,}$/;
    return passwordRegex.test(password);
}, "Password must contain at least 1 uppercase letter, 1 lowercase letter , 1 number and 1 special character");

$.validator.addMethod("isMatchedToPassword", function (value, element) {
    let confirm_password = value;
    let password = $("#password").val();
    return confirm_password === password;
}, "Confirm password must be equal to password");
    
// $.validator.addMethod("filesize", function (value, element) {
//     let file_size = element.files[0].size;
//     return file_size <= 1000000;
// }, "File size must be less than 1MB");

