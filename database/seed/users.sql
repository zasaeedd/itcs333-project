-- Switch to the new database
USE 333project;

-- Insert admin users
-- Password for All: Secure@333 (hashed)
INSERT INTO Users (FirstName, LastName, Username, Email, Password, Role)
VALUES
('CS', 'Admin', 'cs_admin', 'cs@uob.edu.bh', '$2y$10$x8mwRvn7w.r/B6gBlMSG.O4yDjPB2fxJPuZtQnB7wV4D6THQ90kSy', 'Admin'),
('IS', 'Admin', 'is_admin', 'is@uob.edu.bh', '$2y$10$x8mwRvn7w.r/B6gBlMSG.O4yDjPB2fxJPuZtQnB7wV4D6THQ90kSy', 'Admin'),
('CE', 'Admin', 'ce_admin', 'ce@uob.edu.bh', '$2y$10$x8mwRvn7w.r/B6gBlMSG.O4yDjPB2fxJPuZtQnB7wV4D6THQ90kSy', 'Admin'),
('System', 'Admin', 'system_admin', 'system@uob.edu.bh', '$2y$10$x8mwRvn7w.r/B6gBlMSG.O4yDjPB2fxJPuZtQnB7wV4D6THQ90kSy', 'Admin');

-- Insert student users
-- Password for All: Secure@333 (hashed)
INSERT INTO Users (FirstName, LastName, Username, Email, Password, Role) 
VALUES
('ZAINAB', 'SAEED', 'zasaeedd', '202202095@stu.uob.edu.bh', '$2y$10$x8mwRvn7w.r/B6gBlMSG.O4yDjPB2fxJPuZtQnB7wV4D6THQ90kSy', 'User'),
('HASAN', 'JASIM', 'hassanjh', '202110375@stu.uob.edu.bh', '$2y$10$x8mwRvn7w.r/B6gBlMSG.O4yDjPB2fxJPuZtQnB7wV4D6THQ90kSy', 'User'),
('HUSAIN', 'ALI', '7ax', '202201254@stu.uob.edu.bh', '$2y$10$x8mwRvn7w.r/B6gBlMSG.O4yDjPB2fxJPuZtQnB7wV4D6THQ90kSy', 'User'),
('SAYED ALI', 'HUSAIN', 'S3lialaali', '202107650@stu.uob.edu.bh', '$2y$10$x8mwRvn7w.r/B6gBlMSG.O4yDjPB2fxJPuZtQnB7wV4D6THQ90kSy', 'User'),
('ALI', 'MOHSEN', 'Kwaddo', '202100341@stu.uob.edu.bh', '$2y$10$x8mwRvn7w.r/B6gBlMSG.O4yDjPB2fxJPuZtQnB7wV4D6THQ90kSy', 'User');
