document.addEventListener("DOMContentLoaded", function () {
    const dayButtons = document.querySelectorAll(".day-btn");
    const timeslotTable = document.getElementById("timeslot-table");
    const timeslotTbody = document.getElementById("timeslot-tbody");
    const selectedDayHeading = document.getElementById("selected-day-heading");
    const selectedDaySpan = document.getElementById("selected-day");

    dayButtons.forEach(button => {
        button.addEventListener("click", function () {
            const selectedDay = this.getAttribute("data-day");
            selectedDaySpan.textContent = selectedDay;

            // Clear previous timeslots
            timeslotTbody.innerHTML = "";

            // Filter timeslots by selected day
            const filteredTimeslots = roomTimeslots.filter(slot => slot.DayOfWeek === selectedDay);

            if (filteredTimeslots.length > 0) {
                filteredTimeslots.forEach(slot => {
                    const row = document.createElement("tr");

                    row.innerHTML = `
                        <td>${slot.DayOfWeek}</td>
                        <td>${slot.StartTime}</td>
                        <td>${slot.EndTime}</td>
                        <td>${slot.IsAvailable ? '<span class="badge bg-success">Available</span>' : '<span class="badge bg-danger">Not Available</span>'}</td>
                        <td>
                            ${slot.IsAvailable 
                                ? '<button class="btn btn-sm btn-primary">Book</button>' 
                                : '<button class="btn btn-sm btn-secondary" disabled>Unavailable</button>'}
                        </td>
                    `;
                    timeslotTbody.appendChild(row);
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
});
