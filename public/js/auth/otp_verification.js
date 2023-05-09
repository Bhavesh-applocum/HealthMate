$(document).ready(function() {
    ctrlDown = false;
    ctrlKey = 17;
    cmdKey = 91;
    $(document).keydown(function(e) {
        if (e.keyCode == ctrlKey || e.keyCode == cmdKey) ctrlDown = true;
    }).keyup(function(e) {
        if (e.keyCode == ctrlKey || e.keyCode == cmdKey) ctrlDown = false;
    });

    $(document).on('keydown', '.otpInput', function(event) {
        console.log(event.keyCode)
        if (event.key === "Backspace") {
            $(this).val('').addClass('border border-danger');
            if (!$(this).is(':first-child')) {
                $(this).prev().focus();
            }
            event.preventDefault()
        } else {
            if ($(this).is(':last-child') && $(this).val() !== '') {
                return true;
            } else if (event.keyCode > 47 && event.keyCode < 58 || event.keyCode > 95 && event.keyCode < 106) {
                $(this).val(event.key).removeClass('border border-danger');
                if (!$(this).is(':last-child')) {
                    console.log('not')
                    $(this).next().focus();
                }
                event.preventDefault();
            } else if (event.keyCode > 64 && event.keyCode < 91) {
                if ((ctrlDown && (event.keyCode == 86) && $(this).is(':first-child'))) {} else {
                    event.preventDefault();
                }
            }
        }
    });

    $('#verify-otp').click(function() {
        var flag = true;
        $('.otpInput').filter(function() {
            if (this.value == '') {
                $(this).addClass('border border-danger');
                flag = false;
            } else {
                $(this).removeClass('border border-danger');
            }
        });
        if (!flag) {
            alert('You are missing some OTP inputs')
            return false;
        }
    })
    $(document).on('paste', function(e) {
        var pastedData = e.originalEvent.clipboardData.getData('text');
        var pastedChars = pastedData.split("");
        otpInps = $('.otpInput')
        Array.prototype.forEach.call(otpInps, function(inp, index) {
            $(inp).val(pastedChars[index])
            $(inp).removeClass('border border-danger');
        })
        $('.otpInput:last-child').focus();
    })
});