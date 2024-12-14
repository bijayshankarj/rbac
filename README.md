# rbac
!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!DISCLAIMER!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!

Multiple wrong login attempts will lock the system from attempting more loggin attempts, in such a case, head to the very end of this README.

!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!DISCLAIMER!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!


<----------------------------------------Overview---------------------------------------->

Project Name: Role-Based Access Control (RBAC) System

Description: This project is a role-based access control system with an admin and user interface. It uses HTML, CSS, JavaScript, and PHP for frontend and backend development. The MySQL database stores user data.

<----------------------------------Log in Details----------------------------------->

For Master Admin (RBAC/index.php)

email:		bijay@bijay.com

password:	bj


For Users (RBAC/users/index.php)

1.
email:		abc@xyzz.com

password:	7098265988

role:		viewer


3.
email:		ak@ak.com

password:	7098265988

role:		admin


5.
email:		ab@ab.com

password:	7098265988

role:		user


7.
email:		sabya@sabya.com

password:	7098265988

role:		admin



<----------------------------------System Requirements----------------------------------->

PHP 7.0 or higher

MySQL 5.7 or higher

Web server (Apache or Nginx)

phpMyAdmin (for database management)


<-----------------------------------Setup Instructions----------------------------------->

Step 1: 
Clone or Download the Project

Clone the project repository or download and extract it.

Place the files in your web server's root directory (e.g., htdocs for XAMPP).


Step 2: 
Configure the Database

Open your MySQL server (phpMyAdmin or CLI).

Create a new database called rbac.


Step 3: 
Import the SQL File

In phpMyAdmin:

Select the rbac database.

Go to the Import tab.

Choose the SQL file (rbac.sql) provided with this project.

Click Go to import the database.

Using MySQL CLI

mysql -u [username] -p rbac < rbac.sql


Step 4: Update Configuration

Open the connection.php file.

Replace placeholders with your database credentials (connection.php):

$servername = "localhost";

$username = "your_database_username";

$password = "your_database_password";

$dbname = "rbac";


Step 5: Run the Project

Start your web server and database server (e.g., XAMPP or WAMP).

Access the project in your browser: http://localhost/[project-folder].


<------------------------------------Troubleshooting------------------------------------>

Issue: Unable to connect to the database.

Solution: Verify the database credentials in connection.php.

Issue: SQL import error.

Solution: Ensure the database rbac exists before importing.

Issue: PHP not working.

Solution: Ensure PHP is installed and the server is running.


<----------------------------------------API Details---------------------------------------->

1. 

Login API

Purpose: To authenticate users.

Endpoint: RBAC/index.php

Method: POST

Description: Accepts username and password from the login form, verifies credentials against the database, and starts a session for authenticated users.


2. Registration API

Purpose: To register new users.

Endpoint: register.php

Method: POST

Description: Accepts user details like username, password, and role, hashes the password, and stores the data in the users table.


3. Username Availability Check API

Purpose: To check if a username is already taken during registration.

Endpoint: RBAC/users/search.php

Method: GET

Description: Accepts a username query parameter and checks if it exists in the users table. Returns a message indicating availability.


4. Logout API

Purpose: To log out(Change state from Active to Inactive) a user and destroy the session.

Endpoint: logout.php

Method: GET

Description: Ends the user session and redirects to the login page.


5. Privileged Access API

Purpose: To allow access to privileged features or pages based on user roles.

Endpoint: privilege.php

Method: GET

Description: Checks the user's session for role-based access and either allows or denies access to the privileged page. Displays an alert if the user is unauthorized.


6. Delete User API

Purpose: To delete a user account.

Endpoint: delete-certificate.php

Method: POST

Description: Accepts a user_id and deletes the corresponding user record from the users table.


7. Edit User Details API

Purpose: To update user information (by an authorized user).

Endpoint: edit.php

Method: GET or POST

Description: Fetches or updates user details based on the provided user_id.


Summary of APIs

The following custom APIs are involved in the project:

RBAC/index.php

register.php

RBAC/users/search.php

logout.php

privilege.php

delete-certificate.php

edit.php



<----------------------------------------Security Features---------------------------------------->

Password Hashing:

User passwords are securely hashed using PHP's password_hash() function before being stored in the database.

During login, the hashed password is verified using password_verify(), ensuring secure authentication.


SQL Injection Prevention:

User inputs are sanitized using mysqli_real_escape_string() to prevent SQL injection attacks.

Prepared statements are used for all database queries, ensuring additional security.


Session Management:

User sessions are managed with $_SESSION variables to maintain state securely across pages.

Sessions are initialized upon login and destroyed upon logout or inactivity.


Brute Force Protection:

Login attempts are tracked using a session-based counter ($_SESSION['login_attempts']) to lock out users after multiple failed login attempts.


Role-Based Access Control (RBAC):

Different roles (e.g., admin and users) have distinct permissions.

Admins have access to specific features (e.g., deleting users, privilege settings) that are restricted for regular users.


Timeout and Auto Logout:

Sessions automatically expire after 1 hour (3600 seconds) of inactivity ($_SESSION['inactive']), protecting accounts from unauthorized access due to prolonged inactivity.


Input Validation:

Frontend and backend validations are applied to user inputs (e.g., email format, password strength) to ensure data integrity and security.


<-------------------------------------Special Unlock Feature------------------------------------->

Admin/User Unlock: 

After multiple failed login attempts, the system is locked for 24 hours and a countdown timer is displayed on the screen as to when the user can try logging in again, in such cases, a special key is required to unlock the system without having to wait for 24 hours to try logging in again. 

This is implemented to get through the anti brute force locking mechanism so the key must be kept private.

The email and password fields must contain "qwerty" and "whyyouwannaknow?" respectively (without quotations) before hitting login button to unlock the system.
