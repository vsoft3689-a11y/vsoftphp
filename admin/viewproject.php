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
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Project</title>
    <style>
        .filterBox {
            width: 100%;
            height: 100%;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
        }

        .filterBox>form {
            padding: 30px;
            display: flex;
            justify-content: center;
            align-items: center;
            gap: 10px;
            box-shadow: 0 2px 6px rgba(0, 0, 0, 0.1);
            margin: 30px 0;
            border-radius: 5px;
        }

        select {
            margin: 8px 0;
            padding: 8px;
            width: 12vw;
            border: 1px solid #ccc;
            border-radius: 5px;
        }

        .load-projects {
            margin: 10px 50px;
        }

        #projectHeading {
            margin: 20px;
            text-align: center;
        }

        table {
            width: 100%;
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
        }

        th {
            background: #06BBCC;
            color: #fff;
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

        #btn {
            width: auto;
            background: #06BBCC;
            color: #fff;
            font-weight: bold;
            padding: 10px;
            border-radius: 5px;
            cursor: pointer;
            border: none;
            transition: 0.3s;
        }
    </style>
</head>

<body>
    <?php include "adminnavbar.php" ?>
    <div class="filterBox">
        <form id="filterForm" method="POST">
            <select name="degree" id="degree" required onchange="updateBranches()">
                <option value="">Select Degree</option>
                <option value="B.Tech">B.Tech</option>
                <option value="M.Tech">M.Tech</option>
                <option value="MCA">MCA</option>
                <option value="MBA">MBA</option>
            </select>

            <select name="branch" id="branch" required>
                <option value="">Select Branch</option>
            </select>

            <div id="projectTypeDiv">
                <select name="type" id="type" required>
                    <option value="">Select Project Type</option>
                    <option value="mini">Mini Project</option>
                    <option value="major">Major Project</option>
                </select>
            </div>

            <select name="domain" id="domain" required>
                <option value="">Select Domain</option>
            </select>

            <button id="btn" type="submit">Filter</button>
            <button id="btn" onclick="resetForm()" type="button">Clear</button>
        </form>
    </div>

    <div class="load-projects">
        <h2 id="projectHeading">Projects</h2>
        <div id="projects"></div>
    </div>

    <?php include "./footer.php" ?>

</body>

<script>
    const apiUrl = "../controller/ProjectController.php";

    function updateBranches() {
        let degree = document.getElementById("degree").value;
        let branch = document.getElementById("branch");
        let domain = document.getElementById("domain");

        branch.innerHTML = "<option value=''>Select Branch</option>";
        domain.innerHTML = "<option value=''>Select Domain</option>";

        if (degree === "B.Tech") {
            ["CSE", "ECE", "EEE", "Civil", "Mech"].forEach(b => branch.innerHTML += `<option value="${b}">${b}</option>`);
        } else if (degree === "M.Tech") {
            ["CSE", "ECE", "Power Systems", "Structural Engineering"].forEach(b => branch.innerHTML += `<option value="${b}">${b}</option>`);
        } else if (degree === "MCA") {
            ["Software Engineering", "Networking", "Hardware Technologies", "Management Information Systems"].forEach(b => branch.innerHTML += `<option value="${b}">${b}</option>`);
        } else if (degree === "MBA") {
            ["Marketing", "Finance", "Hospitality & Tourism", "Banking & Insurance"].forEach(b => branch.innerHTML += `<option value="${b}">${b}</option>`);
        }
    }

    document.getElementById("branch").addEventListener("change", function() {
        let branch = this.value;
        let degree = document.getElementById("degree").value;
        let domain = document.getElementById("domain");

        domain.innerHTML = "<option value=''>Select Domain</option>";

        if (degree === "B.Tech") {
            if (branch === "CSE") {
                ["Web Development", "AI/ML", "Cloud Computing", "App Development", "Cyber Security"].forEach(d => domain.innerHTML += `<option value="${d}">${d}</option>`);
            } else if (branch === "ECE") {
                ["VLSI", "Embedded Systems", "IoT", "Robotics"].forEach(d => domain.innerHTML += `<option value="${d}">${d}</option>`);
            } else if (branch === "EEE") {
                ["Power Electronics", "Renewable Energy", "Smart Grids"].forEach(d => domain.innerHTML += `<option value="${d}">${d}</option>`);
            } else if (branch === "Civil") {
                ["Structural Analysis", "Construction Management", "Geotechnical"].forEach(d => domain.innerHTML += `<option value="${d}">${d}</option>`);
            } else if (branch === "Mech") {
                ["Thermal Engineering", "Automobile", "Manufacturing", "Mechatronics"].forEach(d => domain.innerHTML += `<option value="${d}">${d}</option>`);
            }
        } else if (degree === "M.Tech") {
            if (branch === "CSE") {
                ["Data Mining", "Blockchain", "Network Security"].forEach(d => domain.innerHTML += `<option value="${d}">${d}</option>`);
            } else if (branch === "ECE") {
                ["Wireless Communication", "Signal Processing", "VLSI Design"].forEach(d => domain.innerHTML += `<option value="${d}">${d}</option>`);
            } else if (branch === "Power Systems") {
                ["FACTS", "Smart Energy System", "Load Flow Studies"].forEach(d => domain.innerHTML += `<option value="${d}">${d}</option>`);
            } else if (branch === "Structural Engineering") {
                ["Finite Element", "Concrete Technology", "Seismic Design"].forEach(d => domain.innerHTML += `<option value="${d}">${d}</option>`);
            }
        } else if (degree === "MCA") {
            if (branch === "Software Engineering") {
                ["Database Management Systems", "Software Design & Architecture", "Software Project Management"].forEach(d => domain.innerHTML += `<option value="${d}">${d}</option>`);
            } else if (branch === "Networking") {
                ["Computer Networking", "Network Security", "Cloud Networking", "Data Communication"].forEach(d => domain.innerHTML += `<option value="${d}">${d}</option>`);
            } else if (branch === "Hardware Technologies") {
                ["Embedded Systems", "VLSI Design", "IoT Hardware & Sensors"].forEach(d => domain.innerHTML += `<option value="${d}">${d}</option>`);
            } else if (branch === "Management Information Systems") {
                ["Enterprise Systems", "E-Business & E-Commerce Systems", "Information Security"].forEach(d => domain.innerHTML += `<option value="${d}">${d}</option>`);
            }
        } else if (degree === "MBA") {
            if (branch === "Marketing") {
                ["Brand Management", "Digital Marketing", "International Marketing", "Sales & Distribution Management"].forEach(d => domain.innerHTML += `<option value="${d}">${d}</option>`);
            } else if (branch === "Finance") {
                ["Corporate Finance", "Investment Banking", "Risk Management"].forEach(d => domain.innerHTML += `<option value="${d}">${d}</option>`);
            } else if (branch === "Hospitality & Tourism") {
                ["Hotel Management & Operations", "Housekeeping & Facility Management", "Travel & Transport Management", "Sustainable Eco-Tourism"].forEach(d => domain.innerHTML += `<option value="${d}">${d}</option>`);
            } else if (branch === "Banking & Insurance") {
                ["Corporate Banking", "Investment Banking", "Retail Banking", "Insurance Management"].forEach(d => domain.innerHTML += `<option value="${d}">${d}</option>`);
            }
        }
    });

    document.getElementById("filterForm").addEventListener("submit", async (e) => {
        e.preventDefault();

        let degree = document.getElementById("degree").value;
        let branch = document.getElementById("branch").value;
        let type = document.getElementById("type").value;
        let domain = document.getElementById("domain").value;

        let formData = new FormData();
        formData.append("action", "getByFilters");
        formData.append("degree", degree);
        formData.append("branch", branch);
        formData.append("type", type);
        formData.append("domain", domain);

        let res = await fetch(apiUrl, {
            method: "POST",
            body: formData
        });
        let result = await res.json();

        if (result.length > 0) {
            document.getElementById("projects").innerHTML = "";
            let table = document.createElement("table");
            let thead = document.createElement("thead");
            let tr1 = document.createElement("tr");
            let th1 = document.createElement("th");
            let th2 = document.createElement("th");
            let th3 = document.createElement("th");
            let th4 = document.createElement("th");
            let th5 = document.createElement("th");
            let th6 = document.createElement("th");
            let th7 = document.createElement("th");
            let th8 = document.createElement("th");
            let th9 = document.createElement("th");
            let th10 = document.createElement("th");

            th1.innerHTML = "ID";
            th2.innerHTML = "Title";
            th3.innerHTML = "Type";
            th4.innerHTML = "Domain";
            th5.innerHTML = "Degree";
            th6.innerHTML = "Branch";
            th7.innerHTML = "Youtube URL";
            th8.innerHTML = "Abstract Paper";
            th9.innerHTML = "Base Paper";
            th10.innerHTML = "Actions";

            tr1.append(th1, th2, th3, th4, th5, th6, th7, th8, th9, th10);
            thead.appendChild(tr1);
            table.appendChild(thead);

            let tbody = document.createElement("tbody");
            tbody.innerHTML = "";

            result.forEach((p) => {
                let tr = document.createElement("tr");
                tr.innerHTML = `
                <td>${p.id}</td>
                <td>${p.title}</td>
                <td>${p.type}</td>
                <td>${p.domain}</td>
                <td>${p.degree}</td>
                <td>${p.branch}</td>
                <td><a href="${p.youtube_url}" target="_blank">Watch Video</a></td>
                <td><a href="${p.file_path_abstract}" target="_blank">Download Abstract</a></td>
                <td><a href="${p.file_path_basepaper}" target="_blank">Download Basepaper</a></td>
                <td>
                    <button id="btn" onclick="window.location='./updateproject.php?id=${p.id}&degree=${p.degree}&branch=${p.branch}&type=${p.type}&domain=${p.domain}&title=${p.title}&description=${p.description}&technologies=${p.technologies}&price=${p.price}&youtube_url=${p.youtube_url}&file_path_abstract=${p.file_path_abstract}&file_path_basepaper=${p.file_path_basepaper}'">
                        Edit
                    </button>
                    <button id="btn" onclick="deleteProject(${p.id})">Delete</button>
                </td>
            `;
                tbody.appendChild(tr);
            });

            table.appendChild(tbody);
            document.getElementById("projects").appendChild(table);
        } else {
            // If no results
            document.getElementById("projects").innerHTML = "";
            let para = document.createElement("p");
            para.innerHTML = `No projects found with selected filters!`;
            para.style.textAlign = "center";
            para.style.fontWeight = "bold";
            para.style.paddingTop = "40px";
            document.getElementById("projects").appendChild(para);
        }
    });

    document.getElementById("")

    async function loadProjects() {
        let res = await fetch(apiUrl + "?action=read");
        let data = await res.json();

        if (data.length > 0) {
            document.getElementById("projects").innerHTML = "";
            let table = document.createElement("table");
            let thead = document.createElement("thead");
            let tr1 = document.createElement("tr");
            let th1 = document.createElement("th");
            let th2 = document.createElement("th");
            let th3 = document.createElement("th");
            let th4 = document.createElement("th");
            let th5 = document.createElement("th");
            let th6 = document.createElement("th");
            let th7 = document.createElement("th");
            let th8 = document.createElement("th");
            let th9 = document.createElement("th");
            let th10 = document.createElement("th");

            th1.innerHTML = "ID";
            th2.innerHTML = "Title";
            th3.innerHTML = "Type";
            th4.innerHTML = "Domain";
            th5.innerHTML = "Degree";
            th6.innerHTML = "Branch";
            th7.innerHTML = "Youtube URL";
            th8.innerHTML = "Abstract Paper";
            th9.innerHTML = "Base Paper";
            th10.innerHTML = "Actions";

            tr1.append(th1, th2, th3, th4, th5, th6, th7, th8, th9, th10);
            thead.appendChild(tr1);

            table.appendChild(thead);

            let tbody = document.createElement("tbody");
            tbody.innerHTML = "";

            data.forEach((p) => {
                let tr = document.createElement("tr");
                tr.innerHTML = `
                                <td>${p.id}</td>
                                <td>${p.title}</td>
                                <td>${p.type}</td>
                                <td>${p.domain}</td>
                                <td>${p.degree}</td>
                                <td>${p.branch}</td>
                                <td><a href="${p.youtube_url}" target="_blank">Watch Video</a></td>
                                <td><a href="${p.file_path_abstract}" target="_blank">Downlaod Abstract</a></td>
                                <td><a href="${p.file_path_basepaper}" target="_blank">Download Basepaper</a></td>
                                <td>
                                    <button id="btn" onclick="window.location='./updateproject.php?id=${p.id}&degree=${p.degree}&branch=${p.branch}&type=${p.type}&domain=${p.domain}&title=${p.title}&description=${p.description}&technologies=${p.technologies}&price=${p.price}&youtube_url=${p.youtube_url}&file_path_abstract=${p.file_path_abstract}&file_path_basepaper=${p.file_path_basepaper}'">
                                    Edit</button>
                                    <button id="btn" onclick="deleteProject(${p.id})">Delete</button>
                                </td>
                            `;
                tbody.appendChild(tr);
            });
            table.appendChild(tbody);
            document.getElementById("projects").appendChild(table);
        } else {
            document.getElementById("projects").innerHTML = "";
            let para = document.createElement("p");
            para.innerHTML = `No projects list found!`;
            para.style.textAlign = "center";
            para.style.fontWeight = "bold";
            para.style.paddingTop = "40px"
            document.getElementById("projects").appendChild(para);
        }
    }

    // Delete project
    async function deleteProject(id) {
        if (!confirm("Are you sure you want to delete this project?")) return;
        let formData = new FormData();
        formData.append("action", "delete");
        formData.append("id", id);
        let res = await fetch(apiUrl, {
            method: "POST",
            body: formData
        });
        let result = await res.json();
        alert(result.success ? "Deleted!" : "Delete failed");
        loadProjects();
    }

    function resetForm() {
        document.getElementById("filterForm").reset();
        loadProjects();
    }

    // Load on page start
    loadProjects();
</script>

</html>