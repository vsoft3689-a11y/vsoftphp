<?php
session_start();
if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'admin') {
  header("Location: ../login.php");
  exit();
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <title>User Inquiries</title>
  <style>
    .main {
      display: flex;
      justify-content: center;
      align-items: center;
      margin-top: 30px;
    }

    #inquiryHeading {
      color: #333;
    }

    table {
      width: 100%;
      border-collapse: collapse;
      margin: 30px auto;
      background-color: #fff;
      border-radius: 8px;
      overflow: hidden;
      box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
    }

    th,
    td {
      padding: 12px;
      border-bottom: 1px solid #eee;
      text-align: left;
    }

    th {
      background-color: #06BBCC;
      color: white;
    }

    tr:hover {
      background-color: #f5f5f5;
    }

    .status-badge {
      padding: 6px 10px;
      border-radius: 12px;
      font-size: 13px;
      font-weight: bold;
      text-transform: capitalize;
    }

    .badge-pending {
      background-color: #ffc107;
      color: #000;
    }

    .badge-resolved {
      background-color: #28a745;
      color: white;
    }

    .status-select {
      padding: 6px;
      font-size: 14px;
      border-radius: 4px;
      margin-bottom: 6px;
    }

    .status-btn,
    .delete-btn {
      padding: 6px 10px;
      background-color: #06BBCC;
      color: white;
      border: none;
      border-radius: 4px;
      font-size: 13px;
      cursor: pointer;
      transition: background-color 0.3s;
    }

    .status-btn:hover,
    .delete-btn:hover {
      background-color: #049eab;
    }

    .alert {
      width: 80%;
      max-width: 900px;
      margin: 10px auto;
      padding: 12px;
      border-radius: 6px;
      text-align: center;
      font-weight: bold;
    }

    .alert-success {
      background-color: #d4edda;
      color: #155724;
      border: 1px solid #c3e6cb;
    }

    .alert-error {
      background-color: #f8d7da;
      color: #721c24;
      border: 1px solid #f5c6cb;
    }

    .load-inquiry {
      margin: 10px 10px;
    }
  </style>
</head>

<body>

  <?php include "./adminnavbar.php"; ?>

  <div class="main">
    <h2 id="inquiryHeading">All User Inquiries</h2>
  </div>

  <div id="alert-container"></div>

  <section class="load-inquiry">
    <table id="inquiries-table">
      <thead>
        <tr>
          <th>Name</th>
          <th>Email</th>
          <th>Phone</th>
          <th>Subject</th>
          <th>Type</th>
          <th>Message</th>
          <th>Status</th>
          <th>Update</th>
          <th>Delete</th>
        </tr>
      </thead>
      <tbody></tbody>
    </table>
  </section>

  <script>
    document.addEventListener("DOMContentLoaded", function() {
      const API_URL = '../controller/InquiryController.php';

      function showAlert(type, message) {
        const alertContainer = document.getElementById("alert-container");
        alertContainer.innerHTML = `
        <div class="alert alert-${type}">
          ${message}
        </div>
      `;
        setTimeout(() => {
          alertContainer.innerHTML = '';
        }, 3000);
      }

      function fetchInquiries() {
        fetch(`${API_URL}?action=read`)
          .then(res => res.json())
          .then(data => {
            const tbody = document.querySelector("#inquiries-table tbody");
            tbody.innerHTML = "";

            data.forEach(inq => {
              const tr = document.createElement("tr");
              tr.innerHTML = `
              <td>${inq.name}</td>
              <td>${inq.email}</td>
              <td>${inq.phone || '-'}</td>
              <td>${inq.subject}</td>
              <td>${inq.type}</td>
              <td>${inq.message}</td>
              <td>
                <span class="status-badge ${inq.status === 'resolved' ? 'badge-resolved' : 'badge-pending'}">
                  ${inq.status}
                </span>
              </td>
              <td>
                <form onsubmit="return updateStatus(event, ${inq.id})">
                  <select name="status" class="status-select">
                    <option value="pending" ${inq.status === 'pending' ? 'selected' : ''}>Pending</option>
                    <option value="resolved" ${inq.status === 'resolved' ? 'selected' : ''}>Resolved</option>
                  </select>
                  <button type="submit" class="status-btn">Update</button>
                </form>
              </td>
              <td>
                <form onsubmit="return deleteInquiry(event, ${inq.id})">
                  <button type="submit" class="delete-btn">Delete</button>
                </form>
              </td>
            `;
              tbody.appendChild(tr);
            });
          })
          .catch(err => {
            console.error(err);
            showAlert("error", "Failed to fetch inquiries.");
          });
      }

      window.updateStatus = function(event, id) {
        event.preventDefault();
        const form = event.target;
        const formData = new FormData(form);
        const selectedStatus = formData.get("status");

        formData.append("id", id);
        formData.append("action", "updateStatus");

        fetch(API_URL, {
            method: "POST",
            body: formData
          })
          .then(res => res.json())
          .then(response => {
            const msg = selectedStatus === 'resolved' ?
              "✅ Status updated to Resolved" :
              "⏳ Status updated to Pending";
            showAlert("success", msg);
            fetchInquiries();
          })
          .catch(err => {
            console.error(err);
            showAlert("error", "Update failed.");
          });
      };

      window.deleteInquiry = function(event, id) {
        event.preventDefault();
        if (!confirm("Are you sure you want to delete this inquiry?")) return;

        const formData = new FormData();
        formData.append("id", id);
        formData.append("action", "delete");

        fetch(API_URL, {
            method: "POST",
            body: formData
          })
          .then(res => res.json())
          .then(response => {
            showAlert("success", "🗑️ Inquiry deleted successfully.");
            fetchInquiries();
          })
          .catch(err => {
            console.error(err);
            showAlert("error", "Delete failed.");
          });
      };

      fetchInquiries(); // Load data initially
    });
  </script>

  <?php include "./footer.php"; ?>
</body>

</html>