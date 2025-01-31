<?php
// Start session
session_start();

// Include necessary files
include_once("../function/session.php");
include_once("../db/dbconn.php");

// Ensure a valid session ID is set
if (!isset($_SESSION['id'])) {
    header("Location: ../login.php");
    exit();
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>AlphaWare</title>
    <link rel="icon" href="img/logo.jpg" />
    <link rel="stylesheet" type="text/css" href="css/style.css" media="all">
    <link rel="stylesheet" type="text/css" href="css/bootstrap.css">
    <script src="js/jquery-1.7.2.min.js"></script>
    <script src="js/bootstrap.min.js"></script>
</head>
<body>
    <div id="header">
        <img src="img/logo.jpg">
        <label>alphaware</label>

        <?php
        // Fetch customer information
        $id = (int) $_SESSION['id'];
        $query = $conn->prepare("SELECT * FROM customer WHERE customerid = ?");
        $query->bind_param("i", $id);
        $query->execute();
        $result = $query->get_result();
        $fetch = $result->fetch_assoc();
        ?>

        <ul>
            <li><a href="function/logout.php"><i class="icon-off icon-white"></i>logout</a></li>
            <li>Welcome:&nbsp;&nbsp;&nbsp;<a href="#profile" data-toggle="modal">
                <i class="icon-user icon-white"></i><?php echo $fetch['firstname']; ?>&nbsp;<?php echo $fetch['lastname']; ?></a>
            </li>
        </ul>
    </div>

    <div id="profile" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="width:700px;">
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">x</button>
            <h3 id="myModalLabel">My Account</h3>
        </div>
        <div class="modal-body">
            <form method="post">
                <center>
                    <table>
                        <tr>
                            <td class="profile">Name:</td>
                            <td class="profile"><?php echo $fetch['firstname'] . " " . $fetch['mi'] . " " . $fetch['lastname']; ?></td>
                        </tr>
                        <tr>
                            <td class="profile">Address:</td>
                            <td class="profile"><?php echo $fetch['address']; ?></td>
                        </tr>
                        <tr>
                            <td class="profile">Country:</td>
                            <td class="profile"><?php echo $fetch['country']; ?></td>
                        </tr>
                        <tr>
                            <td class="profile">ZIP Code:</td>
                            <td class="profile"><?php echo $fetch['zipcode']; ?></td>
                        </tr>
                        <tr>
                            <td class="profile">Mobile Number:</td>
                            <td class="profile"><?php echo $fetch['mobile']; ?></td>
                        </tr>
                        <tr>
                            <td class="profile">Telephone Number:</td>
                            <td class="profile"><?php echo $fetch['telephone']; ?></td>
                        </tr>
                        <tr>
                            <td class="profile">Email:</td>
                            <td class="profile"><?php echo $fetch['email']; ?></td>
                        </tr>
                    </table>
                </center>
        </div>
        <div class="modal-footer">
            <a href="account.php?id=<?php echo $fetch['customerid']; ?>"><input type="button" class="btn btn-success" value="Edit Account"></a>
            <button class="btn btn-danger" data-dismiss="modal" aria-hidden="true">Close</button>
        </div>
        </form>
    </div>

    <div id="container">
        <div class="nav">
            <ul>
                <li><a href="home.php"><i class="icon-home"></i>Home</a></li>
                <li><a href="product1.php"><i class="icon-th-list"></i>Product</a></li>
                <li><a href="aboutus1.php"><i class="icon-bookmark"></i>About Us</a></li>
            </ul>
        </div>

        <form method="post" class="well" style="background-color:#fff;">
            <table class="table">
                <label style="font-size:25px;">My Cart</label>
                <tr>
                    <th>Image</th>
                    <th>Product Name</th>
                    <th>Size</th>
                    <th>Quantity</th>
                    <th>Price</th>
                    <th>Add</th>
                    <th>Remove</th>
                    <th>Subtotal</th>
                </tr>

                <?php
                if (isset($_SESSION['cart'])) {
                    $total = 0;
                    foreach ($_SESSION['cart'] as $id => $quantity) {
                        $stmt = $conn->prepare("SELECT * FROM product WHERE product_id = ?");
                        $stmt->bind_param("i", $id);
                        $stmt->execute();
                        $result = $stmt->get_result();
                        $row = $result->fetch_assoc();

                        $name = $row['product_name'];
                        $price = $row['product_price'];
                        $image = $row['product_image'];
                        $product_size = $row['product_size'];
                        $line_cost = $price * $quantity;
                        $total += $line_cost;

                        echo "<tr>
                                <td><img height='70px' width='70px' src='photo/{$image}'></td>
                                <td>{$name}</td>
                                <td>{$product_size}</td>
                                <td>{$quantity}</td>
                                <td>{$price}</td>
                                <td><a href='cart.php?id={$id}&action=add'><i class='icon-plus-sign'></i></a></td>
                                <td><a href='cart.php?id={$id}&action=remove'><i class='icon-minus-sign'></i></a></td>
                                <td>P {$line_cost}</td>
                              </tr>";
                    }

                    echo "<tr>
                            <td colspan='4'></td>
                            <td>Total:</td>
                            <td colspan='3'><strong>P {$total}</strong></td>
                          </tr>";
                } else {
                    echo "<tr><td colspan='8' class='alert alert-error'>Cart is empty</td></tr>";
                }
                ?>
            </table>
            <div class='pull-right'>
                <a href='product1.php' class='btn btn-inverse btn-lg'>Continue Shopping</a>
                <button name='pay_now' type='submit' class='btn btn-inverse btn-lg'>Purchase</button>
            </div>
        </form>
    </div>

    <div id="footer">
        <div class="foot">
            <label>&copy; Alphaware Inc. 2015</label>
        </div>
    </div>
</body>
</html>
