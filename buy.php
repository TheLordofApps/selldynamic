<?php
include_once("products.php");
//var_dump($products);
if(isset($_GET["product"])){
    $id = htmlspecialchars($_GET["product"]);
    $product_name = $products[$id]["title"];
    $price = $products[$id]["price"];
    $product_desc = $products[$id]["description"];
    $pix = $products[$id]["pix"];
}
else{
    header("Location: index.php?empty=Please, select a product to continue!");
}

//Inegrate Paystack
if(isset($_POST["submit"])){
    $email = htmlspecialchars($_POST["email"]);

    //Initiate Paystack
    $url = "https://api.paystack.co/transaction/initialize";

    //Gather the body params
    $transaction_data =[
        "email" => $email,
        "amount" => $price * 100,
        "callback_url" => "http://localhost/selldynamic/verify.php",
        "metadata" => [
            "custom_fields" =>[
                [
                    "display_name" => "Product Name",
                    "variable_name" => "product",
                    "value" => $product_name
                ],

                 [
                    "display_name" => "Product Description",
                    "variable_name" => "description",
                    "value" => $product_desc
                ],
                 [
                    "display_name" => "Product Price",
                    "variable_name" => "price",
                    "value" => $price
                ]
            ]
        ]
    ];

    //Generate a URL-encoded string
    $encode_transaction_data = http_build_query($transaction_data);
    //Open connect to cURL
    $ch = curl_init();

//Turn off Mandatory SSL checking
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    //Set the url
    curl_setopt($ch, CURLOPT_URL, $url);

    //Enable data to be sent in POST arrays
    curl_setopt($ch, CURLOPT_POST, true);

    //Collect the posted data from above
    curl_setopt($ch, CURLOPT_POSTFIELDS, $encode_transaction_data);

    //Set the headers from the endpoint
    curl_setopt($ch, CURLOPT_HTTPHEADER, array(
         "Authorization: Bearer sk_test_4f15f9df38e2a07a01ea5055c3bf1fa05e19640f",
         "cache-Control: no-cache"
    ));

    //Make curl return the data instead of echoing it
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

    //Execute cURL
    $result = curl_exec($ch);

    //Check for errors
$errors = curl_error($ch);
    if($errors){
        die("Curl returned some errors: " . $errors);
    }

   // var_dump($result);
 $transaction = json_decode($result);
 //Automatically redirect customers to the payment page
 header("Location: " . $transaction->data->authorization_url);
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Buy Now</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
<h1>Buy Now!.</h1>
<hr>
<div class="container">

   <div class="product">
      <h3>Name: <?php echo  $product_name; ?></h3>
      <small>Product Price: &#8358;<?php echo $price; ?></small>
      <p>Product Description:  <?php echo $product_desc; ?></p>
      <?php echo $pix; ?>
    <form action="" method="POST">
      <label>Your Email</label>
      <input type="email" name="email" placeholder="Enter your email here ..." required>
      <input type="submit" name="submit" value="Order Now!">
    </form>
   </div>

   
</div>
<hr>
<footer><p>Copyright &copy; AppKinda <?php echo date("Y"); ?></p></footer>
<hr>
</body>
</html>