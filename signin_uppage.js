document.getElementById('loginForm').addEventListener('submit', function (event) {
    event.preventDefault();

    const email = document.getElementById('email').value;
    const password = document.getElementById('password').value;

    if (email && password) {
        fetch('login.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({ email, password })
        })
        .then(response => response.json())
        .then(data => {
            console.log('Login response:',data);

            if (data.success) {
                console.log('Redirecting to:', data.redirect);
                window.location.href = data.redirect; // Redirect user based on session
            } else {
                alert('Login failed: ' + data.message);
            }
        })
        .catch(error => console.error('Error:', error));
    } else {
        alert('Please fill in all fields.');
    }
});

// SIGNUP FORM HANDLER
document.getElementById('signupForm').addEventListener('submit', function (event) {
    event.preventDefault();

    const username = document.getElementById('username').value;
    const email = document.getElementById('signupEmail').value;
    const password = document.getElementById('signupPassword').value;
    const reentryPassword = document.getElementById('signupReenterPassword').value;

    if (username && email && password && reentryPassword) {
        fetch('signup.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({ username, email, password, reentrypassword: reentryPassword })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                alert(data.message);
                window.location.href = 'signin_uppage.html'; // Redirect to login page
            } else {
                alert('Signup failed: ' + data.message);
            }
        })
        .catch(error => console.error('Error:', error));
    } else {
        alert('Please fill in all fields.');
    }
});


// Toggle between Login and Sign Up forms
document.addEventListener("DOMContentLoaded", function () {
    const showLoginButton = document.getElementById("showLogin");
    const showSignupButton = document.getElementById("showSignup");
    const loginForm = document.getElementById("loginForm");
    const signupForm = document.getElementById("signupForm");

    // Show Login Form and hide Sign Up Form
    showLoginButton.addEventListener("click", function (e) {
        e.preventDefault(); // Prevent default button behavior
        loginForm.classList.add("active"); // Show Login Form
        signupForm.classList.remove("active"); // Hide Sign Up Form
        showLoginButton.classList.add("active"); // Highlight Login Button
        showSignupButton.classList.remove("active"); // Unhighlight Sign Up Button
    });

    // Show Sign Up Form and hide Login Form
    showSignupButton.addEventListener("click", function (e) {
        e.preventDefault(); // Prevent default button behavior
        signupForm.classList.add("active"); // Show Sign Up Form
        loginForm.classList.remove("active"); // Hide Login Form
        showSignupButton.classList.add("active"); // Highlight Sign Up Button
        showLoginButton.classList.remove("active"); // Unhighlight Login Button
    });

    // Initialize the page with the Login Form visible
    loginForm.classList.add("active"); // Ensure Login Form is visible by default
    showLoginButton.classList.add("active"); // Ensure Login Button is highlighted by default
});

// Toggle Password Visibility for Login Form
document.addEventListener("DOMContentLoaded", function () {
    const toggleLoginPassword = document.getElementById("togglePassword");
    const loginPasswordField = document.getElementById("password");

    if (toggleLoginPassword && loginPasswordField) {
        toggleLoginPassword.addEventListener("click", function () {
            if (loginPasswordField.type === "password") {
                loginPasswordField.type = "text"; // Show password
                toggleLoginPassword.innerHTML = '<i class="fas fa-eye-slash"></i>'; // Change button text/icon
            } else {
                loginPasswordField.type = "password"; // Hide password
                toggleLoginPassword.innerHTML = '<i class="fas fa-eye"></i>'; // Change button text/icon
            }
        });
    }

    // Toggle Password Visibility for Sign Up Form
    const toggleSignupPassword = document.getElementById("toggleSignupPassword");
    const signupPasswordField = document.getElementById("signupPassword");
    const toggleSignupReenterPassword = document.getElementById("toggleSignupReenterPassword");
    const signupReenterPasswordField = document.getElementById("signupReenterPassword");

    if (toggleSignupPassword && signupPasswordField) {
        toggleSignupPassword.addEventListener("click", function () {
            if (signupPasswordField.type === "password") {
                signupPasswordField.type = "text"; // Show password
                toggleSignupPassword.innerHTML = '<i class="fas fa-eye-slash"></i>'; // Change button text/icon
            } else {
                signupPasswordField.type = "password"; // Hide password
                toggleSignupPassword.innerHTML = '<i class="fas fa-eye"></i>'; // Change button text/icon
            }
        });
    }

    if (toggleSignupReenterPassword && signupReenterPasswordField) {
        toggleSignupReenterPassword.addEventListener("click", function () {
            if (signupReenterPasswordField.type === "password") {
                signupReenterPasswordField.type = "text"; // Show password
                toggleSignupReenterPassword.innerHTML = '<i class="fas fa-eye-slash"></i>'; // Change button text/icon
            } else {
                signupReenterPasswordField.type = "password"; // Hide password
                toggleSignupReenterPassword.innerHTML = '<i class="fas fa-eye"></i>'; // Change button text/icon
            }
        });
    }
});