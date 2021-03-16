<?php
include_once("products.php");
$curl = curl_init();

//Turn off SSL
curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);

//Get the reference code from the URL
if(!empty($_GET["reference"])){
    //Clean the reference code
    $sanitize = filter_var_array($_GET, FILTER_SANITIZE_STRING);
    $reference = rawurlencode($sanitize["reference"]);
}else{
    die("No reference was supplied!");
}

//Set the configurations
curl_setopt_array($curl, array(
    CURLOPT_URL => "https://api.paystack.co/transaction/verify/" . $reference,
    CURLOPT_RETURNTRANSFER => true,
    //Set the headers
    CURLOPT_HTTPHEADER => [
        "accept: application/json",
        "authorization: Bearer sk_test_4f15f9df38e2a07a01ea5055c3bf1fa05e19640f",
        "cache-control: no-cache"
    ]
)

);
//Execute cURL
$response = curl_exec($curl);

$err = curl_error($curl);
if($err){
    die("cURL returned some error: " . $err);
}
//var_dump($response);

$tranx = json_decode($response);
if(!$tranx->status){
    die("API returned some error:" .$tranx->message);
}
if('success' == $tranx->data->status){
   $amount = $tranx->data->amount; 
   $email = $tranx->data->customer->email; 
   $ref = $tranx->data->reference; 
   $product_name = $tranx->data->metadata->custom_fields[0]->value;
   $product_desc = $tranx->data->metadata->custom_fields[1]->value;
}else{
    die("Transaction not found!");
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Verification Page</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
<h1>Verification Page.</h1>
<hr>
<div class="container">

   <div class="product">
     <h3>See the details of your purchase!</h3>
     <p>customer Email: <?php echo $email; ?></p>
     <p>reference: <?php echo $ref; ?></p>
     <p>Product Name: <?php echo $product_name; ?></p>
     <p>Product Description: <?php echo $product_desc; ?></p>
     <p>Amount: <?php echo $amount / 100; ?></p>
   </div>

   
</div>
<hr>
<footer><p>Copyright &copy; AppKinda <?php echo date("Y"); ?></p></footer>
<hr>
</body>
</html>