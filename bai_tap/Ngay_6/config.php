<?php

// Mang chua thong tin va sach
$books = [
    [
        "title" => "Clean Code",
        "quantity" => 2,
        "price" => 150000
    ],
    [
        "title" => "Design Patterns",
        "quantity" => 1,
        "price" => 200000
    ]

];

// Cac gia tri co dinh
$JSON_FILE = 'cart_data.json'; //file json
$FILE_LOG = 'log.txt'; //file chua cac hanh dong, cac loi
$COOKIE_EXPIRE_DAYS = 3; // So ngay thoi han cookies
$APP_NAME = "Book Shop"; // ten ung dung
$CURRENCY = 'VND'; // Don vi tien te
$MAX_QUANTITY = 100; //So luong toi da
