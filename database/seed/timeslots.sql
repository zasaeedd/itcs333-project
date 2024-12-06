USE 333project;

-- Insert timeslots for all rooms and days of the week dynamically
INSERT INTO TimeSlots (RoomID, DayOfWeek, StartTime, EndTime, IsAvailable)
SELECT 
    RoomID, 
    TimeSlots.DayOfWeek,
    TimeSlots.StartTime, 
    TimeSlots.EndTime,
    TRUE as IsAvailable
FROM 
    Rooms
CROSS JOIN (
    -- Define time slots with 1-hour intervals
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
    SELECT 'Friday', '17:00:00', '18:00:00'
) AS TimeSlots;