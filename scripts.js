document.addEventListener('DOMContentLoaded', function() {
    const signupForm = document.getElementById('signupForm');
    const loginForm = document.getElementById('loginForm');
    const showLoginFormButton = document.getElementById('sign-in-btn');
    const showSignupFormButton = document.getElementById('sign-up-btn');

    signupForm.addEventListener('submit', function(event) {
        event.preventDefault();
        const formData = new FormData(signupForm);
        fetch('signup.php', {
            method: 'POST',
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                showLogin();
            } else {
                alert(data.message);
            }
        });
    });

    loginForm.addEventListener('submit', function(event) {
        event.preventDefault();
        const formData = new FormData(loginForm);
        fetch('login.php', {
            method: 'POST',
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                window.location.href = 'dashboard.html';
            } else {
                alert(data.message);
            }
        });
    });

    showLoginFormButton.addEventListener('click', showLogin);
    showSignupFormButton.addEventListener('click', showSignup);

    function showLogin() {
        document.querySelector('.container').classList.remove('sign-up-mode');
    }

    function showSignup() {
        document.querySelector('.container').classList.add('sign-up-mode');
    }
});
