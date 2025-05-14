<?php
// exceptions.php
require_once 'config.php';

// Định nghĩa custom exception class cho giỏ hàng
class CartException extends Exception
{
    public function __construct($message, $code = 0, Exception $previous = null)
    {
        parent::__construct($message, $code, $previous);
        // Ghi log lỗi vào file nếu $LOG_FILE hợp lệ
        global $LOG_FILE;
        if (!empty($LOG_FILE) && is_string($LOG_FILE)) {
            // Tạo file log.txt nếu chưa tồn tại
            if (!file_exists($LOG_FILE)) {
                file_put_contents($LOG_FILE, '');
            }
            $log_message = date('Y-m-d H:i:s') . " - CartException: $message\n";
            file_put_contents($LOG_FILE, $log_message, FILE_APPEND);
        }
    }
}
