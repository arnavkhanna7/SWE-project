<?php
require_once(__DIR__ . "/../config/database.php");

//Für einfache Anfragen an die Datenbank
//Nicht gegen SQL Injections abgesichert also nur bei Abfragen ohne User-inputs verwenden
function query_simple_assoc($sql) {
    global $pdo;
    return $pdo->query($sql)->fetchAll(PDO::FETCH_ASSOC);
}

//Alle Übungen zu einer fach_id
function query_uebungen_fid($fid) {
    global $pdo;
    $stmt = $pdo->prepare('SELECT * FROM hsgg.uebung WHERE fachID = ?');
    $stmt->bindParam(1, $fid);
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

//Alle Daten zu Fach mit fach_id
function query_fach_fid($fid) {
    global $pdo;
    $stmt = $pdo->prepare('SELECT * FROM hsgg.fach WHERE id = ?');
    $stmt->bindParam(1, $fid);
    $stmt->execute();
    return $stmt->fetch(PDO::FETCH_ASSOC);
}

//Alle Daten zu Übung mit fach_id und uebungs_id
function query_uebung_fid_uid($fid, $uid) {
   global $pdo;
    $stmt = $pdo->prepare('SELECT * FROM hsgg.uebung WHERE fachID = ? AND id = ?');
    $stmt->bindParam(1, $fid);
    $stmt->bindParam(2, $uid);
    $stmt->execute();
    return $stmt->fetch(PDO::FETCH_ASSOC);
}

//Ermittle fachID zu uebung
function db_query_uebung_fach($uid) {
    global $pdo;
    $stmt = $pdo->prepare('SELECT fachID FROM hsgg.uebung WHERE id = ?');
    $stmt->bindParam(1, $uid);
    $stmt->execute();
    return $stmt->fetch(PDO::FETCH_ASSOC);
}

//Updatet den Datenbank Eintrag mit neuen Texten
function update_editable_contents($uid, $explanation_box, $tips_box) {
    global $pdo;
    $stmt = $pdo->prepare('UPDATE hsgg.uebung SET explanation_box = ?, tips_box = ? WHERE id = ?;');
    $stmt->bindParam(1, $explanation_box);
    $stmt->bindParam(2, $tips_box);
    $stmt->bindParam(3, $uid);
    $stmt->execute();
}

//Erstelle ein neues Fach
function db_insert_new_fach($name, $dir_name, $symbol, $kachelfarbe) {
    global $pdo;
    $stmt = $pdo->prepare('INSERT INTO hsgg.fach (name, dir_name, symbol, kachelfarbe) VALUES (?, ?, ?, ?);');
    $stmt->bindParam(1, $name);
    $stmt->bindParam(2, $dir_name);
    $stmt->bindParam(3, $symbol);
    $stmt->bindParam(4, $kachelfarbe);
    $stmt->execute();
}

function db_update_fach($fid, $name, $dir_name, $symbol, $kachelfarbe) {
   global $pdo;
    $stmt = $pdo->prepare('UPDATE hsgg.fach SET name = ?, symbol = ?, kachelfarbe = ? WHERE id = ?');
    $stmt->bindParam(1, $name);
    $stmt->bindParam(2, $symbol);
    $stmt->bindParam(3, $kachelfarbe);
    $stmt->bindParam(4, $fid);
    $stmt->execute();
}

function db_delete_fach($fid) {
    global $pdo;
    $stmt = $pdo->prepare('DELETE FROM hsgg.fach WHERE id = ?');
    $stmt->bindParam(1, $fid);
    $stmt->execute();
}

function db_insert_new_uebung($fid, $name, $file_name, $beschreibung, $symbol, $kachelfarbe) {
    global $pdo;
    $fid_int = (int)$fid;
    $explanation_box = "";
    $tips_box = "";

    $stmt = $pdo->prepare('INSERT INTO hsgg.uebung (name, file_name, beschreibung, symbol, kachelfarbe, fachID, explanation_box, tips_box) VALUES (?, ?, ?, ?, ?, ?, ?, ?)');
    $stmt->bindParam(1, $name);
    $stmt->bindParam(2, $file_name);
    $stmt->bindParam(3, $beschreibung);
    $stmt->bindParam(4, $symbol);
    $stmt->bindParam(5, $kachelfarbe);
    $stmt->bindParam(6, $fid_int, PDO::PARAM_INT);
    $stmt->bindParam(7, $explanation_box);
    $stmt->bindParam(8, $tips_box);
    $stmt->execute();
}

function db_update_uebung($id, $name, $file_name, $beschreibung, $symbol, $kachelfarbe, $fachID) {
    global $pdo;
    $stmt = $pdo->prepare('UPDATE hsgg.uebung SET name = ?, file_name = ?,beschreibung = ?, symbol = ?, kachelfarbe = ?, fachID = ? WHERE id = ?;');
    $stmt->bindParam(1, $name);
    $stmt->bindParam(2, $file_name);
    $stmt->bindParam(3, $beschreibung);
    $stmt->bindParam(4, $symbol);
    $stmt->bindParam(5, $kachelfarbe);
    $stmt->bindParam(6, $fachID, PDO::PARAM_INT);
    $stmt->bindParam(7, $id);
    $stmt->execute();
}

function db_delete_uebung($fid) {
    global $pdo;
    $stmt = $pdo->prepare('DELETE FROM hsgg.uebung WHERE id = ?');
    $stmt->bindParam(1, $fid);
    $stmt->execute();
}