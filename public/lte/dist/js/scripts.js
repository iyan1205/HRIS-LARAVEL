$(document).ready(function() {
    var rememberEmail = localStorage.getItem("rememberEmail");
    var rememberPassword = localStorage.getItem("rememberPassword");
    if (rememberEmail && rememberPassword) {
        $("#email").val(rememberEmail);
        $("#password").val(rememberPassword);
        $("#flexCheckDefault").prop("checked", true);
    }
});

function login() {
    var remember = $("#flexCheckDefault").prop("checked");
    if (remember) {
        var email = $("#email").val();
        var password = $("#password").val();
        localStorage.setItem("rememberEmail", email);
        localStorage.setItem("rememberPassword", password);
    } else {
        localStorage.removeItem("rememberEmail");
        localStorage.removeItem("rememberPassword");
    }
}