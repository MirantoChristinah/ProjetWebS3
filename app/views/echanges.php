<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Liste des échanges</title>
    <link rel="stylesheet" href="/css/styles.css">
</head>
<body>

<?php require 'header.php'; ?>

<h2>Liste des échanges</h2>

<p id="echangeCount" class="echange-count">Nombre d'échanges : 0</p>

<table>
    <thead>
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
                html += `
                    <tr>
                        <td>${e.id}</td>
                        <td>${e.produit1}</td>
                        <td>${e.produit2}</td>
                        <td>${e.user1}</td>
                        <td>${e.user2}</td>
                        <td class="status">${e.etat}</td>
                        <td>${e.date_envoie}</td>
                        <td>${e.date_acceptation ?? '-'}</td>
                        <td>
                            ${e.status_id == 1 ? `
                                <button class="btn accept" onclick="updateStatus(${e.id}, 3)">Accepter</button>
                                <button class="btn refuse" onclick="updateStatus(${e.id}, 2)">Refuser</button>
                            ` : '---'}
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