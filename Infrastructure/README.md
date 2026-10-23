## DDBB Mysqli

The database architecture in my case will be done manually. Why? Because I want to refresh and improve my knowledge of how databases work. To start, we'll run the following command:

```bash
apt install mariadb-server apache2 php-mysql
service mysql start
lsof -i:3306
service apache2 start
lsof -i:8080
```

Remember that enabling the mariadb service enables port 3306, and apache2 normally uses port 80.

We'll also use a bit of PHP, because I'm going to build a small script in this language to be able to display the page and connect it to the database so I can run queries against it.

So in this lab we'll use PHP scripting and a bit of Python for the attack phase.

First of all, the password should never be in plain text. In my case I'm doing it because this is only for educational purposes. At least it should be HASHED , even though it can be cracked, it is safer than leaving it in plain text.

[! Table creation](screenshots/01_mariadb_table.png)
[!Users](screenshots/02_mariadb_all_users.png)

Well, for the PHP script that I'll build, I'll create a special user to connect the DB with the PHP script.
This is going to be the user I'll use in the PHP script to connect:

```bash
create user 'example'@'localhost' identified by 'example123';
grant all privileges on ddbb_Name.* to 'example'@'localhost';
```

After this we go to /var/www/html where I'll create my PHP script to be able to run queries against the DB.

```php
<?php
  ini_set('display_errors', 1);
  ini_set('display_startup_errors', 1);
  error_reporting(E_ALL);

  $server='127.0.0.1';
  $username="example";
  $password="example123";
  $database="ddbb_Name";

  // Database connection
  $conn = new mysqli($server, $username, $password, $database);

  $id = mysqli_real_escape_string($conn, $_GET['id']);
  
  //$id = $_GET['id'];

  $data = mysqli_query($conn, "SELECT username FROM users WHERE id = $id");
  
  $response = mysqli_fetch_array($data);

  if (!isset($response['username'])){
    http_response_code(404);
  } 
?>
```
