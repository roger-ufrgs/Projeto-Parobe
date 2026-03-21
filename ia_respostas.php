<?php

header("Content-Type: application/json");

require_once __DIR__ . "/DataBase.php";

$pdo = DataBase::connect();

$stmt = $pdo->query("
SELECT 
g.id,
g.resposta,
g.criado_em,
d.status,
d.risco
FROM diagnostico_gpt g
JOIN diagnostico d
ON d.id = g.diagnostico_id
ORDER BY g.id DESC
LIMIT 20
");

$dados = $stmt->fetchAll(PDO::FETCH_ASSOC);

echo json_encode($dados);