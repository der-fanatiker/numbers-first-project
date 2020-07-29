<?php

class Mail
{
    //nid - идентификатор, ntype - тип номера, htype - тип хоста
    const MAIL_ADMIN = "admin@admin.ru";
    const MAIL_TO = "demo@demo.ru";

    function __construct($link, $uid, $nid, $ntype, $htype)
    {
        $table = "";
        $number_info_text = "<table><tr><td colspan='2'><b>Информация о номере</b></td></tr>";
        $user_info_text = "<table><tr><td colspan='2'><b>Информация о пользователе</b></td></tr>";
        $htype_text = "";
        $ntype_text = "";
        switch ($ntype) {
            case "doc": {
                $table = "number";
                $ntype_text = "Номер на документ";
                break;
            }
            case "dog": {
                $table = "doc_number";
                $ntype_text = "Номер на договор";
                break;
            }
            case "rezerve": {
                $table = "rezerve_num";
                $ntype_text = "Номер зарезервирован на будущее";
                break;
            }
            default: {
                exit();
                break;
            }
        }
        switch ($htype) {
            case "1": {
                $htype_text = "Юридическое лицо 2";
                break;
            }
            case "2": {
                $htype_text = "Юридическое лицо 2";
                break;
            }
            default: {
                exit();
                break;
            }
        }
        //Заполняем инфу о номере
        $number_result = $link->query("SELECT * FROM " . $table . " WHERE id='" . $nid . "'");
        $number_info = $number_result->fetch_assoc();
        $number_info_text .= "<tr><td>Номер</td><td>" . $number_info["number"] . "</td></tr>";
        if ($ntype == "dog") {
            $number_info_text .= "<tr><td>Комментарий</td><td>" . $number_info["comment"] . "</td><tr>";
        }
        $number_info_text .= "<tr><td>Дата выдачи</td><td>" . $number_info["date"] . "</td></tr></table>";
        //Заполняем инфу о пользователе
        $user_result = $link->query("SELECT name,organization FROM users WHERE id='" . $uid . "'");
        $user_info = $user_result->fetch_assoc();
        $user_info_text .= "<tr><td>Ф.И.О</td><td>" . $user_info["name"] . "</td></tr><tr><td>Организация</td><td>" . $user_info["organization"] . "</td></tr><tr><td>Территория</td><td>" . $htype_text . "</td></tr></table>";
        $message = $number_info_text . "<br>" . $user_info_text;
        $headers = "From: " . Mail::MAIL_ADMIN . "\nContent-Type:text/html; charset=windows-1251\n";
        $to = Mail::MAIL_TO;
        $subject = $ntype_text . " номер-" . $number_info['number'] . "(" . $number_info['date'] . ") Территория " . $htype_text;
        //mail($to,$subject,$message,$headers);
    }
}

?>