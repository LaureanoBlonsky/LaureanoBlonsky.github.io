<?php
require_once 'database.php';

# Table creation
DB::query("CREATE temporary TABLE pdowrapper (id int auto_increment primary key, name varchar(255))");

# Prepared statement multiple execution
$stmt = DB::prepare("INSERT INTO pdowrapper VALUES (NULL, ?)");
foreach (['Sam','Bob','Joe'] as $name)
{
    $stmt->execute([$name]);
}
var_dump(DB::lastInsertId());
//string(1) "3"

# Getting rows in a loop
$stmt = DB::run("SELECT * FROM pdowrapper");
while ($row = $stmt->fetch(PDO::FETCH_LAZY))
{
    echo $row['name'],",";
    echo $row->name,",";
    echo $row[1], PHP_EOL;
}
/*
Sam,Sam,Sam
Bob,Bob,Bob
Joe,Joe,Joe
*/

# Getting one row
$id  = 1;
$row = DB::run("SELECT * FROM pdowrapper WHERE id=?", [$id])->fetch();
var_export($row);
/*
array (
  'id' => '1',
  'name' => 'Sam',
)
*/

# Getting single field value
$name = DB::run("SELECT name FROM pdowrapper WHERE id=?", [$id])->fetchColumn();
var_dump($name);
//string(3) "Sam"

# Getting array of rows
$all = DB::run("SELECT name, id FROM pdowrapper")->fetchAll(PDO::FETCH_KEY_PAIR);
var_export($all);
/*
array (
  'Sam' => '1',
  'Bob' => '2',
  'Joe' => '3',
)
*/

# Update
$new = 'Sue';
$stmt = DB::run("UPDATE pdowrapper SET name=? WHERE id=?", [$new, $id]);
var_dump($stmt->rowCount());
//int(1)
