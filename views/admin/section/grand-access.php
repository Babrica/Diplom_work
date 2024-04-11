<?php

use yii\helpers\Html;
use app\models\SectionAccess; // Предположим, что ваша модель для таблицы с отношением называется UserSectionAccess

$this->title = 'Предоставить доступ к разделу';

// Проверяем наличие необходимых параметров в запросе
if (isset($_GET['sectionId']) && isset($_GET['userId'])) {
    $sectionId = $_GET['sectionId'];
    $userId = $_GET['userId'];

    // Создаем новую запись в таблице
    $access = new SectionAccess();
    $access->section_id = $sectionId;
    $access->user_id = $userId;

    // Сохраняем запись в базе данных
    if ($access->save()) {
        // Если запись успешно сохранена, выводим сообщение об успешном предоставлении доступа
        echo 'Доступ к разделу успешно предоставлен для пользователя с ID ' . $userId;
    } else {
        // Если произошла ошибка при сохранении записи, выводим сообщение об ошибке
        echo 'Произошла ошибка при предоставлении доступа к разделу';
    }
} else {
    // Если не переданы обязательные параметры, выводим сообщение об ошибке
    echo 'Отсутствуют обязательные параметры: sectionId и userId';
}

?>