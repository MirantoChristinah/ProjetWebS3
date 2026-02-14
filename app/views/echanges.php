<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Liste des échanges</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
    <link rel="stylesheet" href="/css/style.css">
</head>
<body>

<?php require 'header.php'; ?>

<div class="container my-5">
    <h2 class="mb-4" style="color: #9b203f;">
        <i class="bi bi-arrow-left-right"></i> Liste des échanges
    </h2>

    <p id="echangeCount" class="echange-count fw-bold text-dark">Nombre d'échanges : 0</p>

    <div class="table-responsive">
        <table class="table table-striped table-hover echange-table">
            <thead class="table-red">
                <tr>
                    <th>ID</th>
                    <th>Produit 1</th>
                    <th>Produit 2</th>
                    <th>User 1</th>
                    <th>User 2</th>
                    <th>Status</th>
                    <th>Date Envoi</th>
                    <th>Date Acceptation</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody id="echangeTable">
            </tbody>
        </table>
    </div>
</div>

<script>
function loadEchanges() {
    fetch('/api/echanges')
        .then(res => res.json())
        .then(data => {
            let rows = data;
            if (data && typeof data.count !== 'undefined' && Array.isArray(data.rows)) {
                document.getElementById('echangeCount').textContent = 'Nombre d\'échanges : ' + data.count;
                rows = data.rows;
            } else {
                const count = Array.isArray(data) ? data.length : 0;
                document.getElementById('echangeCount').textContent = 'Nombre d\'échanges : ' + count;
            }

            let html = "";
            rows.forEach(e => {
                const statusClass = e.status_id == 1 ? 'status-attente' : (e.status_id == 2 ? 'status-refuse' : 'status-accepte');
                html += `
                    <tr>
                        <td class="text-dark fw-bold">${e.id}</td>
                        <td class="text-dark"><strong>${e.produit1}</strong></td>
                        <td class="text-dark"><strong>${e.produit2}</strong></td>
                        <td class="text-dark">${e.user1}</td>
                        <td class="text-dark">${e.user2}</td>
                        <td><span class="status ${statusClass}">${e.etat}</span></td>
                        <td class="text-dark">${e.date_envoie}</td>
                        <td class="text-dark">${e.date_acceptation ?? '-'}</td>
                        <td class="actions-cell">
                            ${e.status_id == 1 ? `
                                <div class="d-flex gap-2 justify-content-center">
                                    <button class="btn btn-success btn-sm accept" onclick="updateStatus(${e.id}, 3)">
                                        <i class="bi bi-check"></i> Accepter
                                    </button>
                                    <button class="btn btn-danger btn-sm refuse" onclick="updateStatus(${e.id}, 2)">
                                        <i class="bi bi-x"></i> Refuser
                                    </button>
                                </div>
                            ` : '<span class="text-muted">---</span>'}
                        </td>
                    </tr>
                `;
            });

            document.getElementById('echangeTable').innerHTML = html;
        });
}

function updateStatus(id, status_id) {
    fetch(`/api/echanges/${id}/status`, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded'
        },
        body: `status_id=${status_id}`
    })
    .then(res => res.json())
    .then(data => {
        if(data.success){
            loadEchanges();
        } else {
            alert("Erreur !");
        }
    });
}

loadEchanges();
</script>

<?php require 'footer.php'; ?>


</body>
</html>