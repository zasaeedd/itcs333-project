USE 333project;
-- Insert timeslots for all rooms and days of the week dynamically
INSERT INTO Timeslots (RoomID, DayOfWeek, StartTime, EndTime)
SELECT 
    RoomID, 
    TimeSlots.DayOfWeek,
    TimeSlots.StartTime, 
    TimeSlots.EndTime
FROM 
    Rooms
CROSS JOIN (
    -- Define time slots
    SELECT 'Monday' AS DayOfWeek, '08:00:00' AS StartTime, '09:00:00' AS EndTime UNION ALL
    SELECT 'Monday', '09:00:00', '10:00:00' UNION ALL
    SELECT 'Monday', '10:00:00', '11:00:00' UNION ALL
    SELECT 'Monday', '11:00:00', '12:00:00' UNION ALL
    SELECT 'Monday', '12:00:00', '13:00:00' UNION ALL
    SELECT 'Monday', '13:00:00', '14:00:00' UNION ALL
    SELECT 'Monday', '14:00:00', '15:00:00' UNION ALL
    SELECT 'Monday', '15:00:00', '16:00:00' UNION ALL
    SELECT 'Monday', '16:00:00', '17:00:00' UNION ALL
    SELECT 'Monday', '17:00:00', '18:00:00' UNION ALL
    SELECT 'Tuesday', '08:00:00', '09:00:00' UNION ALL
    SELECT 'Tuesday', '09:00:00', '10:00:00' UNION ALL
    SELECT 'Tuesday', '10:00:00', '11:00:00' UNION ALL
    SELECT 'Tuesday', '11:00:00', '12:00:00' UNION ALL
    SELECT 'Tuesday', '12:00:00', '13:00:00' UNION ALL
    SELECT 'Tuesday', '13:00:00', '14:00:00' UNION ALL
    SELECT 'Tuesday', '14:00:00', '15:00:00' UNION ALL
    SELECT 'Tuesday', '15:00:00', '16:00:00' UNION ALL
    SELECT 'Tuesday', '16:00:00', '17:00:00' UNION ALL
    SELECT 'Tuesday', '17:00:00', '18:00:00' UNION ALL
    -- Repeat for Wednesday to Sunday
    SELECT 'Wednesday', '08:00:00', '09:00:00' UNION ALL
    SELECT 'Wednesday', '09:00:00', '10:00:00' UNION ALL
    SELECT 'Wednesday', '10:00:00', '11:00:00' UNION ALL
    SELECT 'Wednesday', '11:00:00', '12:00:00' UNION ALL
    SELECT 'Wednesday', '12:00:00', '13:00:00' UNION ALL
    SELECT 'Wednesday', '13:00:00', '14:00:00' UNION ALL
    SELECT 'Wednesday', '14:00:00', '15:00:00' UNION ALL
    SELECT 'Wednesday', '15:00:00', '16:00:00' UNION ALL
    SELECT 'Wednesday', '16:00:00', '17:00:00' UNION ALL
    SELECT 'Wednesday', '17:00:00', '18:00:00' UNION ALL
    -- Continue with Thursday, Friday, Saturday, Sunday
    SELECT 'Thursday', '08:00:00', '09:00:00' UNION ALL
    SELECT 'Thursday', '09:00:00', '10:00:00' UNION ALL
    SELECT 'Thursday', '10:00:00', '11:00:00' UNION ALL
    SELECT 'Thursday', '11:00:00', '12:00:00' UNION ALL
    SELECT 'Thursday', '12:00:00', '13:00:00' UNION ALL
    SELECT 'Thursday', '13:00:00', '14:00:00' UNION ALL
    SELECT 'Thursday', '14:00:00', '15:00:00' UNION ALL
    SELECT 'Thursday', '15:00:00', '16:00:00' UNION ALL
    SELECT 'Thursday', '16:00:00', '17:00:00' UNION ALL
    SELECT 'Thursday', '17:00:00', '18:00:00' UNION ALL
    SELECT 'Friday', '08:00:00', '09:00:00' UNION ALL
    SELECT 'Friday', '09:00:00', '10:00:00' UNION ALL
    SELECT 'Friday', '10:00:00', '11:00:00' UNION ALL
    SELECT 'Friday', '11:00:00', '12:00:00' UNION ALL
    SELECT 'Friday', '12:00:00', '13:00:00' UNION ALL
    SELECT 'Friday', '13:00:00', '14:00:00' UNION ALL
    SELECT 'Friday', '14:00:00', '15:00:00' UNION ALL
    SELECT 'Friday', '15:00:00', '16:00:00' UNION ALL
    SELECT 'Friday', '16:00:00', '17:00:00' UNION ALL
    SELECT 'Friday', '17:00:00', '18:00:00' UNION ALL
    SELECT 'Saturday', '08:00:00', '09:00:00' UNION ALL
    SELECT 'Saturday', '09:00:00', '10:00:00' UNION ALL
    SELECT 'Saturday', '10:00:00', '11:00:00' UNION ALL
    SELECT 'Saturday', '11:00:00', '12:00:00' UNION ALL
    SELECT 'Saturday', '12:00:00', '13:00:00' UNION ALL
    SELECT 'Saturday', '13:00:00', '14:00:00' UNION ALL
    SELECT 'Saturday', '14:00:00', '15:00:00' UNION ALL
    SELECT 'Saturday', '15:00:00', '16:00:00' UNION ALL
    SELECT 'Saturday', '16:00:00', '17:00:00' UNION ALL
    SELECT 'Saturday', '17:00:00', '18:00:00' UNION ALL
    SELECT 'Sunday', '08:00:00', '09:00:00' UNION ALL
    SELECT 'Sunday', '09:00:00', '10:00:00' UNION ALL
    SELECT 'Sunday', '10:00:00', '11:00:00' UNION ALL
    SELECT 'Sunday', '11:00:00', '12:00:00' UNION ALL
    SELECT 'Sunday', '12:00:00', '13:00:00' UNION ALL
    SELECT 'Sunday', '13:00:00', '14:00:00' UNION ALL
    SELECT 'Sunday', '14:00:00', '15:00:00' UNION ALL
    SELECT 'Sunday', '15:00:00', '16:00:00' UNION ALL
    SELECT 'Sunday', '16:00:00', '17:00:00' UNION ALL
    SELECT 'Sunday', '17:00:00', '18:00:00'AS TimeSlots;