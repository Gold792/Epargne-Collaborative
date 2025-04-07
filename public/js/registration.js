document.addEventListener("DOMContentLoaded", function () {
    const passwordField = document.getElementById("password");
    const submitButton = document.querySelector("button[type='submit']");

    passwordField.addEventListener("blur", function () {
        const password = passwordField.value;

        if (!validatePassword(password)) {
            alert("⚠️ Le mot de passe doit contenir au moins :\n- 8 caractères\n- Une majuscule\n- Une minuscule\n- Un chiffre\n- Un caractère spécial (@, $, !, %, *, ?, &)");
            submitButton.disabled = true;
        } else {
            submitButton.disabled = false;
        }
    });

    function validatePassword(password) {
        const regex = /^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{8,}$/;
        return regex.test(password);
    }
});
console.log(document.getElementById("password"));
