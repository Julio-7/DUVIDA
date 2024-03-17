<?php
include_once 'dataBase.php';

$stmt = $db->prepare('SELECT * FROM players');
$stmt->execute();
$players = $stmt->fetchAll(PDO::FETCH_ASSOC);

$templateLinha = file_get_contents('linhaTabelaJogador.html');
$tabelaListaJogadores = file_get_contents('listaJogadores.html');
$templateLinhasProcessado = '';
foreach ($players as $player) {
    $templateLinhaProcessado = str_replace('{ID}', $player['id'], $templateLinha);
    $templateLinhaProcessado = str_replace('{NOME}', $player['nome'], $templateLinhaProcessado);
    $templateLinhaProcessado = str_replace('{USERNAME}', $player['username'], $templateLinhaProcessado);
    $templateLinhaProcessado = str_replace('{EMAIL}', $player['email'], $templateLinhaProcessado);
    $templateLinhasProcessado .= $templateLinhaProcessado;
}

$tabelaListaJogadores = str_replace('{LINHAS}', $templateLinhasProcessado, $tabelaListaJogadores);
echo $tabelaListaJogadores;