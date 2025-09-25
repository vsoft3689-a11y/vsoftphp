<?php
session_start();
if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'admin') {
    // header("Location: login.php");
    header("Location: ../login.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Site Config Management</title>
    <style>
        .main {
            width: 100%;
            min-height: auto;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            padding: 20px;
        }

        #configHeading {
            color: #444;
            margin: 20px;
        }

        h2 {
            text-align: center;
        }

        #configForm {
            background: #fff;
            padding: 20px;
            margin-bottom: 20px;
            border-radius: 8px;
            box-shadow: 0 2px 6px rgba(0, 0, 0, 0.1);
            max-width: 500px;
        }

        label {
            display: block;
            margin-top: 10px;
        }

        input,
        textarea {
            margin: 8px 0;
            padding: 8px;
            width: 100%;
            border: 1px solid #ccc;
            border-radius: 5px;
        }

        #configForm input {
            margin-bottom: 10px;
        }

        textarea {
            resize: vertical;
            min-height: 80px;
        }

        #btn {
            width: auto;
            background: #06BBCC;
            color: #fff;
            padding: 10px;
            font-weight: bold;
            cursor: pointer;
            border: none;
            border-radius: 5px;
            transition: 0.3s;
        }

        #btn:hover {
            background: #06A0B0;
        }

        #btn[type="submit"] {
            margin-top: 5px;
        }

        .load-siteconfig {
            margin: 10px 10px;
        }

        table {
            width: 100%;
            max-width: 1500px;
            border-collapse: collapse;
            background: #fff;
            margin-top: 20px;
            border-radius: 8px;
            overflow: hidden;
            box-shadow: 0 2px 6px rgba(0, 0, 0, 0.1);
        }

        th,
        td {
            padding: 12px;
            border-bottom: 1px solid #eee;
            text-align: left;
            vertical-align: top;
            word-break: break-word;
        }

        th {
            background: #06BBCC;
            color: #fff;
            text-align: center;
        }

        td {
            width: 20px;
        }

        tr:hover {
            background: #f1f1f1;
        }

        .actions button {
            width: auto;
            margin-right: 6px;
            padding: 6px 12px;
            font-size: 13px;
        }

        iframe {
            border: 0;
            border-radius: 6px;
        }
    </style>
</head>

<body>
    <?php include "./adminnavbar.php" ?>

    <div class="main">
        <h2 id="configHeading">Add Config</h2>
        <form id="configForm">
            <input type="hidden" name="action" value="create">
            <label>Config Key:</label>
            <input type="text" name="config_key">
            <label>Config Value:</label>
            <textarea name="config_value"></textarea>
            <button id="btn" type="submit">Add Config</button>
        </form>
    </div>

    <section class="load-siteconfig">
        <h2>Config List</h2>
        <div id="siteConfig"></div>
    </section>

    <?php include "./footer.php" ?>

</body>

<script>
    const apiUrl = "../controller/SiteConfigController.php";

    async function loadConfigs() {
        let res = await fetch(apiUrl + "?action=read");
        let data = await res.json();

        const container = document.getElementById("siteConfig");
        container.innerHTML = "";

        if (data.length > 0) {
            let table = document.createElement("table");
            let thead = document.createElement("thead");
            let tr1 = document.createElement("tr");

            tr1.innerHTML = `
                <th>ID</th>
                <th>Key</th>
                <th>Value</th>
                <th>Created At</th>
                <th>Updated At</th>
                <th>Actions</th>
            `;
            thead.appendChild(tr1);
            table.appendChild(thead);

            let tbody = document.createElement("tbody");

            data.forEach((c) => {
                let tr = document.createElement("tr");

                // Detect if it's a Google Maps link
                let isMap = c.config_key.toLowerCase().includes("map") && c.config_value.includes("maps.google.com");
                let displayValue = isMap ?
                    `<iframe src="${c.config_value}" width="100%" height="200" allowfullscreen loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>` :
                    c.config_value;

                tr.innerHTML = `
                    <td>${c.id}</td>
                    <td>${c.config_key}</td>
                    <td>${displayValue}</td>
                    <td>${c.created_at}</td>
                    <td>${c.updated_at}</td>
                    <td>
                        <button id="btn" onclick="editConfig(${c.id}, '${c.config_key}', \`${c.config_value}\`)">Edit</button>
                        <button id="btn" onclick="deleteConfig(${c.id})">Delete</button>
                    </td>
                `;
                tbody.appendChild(tr);
            });

            table.appendChild(tbody);
            container.appendChild(table);
        } else {
            let para = document.createElement("p");
            para.innerHTML = `No site configurations found!`;
            para.style.textAlign = "center";
            para.style.fontWeight = "bold";
            para.style.paddingTop = "40px";
            container.appendChild(para);
        }
    }

    document.getElementById("configForm").addEventListener("submit", async function(e) {
        e.preventDefault();

        let form = this.closest("form");
        let key = form.config_key.value.trim();
        let value = form.config_value.value.trim();

        if (key.length < 3) {
            alert("Config Key must be at least 3 characters long.");
            form.config_key.focus();
            return;
        }

        if (value.length < 3) {
            alert("Config Value must be at least 3 characters long.");
            form.config_value.focus();
            return;
        }

        let formData = new FormData(this);
        let res = await fetch(apiUrl, {
            method: "POST",
            body: formData
        });
        let result = await res.json();
        alert(result.message);

        form.reset();
        form.querySelector("[name='action']").value = "create";
        form.querySelector("button[type='submit']").innerText = "Add Config";

        let hiddenId = form.querySelector("[name='id']");
        if (hiddenId) hiddenId.remove();

        let cancelBtn = form.querySelector(".cancel-btn");
        if (cancelBtn) cancelBtn.remove();

        loadConfigs();
    });

    async function deleteConfig(id) {
        if (!confirm("Delete this config?")) return;
        let formData = new FormData();
        formData.append("action", "delete");
        formData.append("id", id);
        let res = await fetch(apiUrl, {
            method: "POST",
            body: formData
        });
        let result = await res.json();
        alert(result.message);
        loadConfigs();
    }

    function editConfig(id, key, value) {
        const form = document.getElementById("configForm");

        form.querySelector("[name='action']").value = "update";
        form.querySelector("[name='config_key']").value = key;
        form.querySelector("[name='config_value']").value = value;

        let button = form.querySelector(".cancel-btn");
        if (!button) {
            button = document.createElement("button");
            button.type = "button";
            button.className = "cancel-btn";
            button.innerHTML = "Cancel";
            button.setAttribute("id", "btn");
            form.appendChild(button);

            button.addEventListener("click", () => {
                form.reset();
                form.querySelector("[name='action']").value = "create";
                form.querySelector("button[type='submit']").innerText = "Add Config";
                button.remove();
            });
        }

        let hiddenId = form.querySelector("[name='id']");
        if (!hiddenId) {
            hiddenId = document.createElement("input");
            hiddenId.type = "hidden";
            hiddenId.name = "id";
            form.appendChild(hiddenId);
        }
        hiddenId.value = id;

        form.querySelector("button[type='submit']").innerText = "Update Config";
    }

    loadConfigs();
</script>

</html>