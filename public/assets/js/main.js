let Evently;


function togglePassword(id) {
    const eye_slash = document.getElementById('eye-slash');
    const eye = document.getElementById('eye');
    const input = document.getElementById(id);
    if (input.type === "password") {
        input.type = "text";
        eye_slash.classList.add('hidden');
        eye.classList.remove('hidden');

    } else {
        input.type = "password";
        eye_slash.classList.remove('hidden');
        eye.classList.add('hidden');

    }
}