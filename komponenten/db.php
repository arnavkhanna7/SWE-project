<?php
// Database configuration
$host     = "localhost";   // or your server IP
$username = "root";
$password = "root";
$database = "hsgg";

//Für einfache Anfragen an die Datenbank
//Nicht gegen SQL Injections abgesichert also nur bei Abfragen ohne User-inputs verwenden
function query_simple_assoc($sql) {
    global $host, $username, $password, $database;
    $conn = new mysqli($host, $username, $password, $database);

    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    $result = $conn->query($sql)->fetch_all(MYSQLI_ASSOC);
    $conn->close();
    return $result;
}

//Alle Übungen zu einer fach_id
function query_uebungen_fid($fid) {
    global $host, $username, $password, $database;
    $conn = new mysqli($host, $username, $password, $database);

    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    $stmt = $conn->prepare('SELECT * FROM uebung WHERE fachID = ?');
    $stmt->bind_param("s", $fid);
    $stmt->execute();
    $result = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    $conn->close();
    return $result;
}

//Alle Daten zu Fach mit fach_id
function query_fach_fid($fid) {
    global $host, $username, $password, $database;
    $conn = new mysqli($host, $username, $password, $database);

    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    $stmt = $conn->prepare('SELECT * FROM fach WHERE id = ?');
    $stmt->bind_param("s", $fid);
    $stmt->execute();
    $result = $stmt->get_result()->fetch_assoc();
    $conn->close();
    return $result;
}

//Alle Daten zu Übung mit fach_id und uebungs_id
function query_uebung_fid_uid($fid, $uid) {
    global $host, $username, $password, $database;
    $conn = new mysqli($host, $username, $password, $database);

    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    $stmt = $conn->prepare('SELECT * FROM uebung WHERE fachID = ? AND id = ?');
    $stmt->bind_param("ss", $fid, $uid);
    $stmt->execute();
    $result = $stmt->get_result()->fetch_assoc();
    $conn->close();
    return $result;
}

//Ermittle fachID zu uebung
function db_query_uebung_fach($uid) {
    global $host, $username, $password, $database;
    $conn = new mysqli($host, $username, $password, $database);

    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    $stmt = $conn->prepare('SELECT fachID FROM uebung WHERE id = ?');
    $stmt->bind_param("s",$uid);
    $stmt->execute();
    $result = $stmt->get_result()->fetch_assoc();
    $conn->close();
    return $result;
}

//Updatet den Datenbank Eintrag mit neuen Texten
function update_editable_contents($uid, $explanation_box, $tips_box) {
    global $host, $username, $password, $database;
    $conn = new mysqli($host, $username, $password, $database);

    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    $stmt = $conn->prepare('UPDATE uebung SET explanation_box = ?, tips_box = ? WHERE id = ?;');
    $stmt->bind_param("sss", $explanation_box, $tips_box, $uid);
    $stmt->execute();
    $conn->close();
}

//Erstelle ein neues Fach
function db_insert_new_fach($name, $dir_name, $symbol, $kachelfarbe) {
    global $host, $username, $password, $database;
    $conn = new mysqli($host, $username, $password, $database);

    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    $stmt = $conn->prepare('INSERT INTO fach (name, dir_name, symbol, kachelfarbe) VALUES (?, ?, ?, ?);');
    $stmt->bind_param("ssss", $name, $dir_name, $symbol, $kachelfarbe);
    $stmt->execute();
    $conn->close();
}

function db_update_fach($fid, $name, $dir_name, $symbol, $kachelfarbe) {
    global $host, $username, $password, $database;
    $conn = new mysqli($host, $username, $password, $database);

    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    $stmt = $conn->prepare('UPDATE fach SET name = ?, symbol = ?, kachelfarbe = ? WHERE id = ?');
    $stmt->bind_param("ssss", $name, $symbol, $kachelfarbe, $fid);
    $stmt->execute();
    $conn->close();
}

function db_delete_fach($fid) {
    global $host, $username, $password, $database;
    $conn = new mysqli($host, $username, $password, $database);

    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    $stmt = $conn->prepare('DELETE FROM fach WHERE id = ?');
    $stmt->bind_param("s", $fid);
    $stmt->execute();
    $conn->close();
}

function db_insert_new_uebung($fid, $name, $file_name, $beschreibung, $symbol, $kachelfarbe) {
    global $host, $username, $password, $database;
    $conn = new mysqli($host, $username, $password, $database);

    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    $fid_int = (int)$fid;
    $explanation_box = "";
    $tips_box = "";

    $stmt = $conn->prepare('INSERT INTO uebung (name, file_name, beschreibung, symbol, kachelfarbe, fachID, explanation_box, tips_box) VALUES (?, ?, ?, ?, ?, ?, ?, ?)');
    $stmt->bind_param("sssssiss", $name, $file_name, $beschreibung, $symbol, $kachelfarbe, $fid_int, $explanation_box, $tips_box);
    $stmt->execute();
    $conn->close();
}

function db_update_uebung($id, $name, $file_name, $beschreibung, $symbol, $kachelfarbe, $fachID) {
    global $host, $username, $password, $database;
    $conn = new mysqli($host, $username, $password, $database);

    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    $fid_int = (int)$fachID;

    $stmt = $conn->prepare('UPDATE uebung SET name = ?, file_name = ?,beschreibung = ?, symbol = ?, kachelfarbe = ?, fachID = ? WHERE id = ?;');
    $stmt->bind_param("sssssis", $name, $file_name, $beschreibung, $symbol, $kachelfarbe, $fachID, $id);
    $stmt->execute();
    $conn->close();
}

function db_delete_uebung($fid) {
    global $host, $username, $password, $database;
    $conn = new mysqli($host, $username, $password, $database);

    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    $stmt = $conn->prepare('DELETE FROM uebung WHERE id = ?');
    $stmt->bind_param("s", $fid);
    $stmt->execute();
    $conn->close();
}