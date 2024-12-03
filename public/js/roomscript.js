document.addEventListener("DOMContentLoaded", function () {
    const dayButtons = document.querySelectorAll(".day-btn");
    const timeslotTable = document.getElementById("timeslot-table");
    const timeslotTbody = document.getElementById("timeslot-tbody");
    const selectedDayHeading = document.getElementById("selected-day-heading");
    const selectedDaySpan = document.getElementById("selected-day");

    // At the top of your file, add this function to get URL parameters
    function getUrlParameter(name) {
        const urlParams = new URLSearchParams(window.location.search);
        return urlParams.get(name);
    }

    // Get the room ID from the URL
    const roomID = getUrlParameter('roomNo');
    console.log('Room ID from URL:', roomID); // Debug log

    // Function to format date as DD/MM/YYYY
    function formatDate(date) {
        return `${String(date.getDate()).padStart(2, '0')}/${String(date.getMonth() + 1).padStart(2, '0')}/${date.getFullYear()}`;
    }

    // Function to get dates for the current week
    function getCurrentWeekDates() {
        const today = new Date();
        const currentDay = today.getDay(); // 0 = Sunday, 1 = Monday, etc.
        const diff = currentDay - 1; // Adjust to start from Monday
        
        const monday = new Date(today);
        monday.setDate(today.getDate() - diff);
        
        const weekDates = [];
        for(let i = 0; i < 7; i++) {
            const date = new Date(monday);
            date.setDate(monday.getDate() + i);
            weekDates.push({
                date: date,
                formatted: formatDate(date),
                dayName: date.toLocaleDateString('en-US', { weekday: 'long' })
            });
        }
        return weekDates;
    }

    // Create date buttons
    const weekDates = getCurrentWeekDates();
    const daySelector = document.querySelector('.day-selector');
    daySelector.innerHTML = ''; // Clear existing buttons

    weekDates.forEach(dateInfo => {
        const button = document.createElement('button');
        button.className = 'btn btn-outline-primary day-btn';
        button.setAttribute('data-day', dateInfo.dayName);
        button.setAttribute('data-date', dateInfo.formatted);
        button.textContent = `${dateInfo.dayName} (${dateInfo.formatted})`;
        daySelector.appendChild(button);

        // Add click event listener to each button
        button.addEventListener("click", function() {
            const selectedDay = this.getAttribute("data-day");
            const selectedDate = this.getAttribute("data-date");
            selectedDaySpan.textContent = `${selectedDay} (${selectedDate})`;

            // Clear previous timeslots
            timeslotTbody.innerHTML = "";

            // Filter timeslots by selected day
            const filteredTimeslots = roomTimeslots.filter(slot => slot.DayOfWeek === selectedDay);

            if (filteredTimeslots.length > 0) {
                filteredTimeslots.forEach(slot => {
                    const row = document.createElement("tr");
                    
                    row.innerHTML = `
                        <td>${selectedDate}</td>
                        <td>${slot.StartTime}</td>
                        <td>${slot.EndTime}</td>
                        <td><span class="badge bg-success">Available</span></td>
                        <td>
                            <button class="btn btn-sm btn-primary book-btn" 
                                data-room-id="${roomID}"
                                data-date="${selectedDate}"
                                data-start-time="${slot.StartTime}"
                                data-end-time="${slot.EndTime}"
                                data-timeslot-id="null">Book</button>
                        </td>
                    `;
                    timeslotTbody.appendChild(row);

                    // Check for existing bookings using RoomNo
                    fetch(`check_booking.php?roomId=${roomID}&date=${selectedDate}&startTime=${slot.StartTime}&endTime=${slot.EndTime}`)
                        .then(response => response.json())
                        .then(data => {
                            if (data.isBooked) {
                                const badgeCell = row.querySelector('td:nth-child(4)');
                                const buttonCell = row.querySelector('td:nth-child(5)');
                                
                                badgeCell.innerHTML = '<span class="badge bg-danger">Not Available</span>';
                                buttonCell.innerHTML = '<button class="btn btn-sm btn-secondary" disabled>Unavailable</button>';
                            } else {
                                // Add click event listener only if the slot is available
                                const bookBtn = row.querySelector('.book-btn');
                                if (bookBtn) {
                                    bookBtn.addEventListener('click', function(e) {
                                        handleBooking(e.target);
                                    });
                                }
                            }
                        })
                        .catch(error => console.error('Error checking booking:', error));
                });
            } else {
                const noSlotRow = document.createElement("tr");
                noSlotRow.innerHTML = `
                    <td colspan="5" class="text-center text-danger">No timeslots available for this day.</td>
                `;
                timeslotTbody.appendChild(noSlotRow);
            }

            // Show the timeslot table and heading
            selectedDayHeading.classList.remove("d-none");
            timeslotTable.style.display = "table";
        });
    });

    function handleBooking(button) {
        // Format the times to match MySQL datetime format
        const formatDateTime = (dateStr, timeStr) => {
            const [day, month, year] = dateStr.split('/');
            const [hours, minutes] = timeStr.split(':');
            
            // Create date object using the selected date and time
            const date = new Date(year, month - 1, day);
            date.setHours(hours);
            date.setMinutes(minutes);
            
            // Format to YYYY-MM-DD HH:mm:ss
            return `${year}-${month}-${day} ${String(hours).padStart(2, '0')}:${String(minutes).padStart(2, '0')}:00`;
        };

        const bookingData = {
            roomID: parseInt(roomID),
            startTime: formatDateTime(button.dataset.date, button.dataset.startTime),
            endTime: formatDateTime(button.dataset.date, button.dataset.endTime),
            timeSlotID: null
        };

        console.log('Sending booking data:', bookingData);

        fetch('book_room.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify(bookingData)
        })
        .then(response => response.text())
        .then(result => {
            console.log('Server response:', result);
            alert(result);
            if (result.includes('successful')) {
                button.disabled = true;
                button.classList.remove('btn-primary');
                button.classList.add('btn-secondary');
                button.textContent = 'Unavailable';
                
                const row = button.closest('tr');
                const badgeCell = row.querySelector('td:nth-child(4)');
                badgeCell.innerHTML = '<span class="badge bg-danger">Not Available</span>';
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('An error occurred while booking the room. Please try again.');
        });
    }
});