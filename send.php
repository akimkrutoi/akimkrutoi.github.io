<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Получаем и экранируем данные
    $name = htmlspecialchars(trim($_POST['name'] ?? ''));
    $email = htmlspecialchars(trim($_POST['email'] ?? ''));
    $phone = htmlspecialchars(trim($_POST['phone'] ?? ''));
    $checkin = htmlspecialchars(trim($_POST['checkin'] ?? ''));
    $checkout = htmlspecialchars(trim($_POST['checkout'] ?? ''));
    $roomType = htmlspecialchars(trim($_POST['roomType'] ?? 'standard'));
    $guests = htmlspecialchars(trim($_POST['guests'] ?? ''));
    $comment = htmlspecialchars(trim($_POST['comment'] ?? ''));

    // Преобразуем тип размещения в читаемый вид
    $roomMap = [
        'standard' => 'Обычный (10 000 ₽/сутки)',
        'lux'      => 'Люкс (15 000 ₽/сутки)',
        'banya'    => 'Баня (3 000 ₽/сеанс)'
    ];
    $roomLabel = $roomMap[$roomType] ?? $roomType;

    // Формируем тело письма
    $subject = "Новая заявка на бронирование из Архыза";
    $message = "Поступила новая заявка:\n\n";
    $message .= "Имя: $name\n";
    $message .= "Email: $email\n";
    $message .= "Телефон: $phone\n";
    $message .= "Заезд: $checkin\n";
    $message .= "Выезд: $checkout\n";
    $message .= "Тип размещения: $roomLabel\n";
    $message .= "Количество гостей: $guests\n";
    $message .= "Комментарий: $comment\n";

    // Заголовки письма
    $headers = "From: $email\r\n";
    $headers .= "Reply-To: $email\r\n";
    $headers .= "Content-Type: text/plain; charset=utf-8\r\n";

    // Отправляем письмо
    $to = "akimkrutoi1@mail.ru";
    $success = mail($to, $subject, $message, $headers);

    // Перенаправляем обратно с параметром статуса
    if ($success) {
        header('Location: index.php?status=success');
    } else {
        header('Location: index.php?status=error');
    }
    exit;
} else {
    // Если кто-то зашёл напрямую — редирект на главную
    header('Location: index.php');
    exit;
}
?>