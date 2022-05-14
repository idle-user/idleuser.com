recaptchaCheck();

function recaptchaCheck() {
    if (document.getElementById('recaptchaDiv')) {
        $("#recaptchaSubmitBtn").prop('disabled', true);
    }
}

function expiredRecaptchaCallback() {
    $("#recaptchaSubmitBtn").prop('disabled', true);
}

function recaptchaCallback() {
    $("#recaptchaSubmitBtn").prop('disabled', false);
}