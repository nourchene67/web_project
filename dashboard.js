document.addEventListener('DOMContentLoaded', function() {
    const addPasswordForm = document.getElementById('addPasswordForm');
    const passwordTableBody = document.getElementById('passwordTableBody');
    const logoutButton = document.getElementById('logoutButton');

    addPasswordForm.addEventListener('submit', function(event) {
        event.preventDefault();
        const formData = new FormData(addPasswordForm);
        fetch('add_password.php', {
            method: 'POST',
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                loadPasswords();
            } else {
                alert(data.message);
            }
        });
    });

    logoutButton.addEventListener('click', function() {
        fetch('logout.php', {
            method: 'GET'
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                window.location.href = 'index.html';
            } else {
                alert(data.message);
            }
        });
    });

    function loadPasswords() {
        fetch('get_passwords.php')
        .then(response => response.json())
        .then(data => {
            passwordTableBody.innerHTML = '';
            if (data.success) {
                data.passwords.forEach(password => {
                    const row = document.createElement('tr');
                    row.innerHTML = `
                        <td>${password.website}</td>
                        <td>${password.username}</td>
                        <td>
                            <span class="blurred" id="password-${password.id}">${password.password}</span>
                            <button onclick="toggleBlur(${password.id})">Show/Hide</button>
                        </td>
                        <td>
                            <button onclick="deletePassword(${password.id})">Delete</button>
                            <button onclick="modifyPassword(${password.id})">Modify</button>
                        </td>
                    `;
                    passwordTableBody.appendChild(row);
                });
            } else {
                passwordTableBody.innerHTML = '<tr><td colspan="4">No passwords stored</td></tr>';
            }
        });
    }

    window.deletePassword = function(id) {
        fetch(`delete_password.php?id=${id}`, {
            method: 'GET'
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                loadPasswords();
            } else {
                alert(data.message);
            }
        });
    }

    window.modifyPassword = function(id) {
        const newPassword = prompt("Enter new password");

        if (newPassword) {
            const formData = new FormData();
            formData.append('id', id);
            formData.append('password', newPassword);

            fetch('modify_password.php', {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    loadPasswords();
                } else {
                    alert(data.message);
                }
            });
        }
    }

    window.toggleBlur = function(id) {
        const passwordElement = document.getElementById(`password-${id}`);
        passwordElement.classList.toggle('blurred');
    }

    // Load passwords on page load
    loadPasswords();
});
