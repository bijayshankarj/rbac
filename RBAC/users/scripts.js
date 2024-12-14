document.addEventListener('DOMContentLoaded', () => {
    const addUserForm = document.getElementById('addUserForm');
    const userTable = document.getElementById('userTable');
    const addRoleForm = document.getElementById('addRoleForm');
    const roleList = document.getElementById('roleList');

    const apiBaseUrl = 'backend.php';

    // Fetch initial data
    fetchUsers();
    fetchRoles();

    // Add User Form Submission
    addUserForm.addEventListener('submit', (e) => {
        e.preventDefault();
        const username = document.getElementById('username').value;
        const role = document.getElementById('role').value;

        fetch(`${apiBaseUrl}?type=users`, {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({ username, role })
        })
            .then(response => response.json())
            .then(data => {
                alert(data.message);
                fetchUsers();
                addUserForm.reset();
            });
    });

    // Add Role Form Submission
    addRoleForm.addEventListener('submit', (e) => {
        e.preventDefault();
        const roleName = document.getElementById('roleName').value;

        fetch(`${apiBaseUrl}?type=roles`, {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({ role: roleName })
        })
            .then(response => response.json())
            .then(data => {
                alert(data.message);
                fetchRoles();
                addRoleForm.reset();
            });
    });

    // Fetch and Display Users
    function fetchUsers() {
        fetch(`${apiBaseUrl}?type=users`)
            .then(response => response.json())
            .then(users => {
                userTable.innerHTML = '';
                users.forEach(user => {
                    const row = document.createElement('tr');
                    row.innerHTML = `
                        <td>${user.username}</td>
                        <td>${user.role}</td>
                        <td>${user.status}</td>
                        <td>
                            <button onclick="deleteUser('${user.username}')">Delete</button>
                        </td>
                    `;
                    userTable.appendChild(row);
                });
            });
    }

    // // Fetch and Display Roles
    // function fetchRoles() {
    //     fetch(`${apiBaseUrl}?type=roles`)
    //         .then(response => response.json())
    //         .then(roles => {
    //             roleList.innerHTML = '';
    //             roles.forEach(role => {
    //                 const listItem = document.createElement('li');
    //                 listItem.textContent = role;
    //                 roleList.appendChild(listItem);
    //             });
    //         });
    // }
    function fetchRoles() {
        fetch(`${apiBaseUrl}?type=roles`)
            .then(response => response.json())
            .then(roles => {
                // Update role list in the "Role Management" section
                roleList.innerHTML = '';
                roles.forEach(role => {
                    const listItem = document.createElement('li');
                    listItem.textContent = role;
                    roleList.appendChild(listItem);
                });
    
                // Update dropdown menu for user roles
                const roleDropdown = document.getElementById('role');
                roleDropdown.innerHTML = ''; // Clear existing options
                roles.forEach(role => {
                    const option = document.createElement('option');
                    option.value = role;
                    option.textContent = role;
                    roleDropdown.appendChild(option);
                });
            });
    }
    

    // Delete User
    window.deleteUser = function (username) {
        fetch(`${apiBaseUrl}?type=users`, {
            method: 'DELETE',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({ username })
        })
            .then(response => response.json())
            .then(data => {
                alert(data.message);
                fetchUsers();
            });
    };
});
