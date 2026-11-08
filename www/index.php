<?php
// PAGINAS DI LANDING
require_once __DIR__ . '/db.php';

// scrivi tutte le ricette del db
try {
    $stmt = $pdo->query("SELECT id, nome, ingredienti, istruzioni, data_creazione FROM ricette ORDER BY id DESC");
    $ricette = $stmt->fetchAll();
} catch (PDOException $e) {
    die("Errore durante il recupero delle ricette: " . htmlspecialchars($e->getMessage()));
}
?>
<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Risotti Pazzi | Community & Ricette</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>

    <!-- Barra di Navigazione Orizzontale (Modulo 2) -->
    <?php include __DIR__ . '/navbar.php'; ?>

    <main class="container">
        <!-- Messaggi di notifica per operazioni completate -->
        <?php if (isset($_GET['success'])): ?>
            <?php if ($_GET['success'] === 'aggiunta'): ?>
                <div class="alert alert-success">
                    ✅ <strong>Fantastico!</strong> La tua nuova ricetta è stata salvata con successo nel database e ora è visibile qui sotto.
                </div>
            <?php elseif ($_GET['success'] === 'eliminata'): ?>
                <div class="alert alert-danger">
                    🗑️ <strong>Fatto!</strong> La ricetta selezionata è stata eliminata definitivamente dal database.
                </div>
            <?php endif; ?>
        <?php endif; ?>

        <header class="section-header">
            <div>
                <h1 class="section-title">Community dei <span>Risotti Pazzi</span></h1>
                <p class="section-subtitle">Tutte le ricette prelevate direttamente dalla tabella SQL del database.</p>
            </div>
            <a href="aggiungi.php" class="btn btn-primary">
                ➕ Aggiungi Nuova Ricetta
            </a>
        </header>

        <section class="card">
            <?php if (empty($ricette)): ?>
                <div class="empty-state">
                    <span>🍚</span>
                    <h3>Nessuna ricetta presente nel ricettario!</h3>
                    <p>La tabella SQL è attualmente vuota. Sei pronto a preparare il primo risotto?</p>
                    <br>
                    <a href="aggiungi.php" class="btn btn-primary">Inserisci la prima ricetta</a>
                </div>
            <?php else: ?>
                <div class="table-responsive">
                    <table class="recipes-table">
                        <thead>
                            <tr>
                                <th style="width: 60px;">ID</th>
                                <th style="width: 220px;">Nome Ricetta</th>
                                <th style="width: 320px;">Ingredienti</th>
                                <th>Istruzioni di Preparazione</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($ricette as $r): ?>
                                <tr>
                                    <td>
                                        <span class="recipe-id">#<?= htmlspecialchars($r['id']) ?></span>
                                    </td>
                                    <td>
                                        <div class="recipe-name"><?= htmlspecialchars($r['nome']) ?></div>
                                    </td>
                                    <td>
                                        <div class="content-box">
                                            <?= nl2br(htmlspecialchars($r['ingredienti'])) ?>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="content-box">
                                            <?= nl2br(htmlspecialchars($r['istruzioni'])) ?>
                                        </div>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            <?php endif; ?>
        </section>
    </main>

</body>
</html>
