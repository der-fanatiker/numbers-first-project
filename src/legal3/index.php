<?php
session_start();
if (isset($_SESSION["legal3"])) {
    include_once("classes/authClass.php");
    //Территория пользователя, который ввел логин и пароль и они корректные
    if (isset($_GET['action'])) {
        switch ($_GET['action']) {
            case "logout": {
                $auth = new auth();
                $auth->logout();
                break;
            }
            case "": {
                break;
            }
            default: {
                break;
            }
        }
    } else {
        switch ($_SESSION["legal3"]["status"]) {
            case "user": {
                //Отправляем пользователя на пользовательский файл
                header("Location:users.php?module=document");
                break;
            }
            case "admin": {
                //Отправляем пользователя на администраторский  файл
                header("Location:admin.php?module=users&action=users_show");
                break;
            }
            default: {
                //Это тоже интересный вариант, в случае когда сессия запущена но параметры в ее переменных некорректные
                break;
            }
        }
    }
} else {
    include_once("classes/authClass.php");
    //Выводим форму ввода логина и пароля
    if (isset($_GET["action"])) {
        switch ($_GET["action"]) {
            case "login": {
                if (isset($_POST["login"]) && isset($_POST["password"])) {
                    $auth = new auth();
                    //Если access= 0,то все нормально и метод login сам отправляет header для перезагрузки страницы
                    $access = $auth->login($_POST["login"], $_POST["password"]);
                    if ($access == 1) {
                        //Уууппссс, ошибочка
                        $auth->form($access);

                    } else {
                        header("Location:index.php");
                    }
                } else {
                    //Эта ситуация возможна, когда в командной строке тупо напечатали index.php?action=login
                    $auth = new auth();
                    $auth->form(1);
                }
                break;
            }
            default: {
                //Неккоректный параметр  action, значит что-то нужно сделать
                unset($_GET["action"]);
                //header("Location: index.php");
                break;
            }
        }
    } else {
        //Выводим форму регистрации
        $auth = new auth();
        $auth->form();
    }

}
?>
