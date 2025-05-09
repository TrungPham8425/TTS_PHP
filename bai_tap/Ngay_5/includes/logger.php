<?php
function log_action($action, $ip, $timestamp, $type = 'general')
{
    $date = date('Y-m-d'); // Lấy ngày hiện tại với định dạng DD-MM-YYYY
    $log_file = "logs/log_$date.txt"; // Tên file log theo ngày
    $log_entry = "[$timestamp] IP: $ip - Action: $action - Type: $type\n"; // Dòng log mới

    // Ghi log vào cuối file, tự động tạo file nếu chưa tồn tại
    file_put_contents($log_file, $log_entry, FILE_APPEND | LOCK_EX);
}
