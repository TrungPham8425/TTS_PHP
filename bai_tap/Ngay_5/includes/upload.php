<!-- Hàm xử lý thêm file  -->
<?php
function handle_upload($file)
{
    // Kích thước tối đa của file 2MB
    $max = 2 * 1024 * 1024;
    // Định dạng file hợp lệ
    $allowed_types = ['image/jpeg', 'image/png', 'application/pdf'];
    // Thư mục upload
    $upload_dir = 'uploads/';
    // Kiểm tra lỗi upload
    if ($file['error'] !== UPLOAD_ERR_OK) {
        return ['success' => false, 'error' => 'Lỗi khi upload file.'];
    }

    // Kiểm tra kích thước
    if ($file['size'] > $max) {
        return ['success' => false, 'error' => 'File vượt quá 2MB.'];
    }

    // Kiểm tra định dạng
    $file_type = mime_content_type($file['tmp_name']);
    if (!in_array($file_type, $allowed_types)) {
        return ['success' => false, 'error' => 'Định dạng không hợp lệ. Chỉ chấp nhận JPG, PNG, PDF.'];
    }

    // Đổi tên file để tránh trùng
    $ext = pathinfo($file['name'], PATHINFO_EXTENSION);
    $new_filename = 'upload_' . time() . '.' . $ext;
    $destination = $upload_dir . $new_filename;

    // Di chuyển file
    if (move_uploaded_file($file['tmp_name'], $destination)) {
        return ['success' => true, 'filename' => $new_filename];
    } else {
        return ['success' => false, 'error' => 'Không thể lưu file.'];
    }
}
