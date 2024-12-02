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
CREATE TABLE Rooms (
    RoomID INT AUTO_INCREMENT PRIMARY KEY,
    RoomNo INT UNIQUE NOT NULL,
    Status ENUM('Available', 'Occupied', 'Maintenance') NOT NULL,
    Capacity INT NOT NULL CHECK (Capacity > 0),
    RoomType ENUM('Classroom', 'Lab', 'Lecture Hall', 'Auditorium') NOT NULL,
    Department ENUM('IS', 'CS', 'CE') NOT NULL,
    Equipment VARCHAR(255)  -- Additional column for room equipment
);

-- Create the TimeSlots table
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

-- Update Rooms with department-specific equipment
UPDATE Rooms SET Equipment = 'Projector, Whiteboard, Chairs, Tables' WHERE Department = 'IS';
UPDATE Rooms SET Equipment = 'Projector, Whiteboard, Chairs, Tables, Computers, Networked Printers' WHERE Department = 'CS';
UPDATE Rooms SET Equipment = 'Projector, Whiteboard, Chairs, Tables, Smart Boards, Audio System' WHERE Department = 'CE';
