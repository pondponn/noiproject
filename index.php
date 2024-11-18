<?php
session_start();

// ฟังก์ชันเพิ่มสินค้าไปยังตะกร้า
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_to_cart'])) {
    $item_name = $_POST['item_name'];
    $item_price = $_POST['item_price'];
    $item_quantity = $_POST['quantity'];

    $cart_item = [
        'name' => $item_name,
        'price' => $item_price,
        'quantity' => $item_quantity,
    ];

    if (!isset($_SESSION['cart'])) {
        $_SESSION['cart'] = [];
    }

    $_SESSION['cart'][] = $cart_item;
    header("Location: order.php");
    exit;
}

$menu_items = [
    ['img' => 'kom yum.jpg', 'name' => 'ต้มยำ', 'price' => '80 บาท'],
    ['img' => 'kaprow muu.jpg', 'name' => 'กระเพราหมูกรอบ', 'price' => '80 บาท'],
    ['img' => 'muu paa.jpg', 'name' => 'ผัดเผ็ดหมูป่า', 'price' => '80 บาท'],
    ['img' => 'egg w.jpg', 'name' => 'ต้มจืดไข่น้ำ', 'price' => '70 บาท'],
    ['img' => 'muu spi.jpg', 'name' => 'หมูกรอบคั่วพริกเกลือ', 'price' => '50 บาท'],
    ['img' => 'rice egg.jpg', 'name' => 'ข้าวผัด', 'price' => '80 บาท'],
    ['img' => 'egg.jpg', 'name' => 'ไข่ดาว', 'price' => '15 บาท'],
    ['img' => 'rice.jpg', 'name' => 'ข้าวเปล่า', 'price' => '10 บาท'],
    ['img' => 'drink.jpg', 'name' => 'น้ำอัดลม', 'price' => '15 บาท']
];
?>



<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/mdb-ui-kit/8.0.0/mdb.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css" integrity="sha512-Kc323vGBEqzTmouAECnVceyQqyqdsSiqLQISBL29aUW4U/M7pSPA/gEUZQqv1cwx4OnYxTxve5UMg5GT6L4JJg==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <title>NOI RESTAURANT</title>
    <style>
        /* Navbar */
        .navbar-custom {
            background: linear-gradient(120deg, #98ef7e, #007f00);
            padding: 10px;
        }
        .navbar-custom h1 {
            color: #fff;
            font-weight: bold;
            margin: 0;
        }
        .navbar-custom .btn-login, .navbar-custom .btn-signup {
            color: #fff;
            font-weight: bold;
            margin-left: 10px;
        }
        .btn-login {
            background-color: #2196f3;
            border-color: #2196f3;
        }
        .btn-signup {
            background-color: #4caf50;
            border-color: #4caf50;
        }

        /* Container and Card Styling */
        .container {
            max-width: 1500px;
            margin: auto;
            padding: 20px;
        }
        .row {
            display: flex;
            flex-wrap: wrap;
            gap: 50px;
        }
        .row.g-4 {
            display: flex;
            flex-wrap: wrap;
            justify-content: center; /* จัดให้อยู่ตรงกลางในแนวนอน */
            align-items: center; /* จัดให้อยู่ตรงกลางในแนวตั้ง */
            height: calc(100vh - 80px); /* ลดขนาดความสูงให้สมดุลกับ Navbar (ปรับตามความสูงของ Navbar) */
            margin: 0 auto; /* จัดกึ่งกลาง */
        }
        .col-md-3 {
            max-width: 325px;
        }
        .card {
            background: rgb(192, 253, 154);
            box-shadow: 0px 4px 8px rgba(0, 0, 0, 0.1);
            transition: transform 0.2s;
            height: 100%;
        }
        .card:hover {
            transform: scale(1.05);
        }
        .card img {
            height: 230px;
            object-fit: cover;
        }
        .card-body {
            padding: 20px;
        }
        .btn-outline-secondary {
            background: rgb(234, 251, 178);
            color: #333;
        }
        .container-fluid {
            max-width: 1500px;
            margin: 0 auto; /* จัดกึ่งกลางในแนวนอน */
            padding: 25px;
        }

    </style>
</head>
<body style="padding-top: 100px; background: #f9f9f9;">

<!-- Navbar -->
<nav class="navbar navbar-expand-lg navbar-custom fixed-top">
    <div class="container d-flex justify-content-between align-items-center">
        <div class="navbar-brand">
            <h1>NOI RESTAURANT</h1>
            <!-- <p class="text-light mb-0">
                ยินดีต้อนรับ, <?php echo isset($_SESSION['user_email']) ? $_SESSION['user_email'] : 'Guest'; ?>
            </p> -->
        </div>
        <!-- <div class="d-flex align-items-center">
            <?php if (isset($_SESSION['logged_in']) && $_SESSION['logged_in'] === true): ?>
                <form action="logout.php" method="post" class="d-inline">
                    <button type="submit" class="btn btn-danger">Logout</button>
                </form>
            <?php else: ?>
                <a href="login.php" class="btn btn-login">Login</a>
                <a href="login.php" class="btn btn-signup">Sign Up</a>
            <?php endif; ?>
        </div> -->
    </div>
</nav>

<!-- Content -->
<div class="container-fluid">
    <div class="row g-4">
        <?php foreach ($menu_items as $item): ?>
            <div class="col-md-3">
                <div class="card shadow-sm">
                    <img class="card-img-top" src="<?php echo $item['img']; ?>" alt="<?php echo $item['name']; ?>">
                    <div class="card-body">
                        <b class="card-text"><?php echo $item['name']; ?></b>
                        <div class="d-flex justify-content-between align-items-center mt-2">
                            <small class="text-body-secondary"><?php echo $item['price']; ?></small>
                            <form method="post" action="order.php">
                                <input type="hidden" name="item_name" value="<?php echo $item['name']; ?>">
                                <input type="hidden" name="item_price" value="<?php echo intval(explode(' ', $item['price'])[0]); ?>">
                                <input type="number" name="quantity" value="1" min="1" style="width: 60px;">
                                <button type="submit" name="add_to_cart" class="btn btn-outline-secondary">Add to Cart</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
</div>
</body>
</html>
