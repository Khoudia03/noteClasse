<?php

function deconnexionDB() : PDO {
    static $pdo = null;
    if($pdo === null){
        $pdo = new PDO(
            "pgsql:host=localhost;port=5432;dbname=noteclasse",
            "postgres",
            "Cisse0312@"
        );
        $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE,PDO::FETCH_ASSOC);
        $pdo->setAttribute(PDO::ATTR_ERRMODE,PDO::ERRMODE_EXCEPTION);
    }
    return $pdo;
}

function query(PDO $pdo, string $sql, bool $single = true) : array {
    $query = $pdo->query($sql);
    return $single ? $query->fetch() : $query->fetchAll();
}

function prepare(PDO $pdo, string $sql, array $datas) {
    $prepare = $pdo->prepare($sql);
    $prepare->execute($datas);
    return $prepare;
}

function executeQuery(PDO $pdo, string $sql, array $datas, bool $single = true) : array {
    $stmt = prepare($pdo,$sql,$datas);
    $result = $single ? $stmt->fetch() : $stmt->fetchAll();
    return $result ?:[];
}

function executeUpdate(PDO $pdo, string $sql, array $datas) : int {
    $stmt = prepare($pdo,$sql,$datas);
    return (str_starts_with(strtoupper($sql),'INSERT')) ? $pdo->lastInsertId() : $stmt->rowCount();
}

function getAllTable(string $table) : array {
    $pdo = deconnexionDB();
    $sql = "SELECT * FROM $table";
    $tables = query($pdo,$sql,false);
    return $tables;
}