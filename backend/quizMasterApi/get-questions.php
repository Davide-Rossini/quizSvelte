<?php
header("Access-Control-Allow-Origin: http://localhost:5173");
header('Content-Type: application/json');

if (!isset($_GET['id'])) {
    sendError('id missing', __LINE__);
}
if (!ctype_digit($_GET['id'])) {
    sendError('id not valid', __LINE__);
}

require_once(__DIR__ . '/protected/config.php');

try {
    $numeroDomande = 5;
    $query = $db->prepare("SELECT d.id AS id_domanda, d.testo AS testo_domanda, r.id AS id_risposta, r.testo AS testo_risposta, a.corretta
        FROM domanda d
        JOIN appartiene a ON d.id = a.fkDomanda
        JOIN risposta r ON a.fkRisposta = r.id
        WHERE d.fkQuiz=:id");
    $query->bindValue(':id', $_GET['id'], PDO::PARAM_INT);
    $query->execute();
    $result = $query->fetchAll(PDO::FETCH_ASSOC);

    // Creazione dell'array per l'output JSON
    $output = array();


    foreach ($result as $row) {
        $id_domanda = $row['id_domanda'];
        $testo_domanda = $row['testo_domanda'];
        $id_risposta = $row['id_risposta'];
        $testo_risposta = $row['testo_risposta'];
        $corretta = $row['corretta'];

        // Creazione di un array per ogni domanda
        if (!array_key_exists($id_domanda, $output)) {
            $output[$id_domanda] = array(
                'id_domanda' => $id_domanda,
                'domanda' => $testo_domanda,
                'risposte' => array()
            );
        }

        // Aggiunta di una risposta all'array delle risposte
        $risposta = array(
            'id_risposta' => $id_risposta,
            'testo_risposta' => $testo_risposta,
            'corretta' => ($corretta == 1) ? true : false
        );


        array_push($output[$id_domanda]['risposte'], $risposta);
    }


    shuffle($output);
    $arrayTagliato = array_slice($output, 0, $numeroDomande);

    $arrayFinale = array();
    foreach ($arrayTagliato as $val) {
        shuffle($val['risposte']);
        array_push($arrayFinale, $val);
    }

    echo '{"status":1, "data":' . json_encode(array_values($arrayFinale), JSON_UNESCAPED_UNICODE) . '}';
} catch (PDOException $ex) {
    sendError('error executing query', __LINE__);
}

function sendError($message = 'error', $debug = 0)
{
    echo '{"status":0, "message":"' . $message . '", "debug": ' . $debug . '}';
    exit();
}
