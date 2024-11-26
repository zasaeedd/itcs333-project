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
    Email VARCHAR(100) UNIQUE NOT NULL,
    Password VARCHAR(255) UNIQUE NOT NULL
);

-- Create the Admin table
CREATE TABLE Admin (
    AdminID INT AUTO_INCREMENT PRIMARY KEY,
    Username VARCHAR(50) UNIQUE NOT NULL,
    Name VARCHAR(100) NOT NULL,
    Email VARCHAR(100) UNIQUE NOT NULL,
    Password VARCHAR(255) UNIQUE NOT NULL,
    Action VARCHAR(255),
    Timestamp DATETIME
);

-- Create the Rooms table
CREATE TABLE Rooms (
    RoomID INT AUTO_INCREMENT PRIMARY KEY,
    RoomNo INT UNIQUE NOT NULL,
    Status VARCHAR(50) NOT NULL,
    Capacity INT NOT NULL,
    RoomType VARCHAR(50) NOT NULL
);

-- Create the Bookings table
CREATE TABLE Bookings (
    BookingID INT AUTO_INCREMENT PRIMARY KEY,
    UserID INT NOT NULL,
    RoomID INT NOT NULL,
    StartTime DATETIME NOT NULL,
    EndTime DATETIME NOT NULL,
    Status VARCHAR(50) NOT NULL,
    FOREIGN KEY (UserID) REFERENCES Users(UserID),
    FOREIGN KEY (RoomID) REFERENCES Rooms(RoomID)
);

-- Create the Settings table
CREATE TABLE Settings (
    ID INT AUTO_INCREMENT PRIMARY KEY,
    Settings_Key VARCHAR(255) NOT NULL,
    Settings_Value VARCHAR(255) NOT NULL,
    Updated_At DATETIME NOT NULL
);

-- Create the Analytics_Cache table
CREATE TABLE Analytics_Cache (
    ID INT AUTO_INCREMENT PRIMARY KEY,
    Metric_Key VARCHAR(255) NOT NULL,
    Metric_Value VARCHAR(255) NOT NULL,
    Last_Updated DATETIME NOT NULL
);
