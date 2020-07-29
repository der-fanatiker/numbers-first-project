<?php
session_start();
if (isset($_SESSION["legal3"]["status"]) && ($_SESSION["legal3"]["status"] == "admin")) {
    if (isset($_GET["module"])) {
        switch ($_GET["module"]) {
            case "users": {
                //Управление пользователями
                if (isset($_GET["action"])) {
                    include_once("classes/adminClass.php");
                    $admin = new admin();
                    switch ($_GET["action"]) {
                        case "users_show": {
                            include_once("classes/portalClass.php");
                            $portal = new portal();
                            include_once "templates/admin.php";
                            break;
                        }
                        case "user_edit": {
                            if (!isset($_GET['error'])) {
                                include_once "templates/header.html";
                                $admin->userForm($_GET['id']);
                                include_once "templates/footer.html";
                            } else {
                                include_once "templates/header.html";
                                $admin->userErrorFormEdit($_GET['id']);
                                include_once "templates/footer.html";
                            }
                            break;
                        }
                        case "user_delete": {
                            $admin->userDelete($_GET['id']);
                            break;
                        }
                        case "user_rep": {
                            if (!isset($_GET["type"])) {
                                //Предлагаем выбрать два варианта отчетов по документам и по договорам
                                include_once("templates/header.html");
                                echo "Просмотр отчета для пользователя по полученным номерам на документы<a href='admin.php?module=users&action=user_rep&type=doc&id=" . $_GET["id"] . "'> здесь</a><br>Просмотр отчета пользователя по полученным номерам на договора<a href='admin.php?module=users&action=user_rep&type=dog&id=" . $_GET["id"] . "'> здесь</a>";
                                include_once("templates/footer.html");
                            } else {
                                switch ($_GET["type"]) {
                                    case "dog": {
                                        if (isset($_GET['page'])) {
                                            $admin->page = $_GET['page'];
                                        } else {
                                            $admin->page = 0;
                                        }
                                        $admin->where = "WHERE uid='" . $_GET['id'] . "' ORDER BY number DESC ";
                                        $admin->href = "admin.php?module=users&action=user_rep&type=dog&id=" . $_GET['id'];
                                        $admin->limit = "LIMIT " . admin::page * $admin->page . "," . admin::page;
                                        include_once "templates/header.html";
                                        $admin->userReportWaybill($_GET['id']);
                                        include_once "templates/footer.html";
                                        break;
                                    }
                                    case "doc": {
                                        if (isset($_GET['page'])) {
                                            $admin->page = $_GET['page'];
                                        } else {
                                            $admin->page = 0;
                                        }

                                        if (isset($_GET["filter"])) {
                                            //print_r($_GET);
                                            switch ($_GET["filter"]) {
                                                case "number": {
                                                    $admin->where = "WHERE id='" . $_GET['number'] . "'";
                                                    $admin->href = "admin.php?module=users&action=user_rep&type=doc&filter=number&number=" . $_GET['number'] . "&id=" . $_GET['id'];
                                                    break;
                                                }
                                                case "date": {
                                                    $admin->where = "WHERE date LIKE '" . $_GET['date'] . "%' AND uid='" . $_GET['id'] . "' ORDER BY number DESC";
                                                    $admin->href = "admin.php?module=users&action=user_rep&type=doc&filter=date&date=" . $_GET['date'] . "&id=" . $_GET['id'];
                                                    break;
                                                }
                                                default: {
                                                    echo "Был задан неправильный фильтр!";
                                                    exit();
                                                    break;
                                                }
                                            }
                                        } else {
                                            $admin->where = "WHERE uid='" . $_GET['id'] . "' ORDER BY number DESC";
                                            $admin->href = "admin.php?module=users&action=user_rep&type=doc&id=" . $_GET['id'];
                                        }
                                        $admin->limit = "LIMIT " . admin::page * $admin->page . "," . admin::page;
                                        include_once "templates/header.html";
                                        $admin->userReport($_GET['id']);
                                        include_once "templates/footer.html";
                                        break;
                                    }
                                }
                            }
                            break;
                        }
                        case "user_new": {
                            if (!isset($_GET['error'])) {
                                include_once "templates/header.html";
                                $admin->userForm();
                                include_once "templates/footer.html";
                            } else {
                                include_once "templates/header.html";
                                $admin->userErrorForm();
                                include_once "templates/footer.html";
                            }
                            break;
                        }
                        case "user_save": {
                            $admin->userSave($_POST);
                            break;
                        }
                        case "user_create": {
                            $admin->userNew($_POST);
                            break;
                        }
                        default: {
                            break;
                        }
                    }
                } else {

                }
                break;
            }
            case "reserve": {
                include_once("classes/adminClass.php");
                $admin = new admin();
                //Управление пользователем резерв
                if (isset($_GET["action"])) {
                    switch ($_GET["action"]) {
                        // ************
                        case "search": {
                            if (isset($_GET['number'])) {
                                include_once "templates/header.html";
                                $admin->searchReserve($_GET['number']);
                                include_once "templates/footer.html";
                            } else {
                                // Or show error
                                //header('Location: admin.php?module=reserve');
                                echo "Вам нужно указать номер для поиска";
                            }
                            break;
                        }
                        // ************
                        case "more": {
                            $admin->rezerveMore();
                            break;
                        }
                        case "give": {
                            if (isset($_GET['page'])) {
                                $admin->page = $_GET['page'];
                            } else {
                                $admin->page = 0;
                            }
                            if (isset($_GET['filter'])) {
                                switch ($_GET['filter']) {
                                    case "name": {
                                        $admin->where = " WHERE id='" . $_GET['name'] . "'";
                                        $admin->href = "admin.php?module=reserve&action=give&id=" . $_GET['id'] . "&filter=name&name=" . $_GET['name'];
                                        break;
                                    }
                                    case "login": {
                                        $admin->where = " WHERE id='" . $_GET['login'] . "'";
                                        $admin->href = "admin.php?module=reserve&action=give&id=" . $_GET['id'] . "&filter=login&name=" . $_GET['login'];
                                        break;
                                    }
                                    case "org": {
                                        $admin->where = " WHERE organization LIKE '%" . $_GET['org'] . "%'";
                                        $admin->href = "admin.php?module=reserve&action=give&id=" . $_GET['id'] . "&filter=org&org=" . $_GET['org'];
                                        break;
                                    }
                                    default: {
                                        //Ошибочка, регистрируем её и завершаем выполнение скрипта
                                        echo "Неверный фильтр";
                                        exit();
                                        break;
                                    }
                                }
                            } else {
                                $admin->where = " WHERE login NOT IN('rezerv') ORDER BY name";
                                $admin->href = "admin.php?module=reserve&action=give&id=" . $_GET['id'];
                            }
                            $admin->limit = "LIMIT " . admin::page * $admin->page . "," . admin::page;
                            include_once "templates/header.html";
                            $admin->rezerveGive($_GET['id']);
                            include_once "templates/footer.html";
                            break;
                        }
                        case "num": {
                            $admin->rezerveNum($_GET['uid'], $_GET['nid']);
                            break;
                        }
                        case "this": {
                            $admin->rezerveNumber($_GET['id'], $_GET['mod']);
                            break;
                        }
                        case "daily": {
                            include_once "templates/header.html";
                            if (!isset($_GET['err'])) {
                                if (isset($_GET['upd'])) {
                                    $admin->dailyReserve($admin->getDailyNumbers(), true);
                                } else {
                                    $admin->dailyReserve($admin->getDailyNumbers());
                                }
                            } else {
                                $admin->dailyReserve($admin->getDailyNumbers(), false, true);
                            }
                            include_once "templates/footer.html";
                            break;
                        }
                        case "daily_new": {
                            if (isset($_POST['number'])) {
                                if ($admin->setDailyNumbers($_POST['number'])) {
                                    header('Location:admin.php?module=reserve&action=daily&upd=1');
                                } else {
                                    header('Location:admin.php?module=reserve&action=daily&err=1');
                                }
                            } else {
                                header('Location:admin.php?module=reserver&action=daily&err=1');
                            }
                            break;
                        }
                        default: {
                            break;
                        }
                    }
                } else {
                    include_once "templates/header.html";
                    if (isset($_GET['page'])) {
                        $admin->page = $_GET['page'];
                    } else {
                        $admin->page = 0;
                    }

                    $admin->where = " WHERE uid=(SELECT id FROM users WHERE login='rezerv') ";
                    $admin->limit = " ORDER BY number DESC LIMIT " . admin::page * $admin->page . "," . admin::page;
                    $admin->href = "admin.php?module=reserve";
                    $admin->rezerveMenu();
                    include_once "templates/footer.html";
                }
                break;
            }
            case "setup": {
                include_once "classes/adminClass.php";
                $admin = new admin();
                if (!isset($_GET["action"])) {
                    //Показываем главную страницу настроек
                    include_once "templates/header.html";
                    $admin->managementStart();
                    include_once "templates/footer.html";
                } else {
                    switch ($_GET["action"]) {
                        case "new": {
                            $admin->newNumber($_POST['firstnumber'], $_SESSION['legal3']['id']);
                            break;
                        }
                        case "waybill": {
                            include_once "templates/header.html";
                            $admin->managementWaybill();
                            include_once "templates/footer.html";
                            break;
                        }
                        case "newwaybill": {
                            $admin->newWaybillNumber($_POST['firstnumber'], $_SESSION['legal3']['id']);
                            break;
                        }
                        default: {
                            break;
                        }
                    }
                }
                //Настройки приложения
                break;
            }
            case "number": {
                if (isset($_GET["action"])) {
                    switch ($_GET["action"]) {
                        case "document": {
                            include_once("classes/portalClass.php");
                            $portal = new portal();
                            //include_once "templates/admin.php";
                            $portal = new portal();
                            include_once "templates/header.html";
                            if (isset($_GET['page'])) {
                                $portal->page = $_GET['page'];
                            } else {
                                $portal->page = 0;
                            }
                            $portal->where = " WHERE uid='" . $_SESSION['legal3']['id'] . "'";
                            $portal->limit = " LIMIT " . portal::page * $portal->page . "," . portal::page;
                            $portal->href = "admin.php?module=number&action=document";
                            if (isset($_GET["nid"])) {
                                $portal->showGetForm($_SESSION['legal3']['id'], $_GET["nid"]);
                            } else {
                                $portal->showGetForm($_SESSION['legal3']['id']);
                            }
                            include_once "templates/footer.html";
                            break;

                        }
                        case "waybill": {
                            include_once("classes/portalClass.php");

                            if (isset($_GET["nid"])) {
                                //Пользователь получил номерок и мы можем показать ему чего он получил
                                $portal = new portal();
                                if (isset($_GET['page'])) {
                                    $portal->page = $_GET['page'];
                                } else {
                                    $portal->page = 0;
                                }
                                $portal->where = " WHERE uid='" . $_SESSION['legal3']['id'] . "'";
                                $portal->limit = " LIMIT " . portal::page * $portal->page . "," . portal::page;
                                $portal->href = "admin.php?module=number&action=waybill";
                                include_once "templates/header.html";
                                $portal->getDocNumberForm($_SESSION["legal3"]["id"], 0, $_GET["nid"]);
                                $portal->makeNavigationDoc();
                                include_once "templates/footer.html";

                            } else {
                                //Диалог получения номера на накладную
                                if (isset($_GET["error"])) {
                                    $portal = new portal();
                                    include_once "templates/header.html";
                                    $portal->getDocNumberForm($_SESSION["legal3"]["id"], $_GET["error"]);
                                    include_once "templates/footer.html";
                                } else {
                                    $portal = new portal();
                                    if (isset($_GET['page'])) {
                                        $portal->page = $_GET['page'];
                                    } else {
                                        $portal->page = 0;
                                    }
                                    $portal->where = " WHERE uid='" . $_SESSION['legal3']['id'] . "'";
                                    $portal->limit = " LIMIT " . portal::page * $portal->page . "," . portal::page;
                                    $portal->href = "admin.php?module=number&action=waybill";
                                    include_once "templates/header.html";
                                    $portal->getDocNumberForm($_SESSION["legal3"]["id"]);
                                    $portal->makeNavigationDoc();
                                    include_once "templates/footer.html";
                                }
                            }
                            break;
                        }
                        case "getd": {
                            include_once("classes/portalClass.php");
                            $portal = new portal();
                            $portal->getNum($_SESSION['legal3']['id'],
                                "Location:admin.php?module=number&action=document");
                            break;
                        }
                        case "getw": {
                            include_once("classes/portalClass.php");

                            $portal = new portal();
                            $result = $portal->getDocNumber($_SESSION["legal3"]["id"], $_POST["comment"]);
                            //print_r($result["success"]);
                            if ($result["success"] == false) {
                                //Значит пользователь не ввел комментарий к покупке!
                                header("Location:admin.php?module=number&action=waybill&error=1");
                            } else {
                                //Все отлично, можно его даже похвалить и переслать снова на страничку получения номера на накладную и подсветить его и
                                //завтра 25.10.2007 нужно сделать отображение названия модуля для пользователя
                                header("Location:admin.php?module=number&action=waybill&nid=" . $result["id"]);
                            }
                            break;
                        }
                        default: {
                            break;
                        }
                    }
                } else {
                    header("Location:admin.php?module=number&actio=document");
                }
                break;
            }
            case "report": {
                include_once "classes/adminClass.php";
                $admin = new admin();
                if (isset($_GET["action"])) {
                    switch ($_GET["action"]) {
                        case "download": {

                            $admin->downloadReport();
                            break;
                        }
                        case "load": {
                            $admin->downloadWaybill();
                            break;
                        }
                        case "waybill": {
                            //Выводим отчет по номерам на договора
                            include_once "templates/header.html";
                            if (isset($_GET['page'])) {
                                $admin->page = $_GET['page'];
                            } else {
                                $admin->page = 0;
                            }
                            if (isset($_GET["filter"])) {

                            } else {
                                $admin->where = "";
                                $admin->href = "admin.php?module=report&action=waybill";
                            }
                            $admin->limit = "LIMIT " . admin::page * $admin->page . "," . admin::page;
                            $admin->waybillReport();
                            include_once "templates/footer.html";
                            break;
                        }
                        default: {
                            break;
                        }
                    }
                } else {
                    include_once "templates/header.html";
                    if (isset($_GET['page'])) {
                        $admin->page = $_GET['page'];
                    } else {
                        $admin->page = 0;
                    }
                    if (isset($_GET['filter'])) {
                        switch ($_GET['filter']) {
                            case "number": {
                                $admin->where = " WHERE number.number=" . $_GET['number'];
                                $admin->href = "";
                                break;
                            }
                            case "date": {
                                $admin->where = " WHERE number.date LIKE '" . $_GET['date'] . "%'";
                                $admin->href = "admin.php?module=report&filter=date&date=" . $_GET['date'];
                                break;
                            }
                            case "name": {
                                $admin->where = " WHERE number.uid = '" . $_GET['name'] . "'";
                                $admin->href = "admin.php?module=report&filter=name&name=" . $_GET["name"];
                                break;
                            }
                            case "org": {
                                $admin->where = " WHERE number.uid IN (SELECT id FROM users WHERE organization='" . $_GET['org'] . "')";
                                $admin->href = "admin.php?module=report&filter=org&org=" . $_GET['org'];
                                break;
                            }
                            default: {
                                echo "был задан неккоректный парамметр фильтра";
                                exit();
                                break;
                            }
                        }
                    } else {
                        $admin->where = "";
                        $admin->href = "admin.php?module=report";
                    }
                    $admin->limit = "LIMIT " . admin::page * $admin->page . "," . admin::page;
                    $admin->managementReport();
                    include_once "templates/footer.html";
                }
                //Получение номеров
                //Отчеты по полученным номерам
                break;
            }
            case "future": {
                include_once "classes/adminClass.php";
                $admin = new admin();
                if (isset($_GET["action"])) {
                    switch ($_GET["action"]) {
                        case "get": {
                            $admin->rezerveForFuture($_POST["number"], $_POST["date"], $_SESSION["legal3"]["id"]);
                            break;
                        }
                        case "show": {
                            include_once "templates/header.html";
                            $admin->futureShow();
                            include_once "templates/footer.html";
                            break;
                        }
                    }
                } else {
                    if (isset($_GET["error"])) {
                        //Выводим форму получения резервного номера и показываем ошибку
                        include_once "templates/header.html";
                        $admin->futureMenu($_GET["error"], 0);
                        include_once "templates/footer.html";
                    } else {
                        if (isset($_GET["fid"])) {
                            //Номер человек получил, показываем его
                            include_once "templates/header.html";
                            $admin->futureMenu(0, $_GET["fid"]);
                            include_once "templates/footer.html";
                        } else {
                            //показываем форму получения номера
                            include_once "templates/header.html";
                            $admin->futureMenu(0, 0);
                            include_once "templates/footer.html";
                        }
                    }
                }

                break;
            }
            case "archive": {
                include_once "classes/adminClass.php";
                $admin = new admin();
                if (isset($_GET["action"])) {
                    switch ($_GET["action"]) {
                        case "search": {
                            if (isset($_GET["what"]) && (is_numeric($_GET["what"]))) {
                                //Ищем заданный номер
                                include_once "templates/header.html";
                                $admin->search($_GET["what"], true);
                                include_once "templates/footer.html";
                            } else {
                                //Можно просто вывести диалог поиска номера
                                include_once "templates/header.html";
                                $admin->search($_GET["what"], true);
                                include_once "templates/footer.html";
                            }
                            break;
                        }
                        case "users": {
                            if (isset($_GET["id"]) && (is_numeric($_GET["id"]))) {
                                #Показываем пользоватей и присваиваем пользователю номер
                                if (isset($_GET['page'])) {
                                    $admin->page = $_GET['page'];
                                } else {
                                    $admin->page = 0;
                                }
                                if (isset($_GET['filter'])) {
                                    switch ($_GET['filter']) {
                                        case "name": {
                                            $admin->where = " WHERE id='" . $_GET['name'] . "'";
                                            $admin->href = "admin.php?module=archive&action=users&id=" . $_GET['id'] . "&filter=name&name=" . $_GET['name'];
                                            break;
                                        }
                                        case "login": {
                                            $admin->where = " WHERE id='" . $_GET['login'] . "'";
                                            $admin->href = "admin.php?module=archive&action=users&id=" . $_GET['id'] . "&filter=login&name=" . $_GET['login'];
                                            break;
                                        }
                                        case "org": {
                                            $admin->where = " WHERE organization LIKE '%" . $_GET['org'] . "%'";
                                            $admin->href = "admin.php?module=archive&action=users&nid=" . $_GET['id'] . "&filter=org&org=" . $_GET['org'];
                                            break;
                                        }
                                        default: {
                                            //Ошибочка, регистрируем её и завершаем выполнение скрипта
                                            echo "Неверный фильтр";
                                            exit();
                                            break;
                                        }
                                    }
                                } else {
                                    $admin->where = " ORDER BY name";
                                    $admin->href = "admin.php?module=archive&action=users&id=" . $_GET['id'];
                                }
                                $admin->limit = "LIMIT " . admin::page * $admin->page . "," . admin::page;
                                include_once "templates/header.html";
                                $admin->archiveShowUsers($_GET['id']);
                                include_once "templates/footer.html";
                            } else {
                                header("Location:admin.php?module=archive");
                            }
                            break;
                        }
                        case "give": {
                            if (isset($_GET["id"]) && (isset($_GET["uid"]))) {
                                include_once "templates/header.html";
                                $admin->archiveChangeUser($_GET["uid"], $_GET["id"]);
                                include_once "templates/footer.html";
                            } else {
                                header("Location:admin.php?module=archive");
                            }
                            break;
                        }
                        case "calendar": {
                            if (isset($_GET["year"]) && (isset($_GET["m"]))) {
                                if ($_GET["m"] < 10) {
                                    $m = "0" . $_GET["m"];
                                } else {
                                    $m = $_GET["m"];
                                }
                                if (isset($_GET['page'])) {
                                    $admin->page = $_GET['page'];
                                } else {
                                    $admin->page = 0;
                                }
                                if (isset($_GET["filter"])) {
                                    switch ($_GET["filter"]) {
                                        case "name": {
                                            $admin->where = " WHERE number_archive.uid='" . $_GET['name'] . "' AND number_archive.year = " . $_GET["year"] . " AND month=" . (int)$m;
                                            $admin->href = "admin.php?module=archive&action=calendar&id=" . $_GET['id'] . "&filter=name&name=" . $_GET['name'] . "&year=" . $_GET["year"] . "&m=" . $_GET["m"];
                                            break;
                                        }
                                        case "org": {
                                            $admin->where = " WHERE number_archive.uid IN (SELECT id FROM users WHERE users.organization LIKE '%" . $_GET['org'] . "%') AND number_archive.year = " . $_GET["year"] . " AND month=" . (int)$m;
                                            $admin->href = "admin.php?module=archive&action=calendar&filter=org&org=" . $_GET['org'] . "&year=" . $_GET["year"] . "&m=" . $_GET["m"];
                                            break;
                                        }
                                        case "number": {
                                            $admin->where = " WHERE number_archive.number=" . $_GET['number'] . " AND number_archive.year = " . $_GET["year"] . " AND month=" . (int)$m;
                                            $admin->href = "";
                                            break;
                                        }
                                        default: {
                                            header("Location:admin.php?module=archive");
                                            break;
                                        }
                                    }
                                } else {
                                    $admin->where = "WHERE number_archive.year = " . $_GET["year"] . " AND month=" . (int)$m;
                                    $admin->href = "admin.php?module=archive&action=calendar&m=" . $_GET["m"] . "&year=" . $_GET["year"];
                                }
                                $admin->limit = "LIMIT " . admin::page * $admin->page . "," . admin::page;
                                include_once "templates/header.html";
                                $admin->archiveShowNumbers($_GET["year"], $m);
                                include_once "templates/footer.html";
                            } else {
                                header("Location:admin.php?module=archive");
                            }
                        }
                        default: {
                            break;
                        }
                    }
                } else {
                    include_once "templates/header.html";
                    $admin->archive();
                    include_once "templates/footer.html";
                }
                break;
            }
            case 'download': {

                include_once "classes/adminClass.php";

                $admin = new admin();

                $admin->createArchiveDownload($_GET);

                break;
            }
        }
    } else {
        header("Location:index.php");
    }
} else {
    header("Location:index.php");
}
?>
