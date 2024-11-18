<?php
session_start();
$db = new mysqli('localhost', 'root', '', 'restaurant');

// ตรวจสอบการเชื่อมต่อฐานข้อมูล
if ($db->connect_error) {
    die("Connection failed: " . $db->connect_error);
}

// ดึงรายการเมนูจากฐานข้อมูล
$menu_items = [];
$result = $db->query("SELECT id, name, price FROM menu_items");
while ($row = $result->fetch_assoc()) {
    $menu_items[] = $row;
}

// เพิ่มรายการในตะกร้า
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['item_id'], $_POST['item_qty'])) {
    $item_id = (int)$_POST['item_id'];
    $item_qty = (int)$_POST['item_qty'];

    foreach ($menu_items as $menu_item) {
        if ($menu_item['id'] == $item_id) {
            $_SESSION['cart'][] = [
                'name' => $menu_item['name'],
                'price' => $menu_item['price'],
                'qty' => $item_qty
            ];
            break;
        }
    }
}

// ยกเลิกรายการทั้งหมด
if (isset($_POST['cancel_order'])) {
    unset($_SESSION['cart']);
    $_SESSION['confirm_order'] = false;
}

// ยืนยันคำสั่งซื้อ
if (isset($_POST['confirm_order'])) {
    $_SESSION['confirm_order'] = true;
}

// ชำระเงินสำเร็จ
if (isset($_POST['pay_order'])) {
    unset($_SESSION['cart']);
    $_SESSION['confirm_order'] = false;
    $payment_success = true;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Order Page</title>
    <style>
        /* CSS สำหรับการตกแต่ง */
        body {
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            background-color: #e6f5e6;
            font-family: Arial, sans-serif;
        }
        .container {
            background-color: #fff;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
            width: 350px;
        }
        h2 {
            color: #4CAF50;
            text-align: center;
        }
        .item, .order-summary {
            
            margin-bottom: 10px;
        }
        .btn {
            background-color: #4CAF50;
            color: white;
            padding: 10px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            width: 100%;
            text-align: center;
            margin-top: 10px;
        }
        .btn:hover {
            background-color: #45a049;
        }
        .cancel-btn {
            background-color: #f44336;
        }
        .nothing {
            text-align: center;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>Order Page</h2>

        <?php if (isset($payment_success)): ?>
            <p class="nothing">Payment Successful! Thank you for your order.</p>
        <?php else: ?>
            <?php if (empty($_SESSION['confirm_order'])): ?>
                <!-- แบบฟอร์มการเพิ่มสินค้า -->
                <form method="post">
                    <div class="item">
                        <label>Item Name:</label>
                        <select name="item_id" required>
                            <option value="" disabled selected>เลือกเมนู</option>
                            <?php foreach ($menu_items as $menu_item): ?>
                                <option value="<?= $menu_item['id'] ?>">
                                    <?= htmlspecialchars($menu_item['name']) ?> - $<?= number_format($menu_item['price'], 2) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="item">
                        <label>Quantity:</label>
                        <input type="number" name="item_qty" min="1" required>
                    </div>
                    <button type="submit" class="btn">Add to Cart</button>
                </form>
            <?php endif; ?>

            <!-- แสดงรายการที่อยู่ในตะกร้า -->
            <div class="order-summary">
                <h3 class="nothing  ">Order Summary</h3>
                <?php if (!empty($_SESSION['cart'])): ?>
                    <ul>
                        <?php
                        $total = 0;
                        foreach ($_SESSION['cart'] as $item):
                            $item_total = $item['price'] * $item['qty'];
                            $total += $item_total;
                        ?>
                        <li>
                            <?= htmlspecialchars($item['name']) ?> - 
                            <?= htmlspecialchars($item['qty']) ?> x 
                            $<?= number_format($item['price'], 2) ?> = 
                            $<?= number_format($item_total, 2) ?>
                        </li>
                        <?php endforeach; ?>
                    </ul>
                    <p><strong>Total: $<?= number_format($total, 2) ?></strong></p>
                <?php else: ?>
                    <p class="nothing">Your cart is empty.</p>
                <?php endif; ?>
            </div>

            <!-- ปุ่มสำหรับยกเลิกหรือยืนยันคำสั่งซื้อ -->
            <form method="post">
                <?php if (empty($_SESSION['confirm_order'])): ?>
                    <button type="submit" name="confirm_order" class="btn confirm-btn">Confirm Order</button>                    
                    <button type="submit" name="cancel_order" class="btn cancel-btn">Cancel Order</button>
                <?php else: ?>
                    <button type="submit" name="pay_order" class="btn confirm-btn">Pay Now</button>
                    <button type="submit" name="cancel_order" class="btn cancel-btn">Cancel Order</button>
                <?php endif; ?>
            </form>
        <?php endif; ?>
    </div>
</body>
</html>
