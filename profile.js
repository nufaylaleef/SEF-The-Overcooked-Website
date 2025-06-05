document.addEventListener('DOMContentLoaded', function () {
    const editButton = document.getElementById('edit-button');
    const profileForm = document.getElementById('profile-form');
    const profileContainer = document.querySelector('.profile-container');
    const imageUpload = document.getElementById('image-upload');
    const profilePic = document.getElementById('profile-picture');
    const togglePassword = document.getElementById('togglePassword');
    const passwordInput = document.getElementById('password');
    const logoutButton = document.getElementById("logout-button");

    togglePassword.addEventListener('click', function () {
        const type = passwordInput.type === 'password' ? 'text' : 'password';
        passwordInput.type = type;
        this.classList.toggle('visible');
    });

    editButton.addEventListener('click', function () {
        const isEditing = profileContainer.classList.toggle('editing');
        const formGroups = profileForm.querySelectorAll('input, select');
        const passwordInput = document.getElementById('password');
    
        formGroups.forEach(element => {
            // Handle password field separately
            if (element === passwordInput) {
                element.readOnly = !isEditing;
            } else {
                if (element.type !== "password") {
                    element.readOnly = !isEditing;
                }
                element.disabled = !isEditing;
            }
        });
    
        if (isEditing) {
            editButton.textContent = 'Save';
            editButton.classList.add('save');
            passwordInput.value = ''; // Clear password field when entering edit mode
        } else {
            saveProfile();
            editButton.textContent = 'Edit';
            editButton.classList.remove('save');
        }
    });

    function saveProfile() {
        // Create FormData object
        const formData = new FormData();
    
        // Manually add each form field
        formData.append('full-name', document.getElementById('full-name').value);
        formData.append('gender', document.getElementById('gender').value);
        formData.append('language', document.getElementById('language').value);
        formData.append('country', document.getElementById('country').value);
        formData.append('email', document.getElementById('email').value);
    
        // Add password only if it's not empty
        const password = document.getElementById('password').value;
        if (password.trim()) {
            formData.append('password', password);
        }
    
        // Add profile picture if selected
        if (imageUpload.files[0]) {
            formData.append('profile-picture', imageUpload.files[0]);
        }
    
        // Debug: Log the data being sent (except password)
        console.log('Sending data:');
        for (let pair of formData.entries()) {
            if (pair[0] === 'password') {
                console.log('password: [HIDDEN]');
            } else {
                console.log(pair[0] + ': ' + pair[1]);
            }
        }
    
        fetch('update_profile.php', {
            method: 'POST',
            body: formData
        })
        .then(response => response.text())
        .then(text => {
            console.log('Raw server response:', text);
            try {
                const data = JSON.parse(text);
                if (data.error) {
                    throw new Error(data.error);
                }
                alert("Profile updated successfully!");
                window.location.reload(); // Reload to show updated values
            } catch (e) {
                console.error('JSON Parse Error:', e);
                console.error('Raw response:', text);
                throw new Error('Server response was not in the expected format');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert(`There was an error updating your profile: ${error.message}`);
        });
    }

    imageUpload.addEventListener('change', function (event) {
        const file = event.target.files[0];
        if (file) {
            // Validate file type
            if (!file.type.startsWith('image/')) {
                alert('Please select an image file');
                return;
            }

            // Validate file size (e.g., max 5MB)
            const maxSize = 5 * 1024 * 1024; // 5MB in bytes
            if (file.size > maxSize) {
                alert('File size must be less than 5MB');
                return;
            }

            const reader = new FileReader();
            reader.onload = function (e) {
                profilePic.src = e.target.result;
            };
            reader.onerror = function (e) {
                console.error('FileReader error:', e);
                alert('Error reading file');
            };
            reader.readAsDataURL(file);
        }
    });

    logoutButton.addEventListener("click", function () {
        fetch("logout.php")
            .then(() => {
                window.location.href = "signin_uppage.html";
            })
            .catch(() => {
                window.location.href = "signin_uppage.html";
            });
    });

    // Function to populate country dropdown
    const countrySelect = document.getElementById('country');
    const countries = ["Malaysia", "Singapore", "Indonesia", "Thailand", "Vietnam", "United States",
                      "Canada", "United Kingdom", "Germany", "France", "Norway", "Australia", "New Zealand"];

    countries.forEach(country => {
        const option = document.createElement('option');
        option.value = country;
        option.text = country;
        countrySelect.appendChild(option);
    });
});