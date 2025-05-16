<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Website Thương mại điện tử</title>
    <!-- Tích hợp Bootstrap 5 CSS từ CDN -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .product-item:hover {
            background-color: #f8f9fa;
        }

        /* Hiệu ứng hover cho sản phẩm */
        .search-box {
            max-width: 500px;
        }

        /* Giới hạn chiều rộng ô tìm kiếm */
        .cart-count {
            font-weight: bold;
            color: #dc3545;
        }

        /* Định dạng số lượng giỏ hàng */
    </style>
</head>

<body class="container my-4">
    <!-- Tiêu đề chính của trang -->
    <h1 class="mb-4">Sàn Thương mại điện tử</h1>

    <!-- Phần danh sách sản phẩm -->
    <div class="product-list mb-4">
        <h2>Danh sách sản phẩm</h2>
        <ul class="list-group" id="product-list">
            <!-- Danh sách sản phẩm sẽ được tải động bằng JavaScript -->
        </ul>
    </div>

    <!-- Phần hiển thị chi tiết sản phẩm, ẩn mặc định -->
    <div class="product-details card mb-4" id="product-details" hidden>
        <div class="card-body">
            <!-- Nội dung chi tiết sản phẩm sẽ được cập nhật bằng AJAX -->
        </div>
    </div>

    <!-- Phần hiển thị đánh giá sản phẩm, ẩn mặc định -->
    <div class="reviews card mb-4" id="reviews" hidden>
        <div class="card-body">
            <!-- Nội dung đánh giá sẽ được tải bằng AJAX -->
        </div>
    </div>

    <!-- Phần chọn thương hiệu -->
    <div class="brands mb-4">
        <h2>Chọn thương hiệu</h2>
        <div class="row g-2">
            <div class="col-md-4">
                <!-- Dropdown chọn ngành hàng -->
                <select class="form-select" id="category" onchange="loadBrands()">
                    <option value="electronics">Điện tử</option>
                    <option value="fashion">Thời trang</option>
                </select>
            </div>
            <div class="col-md-4">
                <!-- Dropdown hiển thị thương hiệu tải từ XML -->
                <select class="form-select" id="brands">
                    <!-- Tải động từ brands.xml -->
                </select>
            </div>
        </div>
    </div>

    <!-- Phần tìm kiếm sản phẩm -->
    <div class="search mb-4">
        <h2>Tìm kiếm sản phẩm</h2>
        <!-- Ô nhập từ khóa tìm kiếm -->
        <input type="text" id="search-box" class="form-control search-box" placeholder="Nhập từ khóa..." onkeyup="liveSearch()">
        <!-- Kết quả tìm kiếm hiển thị tại đây -->
        <div id="search-results" class="list-group mt-2"></div>
    </div>

    <!-- Phần bình chọn -->
    <div class="poll card mb-4">
        <div class="card-body">
            <h2 class="card-title">Bạn muốn cải thiện điều gì?</h2>
            <form id="poll-form">
                <div class="form-check">
                    <input class="form-check-input" type="radio" name="poll" value="interface" id="poll-interface">
                    <label class="form-check-label" for="poll-interface">Giao diện</label>
                </div>
                <div class="form-check">
                    <input class="form-check-input" type="radio" name="poll" value="speed" id="poll-speed">
                    <label class="form-check-label" for="poll-speed">Tốc độ</label>
                </div>
                <div class="form-check">
                    <input class="form-check-input" type="radio" name="poll" value="service" id="poll-service">
                    <label class="form-check-label" for="poll-service">Dịch vụ khách hàng</label>
                </div>
                <!-- Nút gửi bình chọn -->
                <button type="button" class="btn btn-primary mt-2" onclick="submitPoll()">Gửi</button>
            </form>
            <!-- Kết quả bình chọn hiển thị tại đây -->
            <div id="poll-results" class="mt-3"></div>
        </div>
    </div>

    <!-- Phần giỏ hàng -->
    <div class="cart mb-4">
        <h2>Giỏ hàng</h2>
        <span>Số lượng: <span class="cart-count" id="cart-count">0</span></span>
    </div>

    <script>
        // gọi hàm lấy sản phẩm và thương hiệu
        window.onload = () => {
            fetchProducts();
            loadBrands();
        };

        // Hàm lấy danh sách sản phẩm từ server
        function fetchProducts() {
            fetch('products.php')
                .then(response => response.json())
                .then(data => {
                    const productList = document.getElementById('product-list');
                    // Tạo danh sách sản phẩm với nút thêm vào giỏ
                    productList.innerHTML = data.map(product =>
                        `<li class="list-group-item product-item d-flex justify-content-between align-items-center" onclick="fetchProductDetails(${product.id})">
                            ${product.name}
                            <button class="btn btn-sm btn-success" onclick="addToCart(${product.id}); event.stopPropagation();">Thêm vào giỏ</button>
                        </li>`
                    ).join('');
                })
                .catch(error => console.error('Lỗi khi tải sản phẩm:', error));
        }

        // Hàm lấy chi tiết sản phẩm theo ID
        function fetchProductDetails(id) {
            // Bỏ ẩn phần chi tiết và đánh giá
            document.getElementById('product-details').hidden = false;
            document.getElementById('reviews').hidden = false;

            fetch(`products.php?id=${id}`)
                .then(response => response.text())
                .then(html => {
                    // Cập nhật chi tiết sản phẩm
                    document.getElementById('product-details').innerHTML = `<div class="card-body"><h2 class="card-title">Chi tiết sản phẩm</h2>${html}</div>`;
                    // Tải đánh giá sản phẩm
                    fetchReviews(id);
                })
                .catch(error => console.error('Lỗi khi tải chi tiết sản phẩm:', error));
        }

        // Hàm thêm sản phẩm vào giỏ hàng
        function addToCart(productId) {
            fetch('cart.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/x-www-form-urlencoded'
                    },
                    body: `product_id=${productId}`
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        // Cập nhật số lượng giỏ hàng
                        document.getElementById('cart-count').textContent = data.cartCount;
                    }
                })
                .catch(error => console.error('Lỗi khi thêm vào giỏ hàng:', error));
        }

        // Hàm lấy đánh giá sản phẩm
        function fetchReviews(productId) {
            fetch(`reviews.php?product_id=${productId}`)
                .then(response => response.text())
                .then(html => {
                    // Cập nhật danh sách đánh giá
                    document.getElementById('reviews').innerHTML = `<div class="card-body"><h2 class="card-title">Đánh giá sản phẩm</h2>${html}</div>`;
                })
                .catch(error => console.error('Lỗi khi tải đánh giá:', error));
        }

        // Hàm tải danh sách thương hiệu từ XML
        function loadBrands() {
            const category = document.getElementById('category').value;
            fetch(`brands.php?category=${category}`)
                .then(response => response.text())
                .then(html => {
                    // Cập nhật dropdown thương hiệu
                    document.getElementById('brands').innerHTML = html;
                })
                .catch(error => console.error('Lỗi khi tải thương hiệu:', error));
        }

        // Hàm tìm kiếm sản phẩm theo thời gian thực
        let searchTimeout;

        function liveSearch() {
            clearTimeout(searchTimeout);
            // Debounce: chờ 300ms trước khi gửi yêu cầu tìm kiếm
            searchTimeout = setTimeout(() => {
                const query = document.getElementById('search-box').value;
                fetch(`search.php?query=${encodeURIComponent(query)}`)
                    .then(response => response.text())
                    .then(html => {
                        // Cập nhật kết quả tìm kiếm
                        document.getElementById('search-results').innerHTML = html;
                    })
                    .catch(error => console.error('Lỗi khi tìm kiếm:', error));
            }, 300);
        }

        // Hàm gửi bình chọn
        function submitPoll() {
            const selected = document.querySelector('input[name="poll"]:checked');
            if (!selected) {
                alert('Vui lòng chọn một lựa chọn!');
                return;
            }
            fetch('poll.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/x-www-form-urlencoded'
                    },
                    body: `option=${selected.value}`
                })
                .then(response => response.json())
                .then(data => {
                    const results = document.getElementById('poll-results');
                    // Hiển thị kết quả bình chọn dưới dạng thanh tiến trình
                    results.innerHTML = Object.entries(data.percentages)
                        .map(([option, percentage]) =>
                            `<div class="progress mb-2">
                                <div class="progress-bar" style="width: ${percentage}%">${option}: ${percentage}%</div>
                            </div>`
                        ).join('');
                })
                .catch(error => console.error('Lỗi khi gửi bình chọn:', error));
        }
    </script>
    <!-- Tích hợp Bootstrap 5 JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>