<?php
// Determina la pagina corrente per applicare la classe 'active'
$currentPage = basename($_SERVER['PHP_SELF']);
?>
<nav class="navbar">
    <a href="index.php" class="nav-brand">
        <span>🍚</span> Risotti Pazzi
    </a>
    <ul class="nav-links">
        <li>
            <a href="index.php" class="nav-link <?= ($currentPage === 'index.php') ? 'active' : '' ?>">
                📖 Tutte le Ricette (Landing)
            </a>
        </li>
        <li>
            <a href="aggiungi.php" class="nav-link <?= ($currentPage === 'aggiungi.php') ? 'active' : '' ?>">
                ➕ Inserisci Ricetta
            </a>
        </li>
    </ul>
</nav>
