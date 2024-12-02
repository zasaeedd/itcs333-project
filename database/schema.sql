-- Drop existing database and user if they exist
DROP DATABASE IF EXISTS 333project;
DROP USER IF EXISTS 'project_admin'@'localhost';

-- Create the database
CREATE DATABASE 333project;

-- Switch to the new database
USE 333project;

-- Create a new database user
CREATE USER 'project_admin'@'localhost' IDENTIFIED BY '!P@ssw0rd#123Secure';

-- Grant full privileges on the database to the new user
GRANT ALL PRIVILEGES ON 333project.* TO 'project_admin'@'localhost';

-- Apply privilege changes
FLUSH PRIVILEGES;

-- Create the Users table
CREATE TABLE Users (
    UserID INT AUTO_INCREMENT PRIMARY KEY,
    FirstName VARCHAR(50) NOT NULL,
    LastName VARCHAR(50) NOT NULL,
    Username VARCHAR(50) UNIQUE NOT NULL,
    Email VARCHAR(254) UNIQUE NOT NULL,
    Password VARCHAR(255) NOT NULL,  -- Passwords are hashed
    Role ENUM('User', 'Admin') NOT NULL DEFAULT 'User'
);

-- Create the Rooms table
<!DOCTYPE html>
<html lang="en">
<head>
    <title>Room Details</title>
    <style>
        .search-container {
            display: flex;
            justify-content: center;
            margin-top: 50px;
        }
        .search-container input[type="text"] {
            padding: 10px;
            font-size: 16px;
            border: 1px solid #ccc;
            border-radius: 4px 0 0 4px;
            outline: none;
        }
        .search-container button {
            padding: 10px;
            font-size: 16px;
            background-color: #4CAF50;
            color: white;
            border: none;
            border-radius: 0 4px 4px 0;
            cursor: pointer;
        }
        .search-container button:hover {
            background-color: #45a049;
        }
        .department-section {
            margin: 30px;
        }
        .department-section h2 {
            text-align: center;
            color: #333;
        }
        .room-grid {
            display: flex;
            flex-wrap: wrap;
            gap: 20px;
            justify-content: center;
        }
        .room-card {
            width: 200px;
            height: 250px;
            border: 1px solid #ddd;
            border-radius: 8px;
            overflow: hidden;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            text-align: center;
            position: relative;
            transition: transform 0.3s, box-shadow 0.3s;
        }
        .room-card img {
            width: 100%;
            height: 180px;
            object-fit: cover;
        }
        .room-card .room-info {
            padding: 10px;
            background-color: white;
        }
        .room-card .room-info p {
            margin: 5px 0;
            font-size: 14px;
            color: #555;
        }
        .room-card:hover {
            transform: translateY(-10px);
            box-shadow: 0 8px 12px rgba(0, 0, 0, 0.2);
        }
        .room-card:hover .room-overlay {
            opacity: 1;
        }
        .room-card .room-overlay {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.6);
            display: flex;
            justify-content: center;
            align-items: center;
            color: white;
            font-size: 18px;
            font-weight: bold;
            opacity: 0;
            transition: opacity 0.3s;
        }
    </style>
</head>
<body>
    <div class="search-container">
        <form action="index.php" method="GET">
            <input type="text" name="query" placeholder="Enter Room Number..." required>
            <button type="submit">Search</button>
        </form>
    </div>

    <!-- Department: IS -->
    <div class="department-section">
        <h2>IS Department</h2>
        <div class="room-grid">
            <!-- Replace with PHP loop -->
            <div class="room-card">
                <img src="path/to/image1.jpg" alt="Room Image">
                <div class="room-info">
                    <p>Room: IS101</p>
                    <p>Capacity: 50</p>
                </div>
                <div class="room-overlay">IS101</div>
            </div>
            <!-- End PHP loop -->
        </div>
    </div>

    <!-- Department: CS -->
    <div class="department-section">
        <h2>CS Department</h2>
        <div class="room-grid">
            <!-- Replace with PHP loop -->
            <div class="room-card">
                <img src="path/to/image2.jpg" alt="Room Image">
                <div class="room-info">
                    <p>Room: CS201</p>
                    <p>Capacity: 40</p>
                </div>
                <div class="room-overlay">CS201</div>
            </div>
            <!-- End PHP loop -->
        </div>
    </div>

    <!-- Department: CE -->
    <div class="department-section">
        <h2>CE Department</h2>
        <div class="room-grid">
            <!-- Replace with PHP loop -->
            <div class="room-card">
                <img src="path/to/image3.jpg" alt="Room Image">
                <div class="room-info">
                    <p>Room: CE301</p>
                    <p>Capacity: 30</p>
                </div>
                <div class="room-overlay">CE301</div>
            </div>
            <!-- End PHP loop -->
        </div>
    </div>
</body>
</html>

-- TimeSlots table
CREATE TABLE TimeSlots (
    TimeSlotID INT AUTO_INCREMENT PRIMARY KEY,
    RoomID INT NOT NULL,
    StartTime TIME NOT NULL,
    EndTime TIME NOT NULL,
    DayOfWeek ENUM('Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday') NOT NULL,
    IsAvailable BOOLEAN NOT NULL DEFAULT TRUE,
    FOREIGN KEY (RoomID) REFERENCES Rooms(RoomID) ON DELETE CASCADE,
    CHECK (EndTime > StartTime)
);

-- Create the Bookings table
CREATE TABLE Bookings (
    BookingID INT AUTO_INCREMENT PRIMARY KEY,
    BookedBy INT NOT NULL,  -- User or Admin ID
    RoomID INT NOT NULL,
    StartTime DATETIME NOT NULL,
    EndTime DATETIME NOT NULL,
    TimeSlotID INT,
    Status ENUM('Pending', 'Confirmed', 'Cancelled', 'Completed') NOT NULL,
    FOREIGN KEY (BookedBy) REFERENCES Users(UserID) ON DELETE CASCADE,
    FOREIGN KEY (RoomID) REFERENCES Rooms(RoomID) ON DELETE CASCADE,
    FOREIGN KEY (TimeSlotID) REFERENCES TimeSlots(TimeSlotID),
    CHECK (EndTime > StartTime)
);

-- Create the Settings table
CREATE TABLE Settings (
    ID INT AUTO_INCREMENT PRIMARY KEY,
    Settings_Key VARCHAR(255) UNIQUE NOT NULL,
    Settings_Value VARCHAR(255) NOT NULL,
    Updated_At DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- Create the Analytics_Cache table
CREATE TABLE Analytics_Cache (
    ID INT AUTO_INCREMENT PRIMARY KEY,
    Metric_Key VARCHAR(255) UNIQUE NOT NULL,
    Metric_Value VARCHAR(255) NOT NULL,
    Last_Updated DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);


-- Update Tables to add equipments in rooms (copy separately into phpmyadmin)

-- Rooms for the IS Department
UPDATE Rooms SET Equipment = 'Projector, Whiteboard, Chairs, Tables' WHERE Department = 'IS';
-- Rooms for the CS Department
UPDATE Rooms SET Equipment = 'Projector, Whiteboard, Chairs, Tables, Computers, Networked Printers' WHERE Department = 'CS';
-- Rooms for the CE Department
UPDATE Rooms SET Equipment = 'Projector, Whiteboard, Chairs, Tables, Smart Boards, Audio System' WHERE Department = 'CE';