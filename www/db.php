<?php
/**
 * pagina di gestione db, solo script php
 *  
 */

$host = getenv('DB_HOST') ?: 'db';
$db   = getenv('DB_NAME') ?: 'risottiPazzi';
$user = getenv('DB_USER') ?: 'utente';
$pass = getenv('DB_PASSWORD') ?: 'utentePassword';

$dsn = "mysql:host=$host;dbname=$db;charset=utf8mb4";
$options = [
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    PDO::ATTR_EMULATE_PREPARES   => false,
];

try {
    $pdo = new PDO($dsn, $user, $pass, $options);

    // crea tabelle di default se no ci sono
    $sqlCreateTable = "
        CREATE TABLE IF NOT EXISTS ricette (
            id INT AUTO_INCREMENT PRIMARY KEY,
            nome VARCHAR(150) NOT NULL,
            ingredienti TEXT NOT NULL,
            istruzioni TEXT NOT NULL,
            data_creazione TIMESTAMP DEFAULT CURRENT_TIMESTAMP
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
    ";
    $pdo->exec($sqlCreateTable);

    // se la tabella e` vuota metti delle ricette di base 
    $countStmt = $pdo->query("SELECT COUNT(*) FROM ricette");
    $total = $countStmt->fetchColumn();

    if ($total == 0) {
        #anche qui fa il prepare... chiedere meglio
        $seedStmt = $pdo->prepare("
            INSERT INTO ricette (nome, ingredienti, istruzioni) 
            VALUES (?, ?, ?)
        ");

        $ricetteSeed = [
            [
                'Risotto ai Peperoni Arrostiti e Gorgonzola DOP',
                "- 320g Riso Carnaroli\n- 2 Peperoni rossi arrostiti\n- 180g Gorgonzola DOP (freddo di frigo)\n- 25g Burro ghiacciato\n- 40g Parmigiano Reggiano 24 mesi\n- 1,2L Brodo vegetale neutro (senza carote)\n- 100ml Vino bianco secco\n- 1 Scalogno piccolo\n- Olio EVO, sale, pepe e nocciole tostate",
                "1. Arrostire i peperoni in forno a 220°C per 25 min, chiuderli in un sacchetto, spellarli e scolare l'acqua di vegetazione. Frullare la polpa a filo con olio EVO ottenendo una crema densa.\n2. Tostare il Carnaroli a secco in casseruola finché scotta al dorso della mano.\n3. Sfumare con vino bianco secco ed iniziare la risottatura con brodo bollente.\n4. A 3 minuti da fine cottura incorporare la crema densa di peperoni.\n5. Togliere dal fuoco, far riposare 1 minuto e mantecare all'onda con Gorgonzola e burro ghiacciato.\n6. Servire con gocce di gorgonzola e granella di nocciole tostate."
            ],
            [
                'Risotto Tradizionale allo Zafferano',
                "- 320g Riso Carnaroli\n- 1 bustina o stimmi di Zafferano puro\n- 1L Brodo di carne di manzo\n- 1 Cipolla dorata tritata finissima\n- 50g Burro di malga\n- 60g Grana Padano o Parmigiano\n- 1/2 bicchiere Vino bianco secco",
                "1. Sciogliere gli stimmi di zafferano in una tazzina di brodo tiepido per almeno 30 minuti.\n2. Appassire dolcemente la cipolla con metà del burro senza farla dorare.\n3. Aggiungere il riso, tostarlo per 2-3 minuti e sfumare col vino.\n4. Portare a cottura aggiungendo brodo mestolo dopo mestolo.\n5. Verso la fine versare l'infuso di zafferano mescolando bene per uniformare il colore oro.\n6. Fuori dal fuoco, mantecare energicamente col restante burro freddo e formaggio grattugiato."
            ],
            [
                'Risotto ai Funghi Porcini & Timo Fresco',
                "- 320g Riso Carnaroli o Vialone Nano\n- 350g Funghi porcini freschi (o 30g secchi rinvenuti)\n- 1 Spicchio d'aglio\n- 1L Brodo vegetale\n- 40g Burro freddo\n- Olio EVO, prezzemolo fresco, rametto di timo\n- Sale e pepe nero",
                "1. Pulire i funghi e tagliarli a fette regolari. Trifolarne 2/3 in padella con filo d'olio, aglio e timo per 4 minuti a fiamma viva.\n2. In casseruola avviare il riso tostandolo a secco, sfumare con vino bianco secco.\n3. Risottare aggiungendo brodo caldo e inserire i funghi trifolati a metà cottura.\n4. Negli ultimi minuti tenere da parte le fette di fungo più belle per la decorazione.\n5. Spegnere il fuoco, mantecare con burro ghiacciato e pepe nero macinato fresco."
            ]
        ];

        foreach ($ricetteSeed as $r) {
            $seedStmt->execute([$r[0], $r[1], $r[2]]);
        }
    }
} catch (PDOException $e) {
    die("Errore di connessione al database: " . htmlspecialchars($e->getMessage()));
}
