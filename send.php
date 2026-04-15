<?php
header('Content-Type: application/json; charset=utf-8');
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
  echo json_encode(['success' => false, 'message' => 'Неверный метод запроса.'], JSON_UNESCAPED_UNICODE);
  exit;
}
function clean($value) { return trim(strip_tags($value ?? '')); }
$name = clean($_POST['name'] ?? '');
$phone = clean($_POST['phone'] ?? '');
$message = clean($_POST['message'] ?? '');
if ($phone === '') {
  echo json_encode(['success' => false, 'message' => 'Телефон обязателен.'], JSON_UNESCAPED_UNICODE);
  exit;
}
$to = 'support@drova1.com';
$subject = 'Новая заявка с сайта Дровяная компания №1';
$body = "Новая заявка с сайта:\n\n";
$body .= "Имя: " . ($name !== '' ? $name : 'Не указано') . "\n";
$body .= "Телефон: " . $phone . "\n";
$body .= "Комментарий: " . ($message !== '' ? $message : 'Не указан') . "\n";
$body .= "Дата: " . date('d.m.Y H:i:s') . "\n";
$body .= "IP: " . ($_SERVER['REMOTE_ADDR'] ?? 'unknown') . "\n";
$headers = [];
$headers[] = 'From: no-reply@' . ($_SERVER['HTTP_HOST'] ?? 'localhost');
$headers[] = 'Reply-To: ' . $to;
$headers[] = 'Content-Type: text/plain; charset=UTF-8';
$sent = mail($to, '=?UTF-8?B?' . base64_encode($subject) . '?=', $body, implode("\r\n", $headers));
echo json_encode($sent ? ['success' => true] : ['success' => false, 'message' => 'Сервер не смог отправить письмо.'], JSON_UNESCAPED_UNICODE);
