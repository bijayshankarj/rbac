# rbac
<---------------------------Overview--------------------------->
Project Name: User Role-Based Access Control (RBAC) System

Description: This project is a role-based access control system with an admin and user interface. It uses HTML, CSS, JavaScript, and PHP for frontend and backend development. The MySQL database stores user data.

<---------------------Log in Details---------------------->
For Master Admin (RBAC/index.php)
email:		bijay@bijay.com
password:	bj
For Users (RBAC/users/index.php)
1.
email:		abc@xyzz.com
password:	abc
2.
email:		ak@ak.com
password:	ak
3.
email:		hmm@hmm.com
password:	hmmmmmmm
4.
email:		ab@ab.com
password:	7098265988


<---------------------System Requirements---------------------->
PHP 7.0 or higher
MySQL 5.7 or higher
Web server (Apache or Nginx)
phpMyAdmin (optional, for database management)


<----------------------Setup Instructions---------------------->
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


<-----------------------Troubleshooting----------------------->

Issue: Unable to connect to the database.
Solution: Verify the database credentials in connection.php.

Issue: SQL import error.
Solution: Ensure the database rbac exists before importing.

Issue: PHP not working.
Solution: Ensure PHP is installed and the server is running.
