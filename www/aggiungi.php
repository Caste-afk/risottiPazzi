<?php
//PAGINA PER INSERIRE RICETTE
require_once __DIR__ . '/db.php';

$errore = '';

//prendi i dati dal form e mandali al db
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $nome = trim($_POST['nome'] ?? '');
    $ingredienti = trim($_POST['ingredienti'] ?? '');
    $istruzioni = trim($_POST['istruzioni'] ?? '');

    //controlla i campi
    if ($nome === '' || $ingredienti === '' || $istruzioni === '') {
        $errore = 'Riempire tutti i campi!';
    } else {
        try {
            //prepared--> dovrebbe prevenire sql injection...(?) chidere meglio
            $stmt = $pdo->prepare("INSERT INTO ricette (nome, ingredienti, istruzioni) VALUES (?, ?, ?)");
            $stmt->execute([$nome, $ingredienti, $istruzioni]);

            // torna alla pagina di landing
            header("Location: index.php?success=aggiunta");
            exit;
        } catch (PDOException $e) {
            $errore = "Errore durante il salvataggio nel database: " . htmlspecialchars($e->getMessage());
        }
    }
}
?>
<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inserisci Nuova Ricetta | Risotti Pazzi</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>

    <!-- Barra di Navigazione Orizzontale (Modulo 2) -->
    <?php include __DIR__ . '/navbar.php'; ?>

    <main class="container">
        <header class="section-header">
            <div>
                <h1 class="section-title">Aggiungi una Nuova <span>Ricetta</span></h1>
                <p class="section-subtitle">Compila il modulo per registrare una nuova ricetta nella tabella SQL.</p>
            </div>
            <a href="index.php" class="btn btn-secondary">
                ← Torna alla Landing
            </a>
        </header>

        <section class="card">
            <?php if (!empty($errore)): ?>
                <div class="alert alert-danger">
                    ⚠️ <?= $errore ?>
                </div>
            <?php endif; ?>

            <form action="aggiungi.php" method="POST">
                <div class="form-group">
                    <label for="nome" class="form-label">Nome della Ricetta</label>
                    <input 
                        type="text" 
                        id="nome" 
                        name="nome" 
                        class="form-control" 
                        placeholder="Es: Risotto ai Funghi Porcini & Timo" 
                        value="<?= htmlspecialchars($_POST['nome'] ?? '') ?>"
                        required
                    >
                </div>

                <div class="form-group">
                    <label for="ingredienti" class="form-label">Ingredienti (uno per riga o con dosi)</label>
                    <textarea 
                        id="ingredienti" 
                        name="ingredienti" 
                        class="form-control" 
                        rows="4" 
                        placeholder="- 320g Riso Carnaroli&#10;- 2 Peperoni rossi&#10;- 180g Gorgonzola DOP"
                        required
                    ><?= htmlspecialchars($_POST['ingredienti'] ?? '') ?></textarea>
                </div>

                <div class="form-group">
                    <label for="istruzioni" class="form-label">Istruzioni di Preparazione</label>
                    <textarea 
                        id="istruzioni" 
                        name="istruzioni" 
                        class="form-control" 
                        rows="6" 
                        placeholder="1. Tostare il riso a secco...&#10;2. Sfumare con vino bianco...&#10;3. Mantecare a freddo..."
                        required
                    ><?= htmlspecialchars($_POST['istruzioni'] ?? '') ?></textarea>
                </div>

                <div style="display: flex; gap: 12px; align-items: center; margin-top: 10px;">
                    <button type="submit" class="btn btn-primary">
                        💾 Invia & Salva Ricetta
                    </button>
                    <a href="index.php" class="btn btn-secondary">Annulla</a>
                </div>
            </form>

            <!-- pulsante per eliminare elementi -->
            <div class="action-banner">
                <div>
                    <strong>Gestione e Manutenzione Ricette</strong>
                    <p>Hai inserito una ricetta per errore o vuoi ripulire il ricettario?</p>
                </div>
                <a href="elimina.php" class="btn btn-danger">
                    🗑️ Elimina elementi
                </a>
            </div>
        </section>
    </main>

</body>
</html>
