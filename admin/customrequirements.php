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
  <meta charset="UTF-8" />
  <title>Manage Custom Requirements</title>
  <style>
    .main {
      display: flex;
      justify-content: center;
      align-items: center;
      margin: 20px 0;
    }

    #heading {
      color: #333;
      margin: 10px;
    }

    table {
      width: 100%;
      margin: 0 auto 30px auto;
      border-collapse: collapse;
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
      vertical-align: middle;
    }

    th {
      background-color: #06BBCC;
      color: white;
      user-select: none;
    }

    tr:hover {
      background-color: #f5f5f5;
    }

    .status-badge {
      padding: 6px 14px;
      border-radius: 12px;
      font-size: 13px;
      font-weight: bold;
      text-transform: capitalize;
      display: inline-block;
      min-width: 90px;
      text-align: center;
      color: white;
    }

    .status-pending {
      background-color: #ffc107;
      color: #000;
    }

    .status-approved {
      background-color: #007bff;
    }

    .status-done {
      background-color: #28a745;
    }

    .download-btn {
      background-color: #28a745;
      color: white;
      border-radius: 4px;
      padding: 6px 14px;
      text-decoration: none;
      font-size: 14px;
      cursor: pointer;
      white-space: nowrap;
      transition: background-color 0.3s ease;
      display: inline-block;
    }

    .download-btn:hover {
      background-color: #1e7e34;
    }

    button.delete-btn,
    button.update-btn {
      background-color: #dc3545;
      color: white;
      border: none;
      border-radius: 4px;
      padding: 6px 14px;
      font-size: 14px;
      cursor: pointer;
      white-space: nowrap;
      transition: background-color 0.3s ease;
      margin-top: 5px;
    }

    button.update-btn {
      background-color: #06BBCC;
    }

    button.update-btn:hover {
      background-color: #049eab;
    }

    button.delete-btn:hover {
      background-color: #b02a37;
    }

    select.status-select {
      padding: 4px 10px;
      border-radius: 4px;
      font-size: 14px;
      border: 1px solid #ccc;
      margin-top: 5px;
    }

    #alert-container {
      width: 90%;
      max-width: 1200px;
      margin: 10px auto 20px auto;
      text-align: center;
      position: relative;
      z-index: 9999;
    }

    .alert {
      padding: 12px;
      border-radius: 6px;
      font-weight: bold;
      font-size: 15px;
      margin-bottom: 10px;
      animation: fadeIn 0.3s ease-in-out;
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

    .load-custom {
      margin: 10px 10px;
    }

    @keyframes fadeIn {
      from {
        opacity: 0;
        transform: translateY(-10px);
      }

      to {
        opacity: 1;
        transform: translateY(0);
      }
    }
  </style>
</head>

<body>

  <?php include "./adminnavbar.php"; ?>

  <div class="main">
    <h2 id="heading">Custom Requirements</h2>
  </div>

  <!-- ALERT POPUP CONTAINER -->
  <div id="alert-container"></div>

  <section class="load-custom">
    <table id="custom-req-table" aria-label="Custom Requirements Table">
      <thead>
        <tr>
          <th>ID</th>
          <th>User ID</th>
          <th>Title</th>
          <th>Description</th>
          <th>Technologies</th>
          <th>Status</th>
          <th>Change Status</th>
          <th>Update</th>
          <th>Download</th>
          <th>Delete</th>
        </tr>
      </thead>
      <tbody></tbody>
    </table>
  </section>

  <?php include "./footer.php"; ?>

  <script>
    document.addEventListener("DOMContentLoaded", function() {
      fetchRequirements();

      function fetchRequirements() {
        fetch('../controller/CustomRequirementsController.php?action=read')
          .then(res => res.json())
          .then(data => {
            const tbody = document.querySelector("#custom-req-table tbody");
            tbody.innerHTML = "";

            data.forEach(item => {
              let statusClass = "";
              if (item.status === 'pending') statusClass = "status-pending";
              else if (item.status === 'approved') statusClass = "status-approved";
              else if (item.status === 'done') statusClass = "status-done";

              const documentInfo = item.document_path ?
                `<a href="../${item.document_path}" class="download-btn" target="_blank" download>Download</a>` :
                `<span style="color:#888;">No document</span>`;

              const tr = document.createElement("tr");
              tr.innerHTML = `
                <td>${item.id}</td>
                <td>${item.user_id}</td>
                <td>${escapeHtml(item.title || '')}</td>
                <td>${escapeHtml(item.description || '')}</td>
                <td>${escapeHtml(item.technologies || '')}</td>
                <td>
                  <span id="status-badge-${item.id}" class="status-badge ${statusClass}">${item.status}</span>
                </td>
                <td>
                  <select id="status-${item.id}" class="status-select">
                    <option value="pending" ${item.status === 'pending' ? 'selected' : ''}>Pending</option>
                    <option value="approved" ${item.status === 'approved' ? 'selected' : ''}>Approved</option>
                    <option value="done" ${item.status === 'done' ? 'selected' : ''}>Done</option>
                  </select>
                </td>
                <td>
                  <button class="update-btn" onclick="updateStatus(${item.id})">Update</button>
                </td>
                <td>${documentInfo}</td>
                <td>
                  <button class="delete-btn" onclick="deleteReq(${item.id})">Delete</button>
                </td>
              `;
              tbody.appendChild(tr);
            });
          })
          .catch(err => {
            console.error("Fetch error:", err);
            showAlert("error", "Failed to load requirements.");
          });
      }

      // ✅ Update Status Function with Custom Message
      window.updateStatus = function(id) {
        const status = document.getElementById(`status-${id}`).value;

        const formData = new FormData();
        formData.append("action", "update_status");
        formData.append("id", id);
        formData.append("status", status);

        fetch('../controller/CustomRequirementsController.php', {
            method: "POST",
            body: formData
          })
          .then(res => res.json())
          .then(resp => {
            if (!resp) {
              showAlert("error", "Empty response from server.");
              return;
            }

            if (resp.status === "success") {
              const badge = document.querySelector(`#status-badge-${id}`);
              badge.textContent = status;
              badge.className = `status-badge`;
              if (status === 'pending') badge.classList.add("status-pending");
              else if (status === 'approved') badge.classList.add("status-approved");
              else if (status === 'done') badge.classList.add("status-done");

              // ✅ Custom alert based on status
              const statusMsg = status.charAt(0).toUpperCase() + status.slice(1);
              showAlert("success", `Status updated to ${statusMsg}`);
            } else {
              showAlert("error", resp.message);
            }
          })
          .catch(err => {
            console.error("Update error:", err);
            showAlert("error", "Failed to update status.");
          });
      }

      window.deleteReq = function(id) {
        if (!confirm("Are you sure you want to delete this requirement?")) return;

        fetch(`../controller/CustomRequirementsController.php?action=delete&id=${id}`, {
            method: "GET"
          })
          .then(res => res.json())
          .then(resp => {
            showAlert(resp.status, resp.message);
            if (resp.status === "success") {
              fetchRequirements();
            }
          })
          .catch(err => {
            console.error("Delete error:", err);
            showAlert("error", "Failed to delete requirement.");
          });
      }

      function showAlert(type, message) {
        const container = document.getElementById("alert-container");
        const alert = document.createElement("div");
        alert.className = `alert alert-${type}`;
        alert.textContent = message;
        container.innerHTML = "";
        container.appendChild(alert);

        setTimeout(() => {
          alert.remove();
        }, 4000);
      }

      function escapeHtml(text) {
        return text
          .replace(/&/g, "&amp;")
          .replace(/</g, "&lt;")
          .replace(/>/g, "&gt;")
          .replace(/"/g, "&quot;")
          .replace(/'/g, "&#039;");
      }
    });
  </script>

</body>

</html>