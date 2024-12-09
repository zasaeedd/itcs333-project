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
- Profile Management with Image Upload
- Room Browsing and Booking
- Booking Management (View/Delete)
- Admin Panel

## File Structure:
- **ITCS333-PROJECT/**
  - `README.md`
  - `config.php`
  - `index.html`
  - **css/**
    - `styles.css`
    - `roomstyle.css`
    - `editprofile.css`
  - **js/**
    - `scripts.js`
  - **db/**
    - `schema.sql`
    - **seed/**
      - `users.sql`
      - `rooms.sql`
  - **public/**
    - **auth/**
      - `login.php`
      - `register.php`
    - **room-browsing/**
      - `room_browse.php`
      - `room_details.php`
      - `book_room.php`
    - **profile-management/**
      - `profile_page.php`
      - `profile.php`
      - `update_profile.php`
    - **images/**
      - `bxs-exit.svg`
      - `bxs-user.svg`
      - `bxs-trash.svg`
      - `default-profile.jpg`

## Installation:
- **Clone the repository**:  
  ```bash
  git clone https://github.com/zasaeedd/itcs333-project.git
  ```

- **Set up the database**:
  1. Create a new MySQL database
  2. Import `db/schema.sql`
  3. Import seed files from `db/seed/`

- **Configure the application**:
  1. Copy `config.example.php` to `config.php`
  2. Update database credentials in `config.php`

- **Set up an Apache server using XAMPP**:
  1. Install XAMPP
  2. Place project in `htdocs` folder
  3. Start Apache and MySQL services
  4. Access via `http://localhost/itcs333-project`

## Development Guidelines:
- **Database**:
  - Use prepared statements
  - Handle BLOB data for images
  - Maintain referential integrity

- **Security**:
  - Sanitize all user inputs
  - Use password hashing
  - Implement session management
  - Validate file uploads

- **Code Style**:
  - Use meaningful variable names
  - Comment complex logic
  - Follow PSR-12 standards
  - Keep functions focused

## Contribution Guidelines:
- **Use branches for each feature**:  
  ```bash
  git checkout -b feature-name
  ```

- **Commit your changes**:  
  ```bash
  git add .
  git commit -m "Description of changes"
  ```

- **Push your branch**:  
  ```bash
  git push origin feature-name
  ```

- **Create a Pull Request**:
  1. Push to GitHub
  2. Create PR with description
  3. Wait for review
  4. Address feedback

## Testing:
- Test all CRUD operations
- Verify image uploads
- Check booking conflicts
- Validate form submissions
- Test responsive design
