// FAKE data for now. Later this comes from api/stats.php
const data = {
    projects: [
        {id: 1, title: "Webshop", customer: "Bagergården", status: "progress", progress: 60},
        {id: 2, title: "Hjemmeside", customer: "KH Fix", status: "waiting", progress: 40},
        {id: 3, title: "Booking-side", customer: "Frisør Line", status: "done", progress: 100},
        {id: 4, title: "Portfolio", customer: "Mads", status: "planned", progress: 0}
    ],
    updatesPerWeek: {
        labels: ["Uge 38", "Uge 39", "Uge 40", "Uge 41", "Uge 42", "Uge 43"],
        counts: [2, 5, 3, 6, 4, 7]
    }
};

// Danish text for each status
const statusText = {
    planned: "Planlagt",
    progress: "I gang",
    waiting: "Venter på kunde",
    done: "Færdig"
};

// ----- Big numbers -----
document.getElementById("stat-done").textContent = data.projects.filter(p => p.status === "done").length;
document.getElementById("stat-active").textContent = data.projects.filter(p => p.status === "progress").length;
document.getElementById("stat-waiting").textContent = data.projects.filter(p => p.status === "waiting").length;

// ----- Project table -----
let rows = "";

for (const p of data.projects) {
    rows += `<tr>
        <td>${p.title}</td>
        <td>${p.customer}</td>
        <td><span class="pill ${p.status}">${statusText[p.status]}</span></td>
        <td>${p.progress}%</td>
    </tr>`;
}

document.getElementById("project-rows").innerHTML = rows;

// ----- Doughnut chart: projects by status -----
const count = s => data.projects.filter(p => p.status === s).length;

new Chart(document.getElementById("status-chart"), {
    type: "doughnut",
    data: {
        labels: ["Planlagt", "I gang", "Venter", "Færdig"],
        datasets: [{
            data: [count("planned"), count("progress"), count("waiting"), count("done")],
            backgroundColor: ["#9ca3af", "#fb923c", "#f87171", "#4ade80"]
        }]
    }
});

// ----- Line chart: updates per week -----
new Chart(document.getElementById("updates-chart"), {
    type: "line",
    data: {
        labels: data.updatesPerWeek.labels,
        datasets: [{data: data.updatesPerWeek.counts, borderColor: "#3b82f6"}]
    }
});