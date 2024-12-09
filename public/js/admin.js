// js/admin.js

document.addEventListener('DOMContentLoaded', function () {
  const body = document.body;
  const darkModeToggle = document.getElementById('darkModeToggle');
  const darkModeIcon = document.getElementById('darkModeIcon');
  const overlay = document.getElementById('overlay');
  const sidebarToggle = document.querySelector('[data-bs-toggle="collapse"]');
  const sidebar = document.getElementById('sidebarMenu');

  // Dark Mode Toggle
  if (darkModeToggle) {
      darkModeToggle.addEventListener('click', function () {
          const isDarkMode = body.classList.toggle('dark-mode');
          if (isDarkMode) {
              if (darkModeIcon) {
                  darkModeIcon.classList.replace('fa-moon', 'fa-sun');
              }
              document.cookie = "darkMode=enabled; path=/";
          } else {
              if (darkModeIcon) {
                  darkModeIcon.classList.replace('fa-sun', 'fa-moon');
              }
              document.cookie = "darkMode=disabled; path=/";
          }
      });

      // Initialize Dark Mode State from Cookie
      if (document.cookie.includes('darkMode=enabled')) {
          body.classList.add('dark-mode');
          if (darkModeIcon) {
              darkModeIcon.classList.replace('fa-moon', 'fa-sun');
          }
      }
  }

  // Sidebar Toggle for Mobile
  if (sidebarToggle && sidebar) {
      sidebarToggle.addEventListener('click', function () {
          sidebar.classList.toggle('show');
          overlay.classList.toggle('show');
      });
  }

  // Overlay Click to Close Sidebar
  if (overlay) {
      overlay.addEventListener('click', function () {
          sidebar.classList.remove('show');
          overlay.classList.remove('show');
      });
  }

  // Handle Overlay with Bootstrap Modals
  const modals = document.querySelectorAll('.modal');
  modals.forEach(function(modal) {
      modal.addEventListener('show.bs.modal', function () {
          // Hide the custom overlay if it's visible
          if (overlay && overlay.classList.contains('show')) {
              overlay.classList.remove('show');
              sidebar.classList.remove('show');
          }
      });

      modal.addEventListener('hidden.bs.modal', function () {
          // Remove any lingering modal backdrops manually (if necessary)
          const modalBackdrop = document.querySelector('.modal-backdrop');
          if (modalBackdrop) {
              modalBackdrop.parentNode.removeChild(modalBackdrop);
          }
      });
  });
});

// User Management Functions
function openAddUserModal() {
  const userForm = document.getElementById('userForm');
  const userModalLabel = document.getElementById('userModalLabel');

  // Reset the form and update modal title
  userForm.reset();
  userModalLabel.innerText = "Add New User";

  // Set hidden inputs
  document.getElementById('action').value = "add";
  document.getElementById('user_id').value = "";
  document.getElementById('password').required = true;
  document.getElementById('passwordLabel').innerText = "Password";
  document.getElementById('password').placeholder = "Enter password";

  // Open the modal
  const userModalElement = document.getElementById('userModal');
  const userModal = new bootstrap.Modal(userModalElement);
  userModal.show();
}

function editUser(button) {
  const userId = button.getAttribute("data-user-id");
  const firstName = button.getAttribute("data-first-name");
  const lastName = button.getAttribute("data-last-name");
  const username = button.getAttribute("data-username");
  const email = button.getAttribute("data-email");
  const role = button.getAttribute("data-role");

  document.getElementById("user_id").value = userId;
  document.getElementById("first_name").value = firstName;
  document.getElementById("last_name").value = lastName;
  document.getElementById("username").value = username;
  document.getElementById("email").value = email;
  document.getElementById("role").value = role;
  document.getElementById("userModalLabel").innerText = "Edit User";
  document.getElementById("action").value = "edit";
  document.getElementById("password").required = false;
  document.getElementById("passwordLabel").innerHTML =
      'New Password <small class="text-muted">(Leave blank to keep current password)</small>';
  document.getElementById("password").placeholder = "Enter new password if changing";
  document.getElementById("password").value = "";

  const userModalElement = document.getElementById('userModal');
  const userModal = new bootstrap.Modal(userModalElement);
  userModal.show();
}

function deleteUser(userId) {
  if (confirm("Are you sure you want to delete this user?")) {
      const form = document.createElement("form");
      form.method = "POST";
      form.action = "manage_users.php";

      const inputAction = document.createElement("input");
      inputAction.type = "hidden";
      inputAction.name = "action";
      inputAction.value = "delete";

      const inputUserId = document.createElement("input");
      inputUserId.type = "hidden";
      inputUserId.name = "user_id";
      inputUserId.value = userId;

      form.appendChild(inputAction);
      form.appendChild(inputUserId);
      document.body.appendChild(form);
      form.submit();
  }
}

// Booking Management Functions
function openEditBookingModal(booking) {
  document.getElementById("booking_id").value = booking.BookingID;
  document.getElementById("status").value = booking.Status;
  const bookingModalElement = document.getElementById('bookingModal');
  const bookingModal = new bootstrap.Modal(bookingModalElement);
  bookingModal.show();
}

function deleteBooking(bookingId) {
  if (confirm("Are you sure you want to delete this booking?")) {
      const form = document.createElement("form");
      form.method = "POST";
      form.action = "manage_bookings.php";

      const inputBookingId = document.createElement("input");
      inputBookingId.type = "hidden";
      inputBookingId.name = "booking_id";
      inputBookingId.value = bookingId;

      const inputAction = document.createElement("input");
      inputAction.type = "hidden";
      inputAction.name = "action";
      inputAction.value = "delete";

      form.appendChild(inputBookingId);
      form.appendChild(inputAction);
      document.body.appendChild(form);
      form.submit();
  }
}

// Room Management Functions
function openAddRoomModal() {
  const roomForm = document.getElementById('roomForm');
  const roomModalLabel = document.getElementById('roomModalLabel');

  // Reset the form and update modal title
  roomForm.reset();
  roomModalLabel.innerText = "Add New Room";

  // Set hidden inputs
  document.getElementById('action').value = "add";
  document.getElementById('room_id').value = "";

  // Open the modal
  const roomModalElement = document.getElementById('roomModal');
  const roomModal = new bootstrap.Modal(roomModalElement);
  roomModal.show();
}

function openEditRoomModal(room) {
  document.getElementById("room_id").value = room.RoomID;
  document.getElementById("room_no").value = room.RoomNo;
  document.getElementById("room_type").value = room.RoomType;
  document.getElementById("capacity").value = room.Capacity;
  document.getElementById("status").value = room.Status;
  document.getElementById("department").value = room.Department;
  document.getElementById("action").value = "edit";
  document.getElementById("roomModalLabel").innerText = "Edit Room";

  const roomModalElement = document.getElementById('roomModal');
  const roomModal = new bootstrap.Modal(roomModalElement);
  roomModal.show();
}

// Reset Modal States When Hidden
document.getElementById('userModal').addEventListener('hidden.bs.modal', function () {
  // Reset form fields
  document.getElementById('userForm').reset();

  // Reset modal title and hidden fields
  document.getElementById("userModalLabel").innerText = "Add New User";
  document.getElementById('action').value = "add";
  document.getElementById('user_id').value = "";
  document.getElementById("passwordLabel").innerText = "Password";
  document.getElementById("password").placeholder = "Enter password";
  document.getElementById("password").required = true;

  // Remove any lingering modal backdrops
  const modalBackdrop = document.querySelector('.modal-backdrop');
  if (modalBackdrop) {
      modalBackdrop.parentNode.removeChild(modalBackdrop);
  }
});

document.getElementById('roomModal').addEventListener('hidden.bs.modal', function () {
  // Reset form fields
  document.getElementById('roomForm').reset();

  // Reset modal title and hidden fields
  document.getElementById("roomModalLabel").innerText = "Add New Room";
  document.getElementById('action').value = "add";
  document.getElementById('room_id').value = "";

  // Remove any lingering modal backdrops
  const modalBackdrop = document.querySelector('.modal-backdrop');
  if (modalBackdrop) {
      modalBackdrop.parentNode.removeChild(modalBackdrop);
  }
});

document.getElementById('bookingModal').addEventListener('hidden.bs.modal', function () {
  // Reset form fields
  document.getElementById('bookingForm').reset();

  // Reset modal title and hidden fields
  document.getElementById("bookingModalLabel").innerText = "Edit Booking";
  document.getElementById('action').value = "edit";
  document.getElementById('booking_id').value = "";

  // Remove any lingering modal backdrops
  const modalBackdrop = document.querySelector('.modal-backdrop');
  if (modalBackdrop) {
      modalBackdrop.parentNode.removeChild(modalBackdrop);
  }
});

document.getElementById('selectAll').addEventListener('change', function() {
  const checkboxes = document.querySelectorAll('input[name="selected_bookings[]"]');
  checkboxes.forEach(cb => cb.checked = this.checked);
});
