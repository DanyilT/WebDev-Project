# WebDev-Project
Web Development Server-Side Final Project

## Prerequisites
- PHP (7.4 or higher I assume)
- MySQL (5.7 or higher I assume)

## Installation

1. **Clone the repository:**
    ```sh
    git clone https://github.com/yourusername/WebDev-Project.git
    cd WebDev-Project
    ```

2. **Create the database:**
    - Ensure your MySQL server is running.
      - On Windows:
        ```shell
        # Start MySQL service
        net start mysql80
        
        # Stop MySQL service
        net stop mysql80
        ```
    - Create a database and user with the necessary privileges.

3. **Configure the database connection:**
    - If you are a contributor, you can download the `config.php` as **artifact** from the **GitHub Actions** (last successful `Create Config` job).
      1. Go to the **Actions** tab.
      2. Click on the last successful **Create Config** job.
      3. Download the `config.php` artifact.
      4. Extract the `config.php` file and place it in the project root directory.
    - If you are not a contributor, you can create or edit the `config.php` file manually:
      - Create a new file named `config.php` in the project root directory, or open the existing [`config.php`](config.php) file (it has an empty fields).
      - Edit the [`config.php`](config.php) file with your database connection details:
          ```php
          return [
              'host' => 'localhost',
              'dbname' => 'your_database_name',
              'user' => 'your_database_user',
              'password' => 'your_database_password',
              'options' => [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]
          ];
          ```

## Running MySQL Server

Ensure your MySQL server is running. You can start it using the following command (depending on your system):

- On Windows:
  ```shell
  # Start MySQL service
  net start mysql80
    
  # Stop MySQL service
  net stop mysql80
  ```

## Running the Project

1. **Start the PHP built-in server:**
    ```sh
    php -S 127.0.0.1:8000 -t public
    ```

2. **Access the application:**
    Open your web browser and go to [`http://127.0.0.1:8000`](http://127.0.0.1:8000).

## Database Initialization

```shell
mysql -u <your_database_user> -p
enter password: <your_database_password>
```
```shell
#if exist, drop the database
DROP DATABASE IF EXISTS qwertyDB;
```
```shell
#create the database
CREATE DATABASE IF NOT EXISTS qwertyDB;
```
```shell
#creare database tables (schema) -- run the install.php script in another shell (or browser)
php install.php
```
```shell
#use and populate the database
source data/populate.sql
```

1. **Run the database initialization script:**
    - Run the `install.php` script to create the necessary tables and initial data:
        ```sh
        php install.php
        ```
    - Or you can run `php -S 127.0.0.1:8000` and open [`http://127.0.0.1:8000/install.php`](http://127.0.0.1:8000/install.php) in your browser.
    - Or you can run the SQL script manually:
        ```sh
        mysql -u <your_database_user> -p
        enter password: <your_database_password>
        CREATE DATABASE IF NOT EXISTS qwertyDB;
        ```
        ```sh
        mysql -u your_database_user -p your_database_name < data/init.sql
        ```

## Additional Information

- **Database Initialization Script:**
    The database initialization script is located in `data/init.sql`. It contains the necessary SQL commands to create the required tables and initial data.

- **Project Structure:**
    - `public/`: Contains the public-facing files, including the entry point `index.php`.
    - `src/`: Contains the PHP source code.
    - `data/`: Contains the database initialization script.
    - `config.php`: Contains the database configuration.
    - `install.php`: Script to initialize the database.
