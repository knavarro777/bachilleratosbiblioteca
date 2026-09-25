# Database Management Application in Python (MySQL)

## Project Description
This project is a graphical user interface (GUI) application made with **Python** to manage and navigate **MySQL** databases. It allows users to log in with their MySQL account, look through databases and tables, run custom SQL queries, and perform basic CRUD operations (Create, Read, Update, Delete) on records.

---

## Features and Basic Functionalities

Here are the main features developed for this project:

### 1. Connection and Login
* **Server Connection:** Successfully connects to an active MySQL server using Python database drivers (like `mysql-connector-python` or `PyMySQL`).
* **Login Form:** A simple screen with required fields to connect:
  * Username (`login`)
  * Password (`pwd`)
  * Server / Host (`server`)
* **Feedback Messages:** Clear pop-up messages to show if the connection was successful or if there was a MySQL error.

### 2. Database and Table Management
* **Database Selection:** Lets you see a list of available MySQL databases on the server and choose one to work with.
* **Table Selection:** Lets you easily switch between different tables inside the selected database.

### 3. Data View and Navigation
* **Grid View:** Shows the MySQL data in a clear table format inside the Python app.
* **Navigation Controls:** Easy-to-use buttons to move through the rows:
  * First
  * Previous
  * Next
  * Last
* **Data Pagination:** Uses SQL queries (`LIMIT` and `OFFSET`) to load data in smaller pages so large datasets don't slow down the Python app.

### 4. SQL Query Execution
* **Query Window:** A space to write and run custom SQL `SELECT` queries directly on MySQL.
* **Execution and Output:** Runs the query and displays the results on the screen in real-time.
* **Basic Security:** Includes checks to prevent basic SQL injection and stop bad commands from running.

### 5. Basic CRUD Interface
* **Record Selection:** Ability to click and view specific records from the table.
* **Form Controls:** Simple buttons and controls to trigger CRUD actions.
* **Adaptive Form:** The form updates dynamically to let you insert, edit, or delete records in the current table.

### 6. Session Management
* **Logout:** Closes the current MySQL connection safely and takes you back to the login screen.
* **Switch Database:** Lets you switch to another database without having to type your password again.
