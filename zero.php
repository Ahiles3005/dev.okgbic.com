<?php
$host = 'localhost'; // адрес сервера 
$database = 'okgbi_db'; // имя базы данных
$user = 'okgbi'; // имя пользователя
$password = 'R5e4D9q4'; // пароль
//
// подключаемся к серверу
$link = mysqli_connect($host, $user, $password, $database) 
    or die("Ошибка " . mysqli_error($link));
 
// выполняем операции с базой данных
$query ="DELETE FROM `b_catalog_price` WHERE  `PRICE` =0";
$result = mysqli_query($link, $query) or die("Ошибка " . mysqli_error($link)); 
if($result)
{
    echo "Выполнение запроса прошло успешно";
}
 
// закрываем подключение
mysqli_close($link);
?>