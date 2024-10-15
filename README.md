# ITCS 333: Room Booking System

This project is a responsive web-based room booking system for the IT College. It allows users to browse available rooms, book them, and manage their bookings. The system also includes administrative functionality for managing rooms and viewing usage analytics.

## Team Members:

| Student ID  | Section | GitHub Username  |
|-------------|---------|------------------|
| 202202095   | 9       | zasaeedd         |
| 202110375   | 8       | hassanjh         |
| 202201254   | 3       | 7ax              |
| 202107650   | 8       | S3lialaali       |
| 202100341   | 3       | Kwaddo           |

## Key Features:
- User Registration and Login
- Room Browsing and Booking
- Admin Panel
- Reporting and Analytics **(Optional)**
- Comment System **(Optional)**

## File Structure:
- **ITCS333-PROJECT/**
  - `README.md`
  - `config.php`
  - `index.html`
  - **css/**
    - `styles.css`
  - **js/**
    - `scripts.js`
  - **db/**
    - `empty_database.sql`

## Installation:
- **Clone the repository**:  
  Open a terminal and run the following command to clone the repository to your local machine:
  ```bash
  git clone https://github.com/zasaeedd/itcs333-project.git
  ```

- **Navigate into the project directory**:  
  After cloning, move into the project directory:
  ```bash
  cd itcs333-project
  ```

- **Set up an Apache server using XAMPP (Windows)**:
  - **Download and install XAMPP**:  
    Download XAMPP from [https://www.apachefriends.org/index.html](https://www.apachefriends.org) and follow the installation instructions.
  - **Start Apache in XAMPP**:  
    Open the XAMPP Control Panel and click the **Start** button next to Apache.
  - **Move project to `htdocs`**:  
    Copy the `itcs333-project` folder into the `htdocs` folder, located in the XAMPP installation directory (usually `C:\xampp\htdocs`).
  - **Access the project in your browser**:  
    Open your browser and go to:
    ```
    http://localhost/itcs333-project
    ```
    This will load the project.



## Contribution Guidelines:
- **Use branches for each feature**:  
  - Create a new branch for each feature you work on:
    ```bash
    git checkout -b feature-name
    ```
    Example:
    ```bash
    git checkout -b registration-feature
    ```

- **Commit your changes**:  
  - After making changes, commit them with a clear message:
    ```bash
    git add .
    git commit -m "Add registration functionality"
    ```

- **Push your branch**:  
  - Push your branch to GitHub:
    ```bash
    git push origin feature-name
    ```

- **Create a Pull Request (PR)**:  
  - Go to GitHub and create a Pull Request (PR) to merge your changes into the main branch.

- **Test your code**:  
  - Test the feature on your local machine to make sure it works as expected and does not affect other parts of the system.

- **Review and approve**:  
  - Team members review the PR on GitHub. Once approved, merge it into the main branch.
