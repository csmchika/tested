<?php


function dbconnect($dbname="guides", $host="localhost", $username="root", $pass="root"): PDO
{
    return new PDO("mysql:dbname=$dbname;host=$host", "$username", "$pass");
}


function createdbTable($db)
{
    $sql_text = file_get_contents("createdb.sql");
    $db->exec("$sql_text");
    save_db($db);
    echo "<a href='file.csv' download>". "Скачать" . "</a>";
}


function save_db($db)
{
    $temp = $_FILES["file"]["tmp_name"];
    $csvAsArray = array_map('str_getcsv', explode("\r\n", file_get_contents($temp)));
    $lst = $csvAsArray;
    array_push($lst[0], "ERROR");
    for ($i = 1; $i < count($csvAsArray); $i++) {
        if (preg_match("([\d.a-zA-ZА-Яа-я\-]*)", $csvAsArray[$i][1]) && $csvAsArray[$i][1] != "")  {

            $sth = $db->prepare("SELECT * FROM `guides`.`guide` WHERE `id` = ?");
            $sth->execute(array($csvAsArray[$i][0]));
            $is_db = $sth->fetch(PDO::FETCH_ASSOC);
            if (empty($is_db)){
                $sth_ins = $db->prepare("INSERT INTO `guides`.`guide` (`id`, `name`) VALUES (?, ?)");
                $sth_ins->execute(array($csvAsArray[$i][0], trim($csvAsArray[$i][1])));
            } else {
                $sth_up = $db->prepare("UPDATE `guides`.`guide` SET `name` = ? WHERE `guide`.`id` = ?");
                $sth_up->execute(array(trim($csvAsArray[$i][1]), $csvAsArray[$i][0]));
            }
            array_push($lst[$i], "");
        }
        else {
            $format = 'Недопустимый символ %s в поле Название';
            if ($csvAsArray[$i][1] == "") {
                array_push($lst[$i], sprintf($format, 'Пробел'));
            } else {
                foreach ($csvAsArray[$i][1] as $ch) {
                    if (!preg_match("([\d.a-zA-ZА-Яа-я\-]*)", $ch)) {
                        array_push($lst[$i], sprintf($format, $ch));
                    }
                }
            }

        }

    }
    $f = fopen("file.csv", "w");
    foreach ($lst as $line) {
        fputcsv($f, $line);
    }

}
ini_set('max_execution_time', '600');
createdbTable(dbconnect());
