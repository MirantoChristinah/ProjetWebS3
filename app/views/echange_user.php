<?php 
// echanges_user.php
if(isset($_SESSION['user'])){
    $userId = $_SESSION['user']['id'];
} else {
    // Rediriger vers la page de connexion ou afficher un message d'erreur
    header('Location: /login');
    exit();
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mes Échanges</title>
    <style>
        * { box-sizing: border-box; }
        body { 
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; 
            margin: 0;
            padding: 20px;
            background: #f5f5f5;
        }
        .container { 
            max-width: 1400px; 
            margin: 0 auto;
            background: white;
            padding: 30px;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
        h1 { 
            color: #333; 
            margin-bottom: 30px;
            border-bottom: 3px solid #2196F3;
            padding-bottom: 10px;
        }
        h2 {
            color: #555;
            margin-top: 40px;
            margin-bottom: 20px;
        }
        
        /* Filtres */
        .filters {
            display: flex;
            gap: 10px;
            flex-wrap: wrap;
            margin-bottom: 30px;
            padding: 20px;
            background: #f9f9f9;
            border-radius: 6px;
        }
        .filter-btn {
            padding: 10px 20px;
            border: 2px solid #ddd;
            background: white;
            cursor: pointer;
            border-radius: 6px;
            font-size: 14px;
            font-weight: 500;
            transition: all 0.3s;
            color: #555;
        }
        .filter-btn:hover {
            background: #f0f0f0;
            border-color: #2196F3;
        }
        .filter-btn.active {
            background: #2196F3;
            color: white;
            border-color: #2196F3;
        }
        
        /* Tables */
        table {
            border-collapse: collapse;
            width: 100%;
            margin-bottom: 30px;
            box-shadow: 0 1px 3px rgba(0,0,0,0.1);
        }
        th, td {
            border: 1px solid #e0e0e0;
            padding: 12px;
            text-align: left;
        }
        th {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            font-weight: 600;
            text-transform: uppercase;
            font-size: 12px;
            letter-spacing: 0.5px;
        }
        tr:nth-child(even) {
            background: #f9f9f9;
        }
        tr:hover {
            background: #f0f7ff;
        }
        
        /* Boutons d'action */
        button {
            padding: 8px 16px;
            margin: 2px;
            cursor: pointer;
            border: none;
            border-radius: 4px;
            font-size: 13px;
            font-weight: 500;
            transition: all 0.2s;
        }
        .accept { 
            background: #4CAF50; 
            color: white; 
        }
        .accept:hover { 
            background: #45a049; 
            transform: translateY(-2px);
        }
        .refuse { 
            background: #f44336; 
            color: white; 
        }
        .refuse:hover { 
            background: #da190b; 
            transform: translateY(-2px);
        }
        .disabled { 
            background: #ccc; 
            cursor: not-allowed; 
            color: #666;
        }
        
        /* Status badges */
        .status {
            padding: 4px 12px;
            border-radius: 12px;
            font-size: 12px;
            font-weight: 600;
            display: inline-block;
        }
        .status-attente { background: #fff3cd; color: #856404; }
        .status-refuse { background: #f8d7da; color: #721c24; }
        .status-accepte { background: #d4edda; color: #155724; }
        
        /* Section produits */
        .produits-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(250px, 1fr));
            gap: 20px;
            margin-bottom: 30px;
        }
        .produit-card {
            border: 2px solid #e0e0e0;
            padding: 15px;
            border-radius: 8px;
            background: white;
            transition: all 0.3s;
            cursor: pointer;
        }
        .produit-card:hover {
            border-color: #2196F3;
            box-shadow: 0 4px 12px rgba(33, 150, 243, 0.2);
            transform: translateY(-2px);
        }
        .produit-card.selected {
            border-color: #4CAF50;
            background: #e8f5e9;
        }
        .produit-card h4 {
            margin: 0 0 10px 0;
            color: #333;
        }
        .produit-card p {
            margin: 5px 0;
            color: #666;
            font-size: 13px;
        }
        
        /* Formulaire d'échange */
        .echange-form {
            background: #f9f9f9;
            padding: 20px;
            border-radius: 8px;
            margin-bottom: 30px;
        }
        .form-row {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 20px;
            margin-bottom: 20px;
        }
        .form-group label {
            display: block;
            margin-bottom: 8px;
            font-weight: 600;
            color: #555;
        }
        .form-group select,
        .form-group input {
            width: 100%;
            padding: 10px;
            border: 2px solid #ddd;
            border-radius: 6px;
            font-size: 14px;
        }
        .btn-submit {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 12px 30px;
            font-size: 16px;
            border: none;
            border-radius: 6px;
            cursor: pointer;
            font-weight: 600;
        }
        .btn-submit:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(102, 126, 234, 0.4);
        }
        
        /* Messages */
        .message {
            padding: 15px;
            border-radius: 6px;
            margin-bottom: 20px;
            font-weight: 500;
        }
        .message-success {
            background: #d4edda;
            color: #155724;
            border: 1px solid #c3e6cb;
        }
        .message-error {
            background: #f8d7da;
            color: #721c24;
            border: 1px solid #f5c6cb;
        }
        
        .empty-message {
            text-align: center;
            padding: 40px;
            color: #999;
            font-style: italic;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>🔄 Mes Échanges - Utilisateur #<?= $userId ?></h1>
        
        <!-- Filtres -->
        <div class="filters">
            <button class="filter-btn active" onclick="filterEchanges('tous')">📋 Tous mes échanges</button>
            <button class="filter-btn" onclick="filterEchanges('envoyees')">📤 Mes demandes envoyées</button>
            <button class="filter-btn" onclick="filterEchanges('recues')">📥 Demandes reçues</button>
            <button class="filter-btn" onclick="filterEchanges('attente')">⏳ En attente</button>
            <button class="filter-btn" onclick="filterEchanges('accepte')">✅ Acceptés</button>
            <button class="filter-btn" onclick="filterEchanges('refuse')">❌ Refusés</button>
        </div>
        
        <!-- Tableau des échanges -->
        <div id="messageContainer"></div>
        
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Mon Produit</th>
                    <th>Produit Demandé</th>
                    <th>Demandeur</th>
                    <th>Destinataire</th>
                    <th>Statut</th>
                    <th>Date Envoi</th>
                    <th>Date Réponse</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody id="echangeTable">
                <tr><td colspan="9" class="empty-message">Chargement...</td></tr>
            </tbody>
        </table>
        
        <!-- Section création d'échange -->
        <h2>➕ Créer une nouvelle demande d'échange</h2>
        
        <div class="echange-form">
            <div class="form-row">
                <div class="form-group">
                    <label>Votre produit à échanger :</label>
                    <select id="produit1_id">
                        <option value="">-- Sélectionnez votre produit --</option>
                    </select>
                </div>
                <div class="form-group">
                    <label>Produit que vous souhaitez :</label>
                    <select id="produit2_id">
                        <option value="">-- Sélectionnez le produit désiré --</option>
                    </select>
                </div>
            </div>
            <div class="form-row">
                <div class="form-group">
                    <label>ID Destinataire (user2_id) :</label>
                    <input type="number" id="user2_id" placeholder="Ex: 2">
                </div>
            </div>
            <button class="btn-submit" onclick="createEchange()">📨 Envoyer la demande d'échange</button>
        </div>
        
        <!-- Liste des produits disponibles -->
        <h2>📦 Produits disponibles (Pour référence)</h2>
        <div class="produits-grid" id="produitsGrid">
            <!-- Rempli par JS -->
        </div>
    </div>

    <!-- Configuration JS pour echange.js -->
    <script>
        window.ECHANGE_CONFIG = {
            userId: <?= (int)$userId ?>,
            BASE_URL: '<?= BASE_URL ?>'
        };
    </script>
    <script src="/js/echange.js"></script>
</body>
</html>