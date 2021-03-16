<?php
include_once("products.php");
//var_dump($products);
if(isset($_GET["empty"])){
   $error = htmlspecialchars($_GET["empty"]);
}else{
   $error = "";
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sell Dynamic Products Using Paystack</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
<h1>Sell Dynamic Products Using Paystack.</h1>
<hr>
<span id="error"><?php echo $error; ?></span>
<div class="container">
<?php foreach($products as $product): ?>
   <div class="product">
      <h3>Name: <?php echo $product["title"]; ?></h3>
      <small>Product Price: &#8358;<?php echo $product["price"]; ?></small>
      <p>Product Description:  <?php echo $product["description"]; ?></p>
      <?php echo $product["pix"]; ?>
       <a href="buy.php?product=<?php echo $product["id"]; ?>">Buy</a>
   </div>
<?php endforeach; ?>
   
</div>
<hr>
<footer><p>Copyright &copy; AppKinda <?php echo date("Y"); ?></p></footer>
<hr>
</body>
</html>