const token = new URLSearchParams(window.location.search).get("t");

fetch(`api/project.php?t=${encodeURIComponent(token ?? "")}`)
    .then(res => {
        if (!res.ok) throw new Error("Not found");
        return res.json();
    })
    .then(p => showProject(p))
    .catch(() => {
        document.getElementById("title").textContent = "Projektet blev ikke fundet";
    });

function showProject(p) {
    document.getElementById("title").textContent = p.title;
    document.getElementById("customer").textContent = p.customer_name;

    // The database returns done as "0" or "1", so compare with Number()
    const done = p.milestones.filter(m => Number(m.done) === 1).length;
    const total = p.milestones.length;
    const percent = total ? Math.round(done / total * 100) : 0;

    document.getElementById("bar").style.width = percent + "%";
    document.getElementById("percent").textContent = percent + "%";

    let html = "";
    for (const m of p.milestones) {
        const isDone = Number(m.done) === 1;
        html += `<div class="step ${isDone ? "done" : ""}">${isDone ? "✓" : "○"} ${m.title}</div>`;
    }
    document.getElementById("milestones").innerHTML = html;

    if (p.waiting_on_client) {
        document.getElementById("waiting").textContent = p.waiting_on_client;
    } else {
        document.getElementById("waiting-box").style.display = "none";
    }

    if (p.preview_url) {
        document.getElementById("preview").href = p.preview_url;
    } else {
        document.getElementById("preview").style.display = "none";
    }

    let updatesHtml = "";
    for (const u of p.updates) {
        updatesHtml += `<div class="update">${u.created_at} – ${u.message}</div>`;
    }
    document.getElementById("updates").innerHTML = updatesHtml;
}