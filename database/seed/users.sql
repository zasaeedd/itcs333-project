-- First, alter the Users table to add the ImagePath column
ALTER TABLE Users ADD COLUMN ImagePath VARCHAR(255) DEFAULT 'images/default-profile.jpg';

-- Switch to the new database
USE 333project;

-- Insert admin users
-- Password for All: Secure@333 (hashed)
INSERT INTO Users (FirstName, LastName, Username, Email, Password, Role, ImagePath)
VALUES
('CS', 'Admin', 'cs_admin', 'cs@uob.edu.bh', '$2y$10$x8mwRvn7w.r/B6gBlMSG.O4yDjPB2fxJPuZtQnB7wV4D6THQ90kSy', 'Admin', 'images/admin-profile.jpg'),
('IS', 'Admin', 'is_admin', 'is@uob.edu.bh', '$2y$10$x8mwRvn7w.r/B6gBlMSG.O4yDjPB2fxJPuZtQnB7wV4D6THQ90kSy', 'Admin', 'images/admin-profile.jpg'),
('CE', 'Admin', 'ce_admin', 'ce@uob.edu.bh', '$2y$10$x8mwRvn7w.r/B6gBlMSG.O4yDjPB2fxJPuZtQnB7wV4D6THQ90kSy', 'Admin', 'images/admin-profile.jpg'),
('System', 'Admin', 'system_admin', 'system@uob.edu.bh', '$2y$10$x8mwRvn7w.r/B6gBlMSG.O4yDjPB2fxJPuZtQnB7wV4D6THQ90kSy', 'Admin', 'images/admin-profile.jpg');

-- Insert student users
-- Password for All: Secure@333 (hashed)
INSERT INTO Users (FirstName, LastName, Username, Email, Password, Role, ImagePath) 
VALUES
('ZAINAB', 'SAEED', 'zasaeedd', '202202095@stu.uob.edu.bh', '$2y$10$x8mwRvn7w.r/B6gBlMSG.O4yDjPB2fxJPuZtQnB7wV4D6THQ90kSy', 'User', 'images/default-profile.jpg'),
('HASAN', 'JASIM', 'hassanjh', '202110375@stu.uob.edu.bh', '$2y$10$x8mwRvn7w.r/B6gBlMSG.O4yDjPB2fxJPuZtQnB7wV4D6THQ90kSy', 'User', 'images/default-profile.jpg'),
('HUSAIN', 'ALI', '7ax', '202201254@stu.uob.edu.bh', '$2y$10$x8mwRvn7w.r/B6gBlMSG.O4yDjPB2fxJPuZtQnB7wV4D6THQ90kSy', 'User', 'images/default-profile.jpg'),
('SAYED ALI', 'HUSAIN', 'S3lialaali', '202107650@stu.uob.edu.bh', '$2y$10$x8mwRvn7w.r/B6gBlMSG.O4yDjPB2fxJPuZtQnB7wV4D6THQ90kSy', 'User', 'images/default-profile.jpg'),
('ALI', 'MOHSEN', 'Kwaddo', '202100341@stu.uob.edu.bh', '$2y$10$x8mwRvn7w.r/B6gBlMSG.O4yDjPB2fxJPuZtQnB7wV4D6THQ90kSy', 'User', 'images/default-profile.jpg');
