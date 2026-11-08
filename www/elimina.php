<?php
// pagina per eliminare gli oggetti dal db
require_once __DIR__ . '/db.php';

$errore = '';

// quando il pulsante di eliminazione viene premuto ---> elimina
if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST['action']) && $_POST['action'] === 'elimina') {
    $id = filter_input(INPUT_POST, 'id', FILTER_VALIDATE_INT);

    if ($id) {
        try {
            $stmt = $pdo->prepare("DELETE FROM ricette WHERE id = ?");
            $stmt->execute([$id]);

            // torna alla pagina di landing dopo aver eliminato l'oggetto
            header("Location: index.php?success=eliminata");
            exit;
        } catch (PDOException $e) {
            $errore = "Errore durante l'eliminazione: " . htmlspecialchars($e->getMessage());
        }
    } else {
        $errore = "ID ricetta non valido per l'eliminazione.";
    }
}

// fai la lista delle ricette per eliminarle
try {
    $stmt = $pdo->query("SELECT id, nome, ingredienti, data_creazione FROM ricette ORDER BY id DESC");
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
    <title>Elimina Ricette | Risotti Pazzi</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>

    <!-- Barra di Navigazione Orizzontale (Modulo 2) ????-->
    <?php include __DIR__ . '/navbar.php'; ?>

    <main class="container">
        <header class="section-header">
            <div>
                <h1 class="section-title">Elimina <span>Ricette</span></h1>
                <p class="section-subtitle">Gestisci ed elimina dal database SQL le ricette che non desideri più mostrare.</p>
            </div>
            <div style="display: flex; gap: 10px;">
                <a href="aggiungi.php" class="btn btn-secondary">
                    ← Torna ad Inserisci
                </a>
                <a href="index.php" class="btn btn-secondary">
                    Landing Page
                </a>
            </div>
        </header>

        <section class="card">
            <?php if (!empty($errore)): ?>
                <div class="alert alert-danger">
                    ⚠️ <?= $errore ?>
                </div>
            <?php endif; ?>

            <?php if (empty($ricette)): ?>
                <div class="empty-state">
                    <span>✨</span>
                    <h3>Nessuna ricetta da eliminare!</h3>
                    <p>La tabella SQL è vuota.</p>
                    <br>
                    <a href="aggiungi.php" class="btn btn-primary">Aggiungi una ricetta</a>
                </div>
            <?php else: ?>
                <div class="table-responsive">
                    <table class="recipes-table">
                        <thead>
                            <tr>
                                <th style="width: 70px;">ID</th>
                                <th>Nome Ricetta</th>
                                <th>Ingredienti (anteprima)</th>
                                <th style="width: 140px; text-align: center;">Azione</th>
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
                                    <td style="text-align: center; vertical-align: middle;">
                                        <!-- Form con conferma JavaScript prima dell'invio -->
                                        <form 
                                            action="elimina.php" 
                                            method="POST" 
                                            onsubmit="return confirm('⚠️ Conferma Eliminazione:\nSei sicuro di voler eliminare definitivamente dal database la ricetta «<?= addslashes(htmlspecialchars($r['nome'])) ?>»? L\'operazione non è reversibile.');"
                                        >
                                            <input type="hidden" name="action" value="elimina">
                                            <input type="hidden" name="id" value="<?= htmlspecialchars($r['id']) ?>">
                                            <button type="submit" class="btn btn-danger btn-sm">
                                                🗑️ Elimina
                                            </button>
                                        </form>
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
