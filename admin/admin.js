// Danish text for each status
const statusText = {
    planned: "Planlagt",
    progress: "I gang",
    waiting: "Venter på kunde",
    done: "Færdig"
};

fetch("../api/stats.php")
    .then(res => res.json())
    .then(data => showDashboard(data));

function showDashboard(data) {
    // ----- Big numbers -----
    document.getElementById("stat-done").textContent = data.counts.done;
    document.getElementById("stat-active").textContent = data.counts.progress;
    document.getElementById("stat-waiting").textContent = data.counts.waiting;

    // ----- Project table -----
    let rows = "";
    for (const p of data.projects) {
        rows += `<tr>
            <td><a href="edit-project.php?id=${p.id}">${p.title}</a></td>
            <td>${p.customer_name}</td>
            <td><span class="pill ${p.status}">${statusText[p.status]}</span></td>
            <td>–</td>
        </tr>`;
    }
    document.getElementById("project-rows").innerHTML = rows;

    // ----- Doughnut chart: projects by status -----
    new Chart(document.getElementById("status-chart"), {
        type: "doughnut",
        data: {
            labels: ["Planlagt", "I gang", "Venter", "Færdig"],
            datasets: [{
                data: [data.counts.planned, data.counts.progress, data.counts.waiting, data.counts.done],
                backgroundColor: ["#9ca3af", "#fb923c", "#f87171", "#4ade80"]
            }]
        }
    });

    // ----- Line chart: updates per week -----
    new Chart(document.getElementById("updates-chart"), {
        type: "line",
        data: {
            labels: data.updatesPerWeek.labels,
            datasets: [{ data: data.updatesPerWeek.counts, borderColor: "#3b82f6" }]
        }
    });
}