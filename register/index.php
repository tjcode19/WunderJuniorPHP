<?php 

require_once("../data/userClass.php");

$user = new userClass;
//$user->database();

$ln = $_POST['lname'];
$fn = $_POST['fname'];
$ph = $_POST['phone'];
$str = $_POST['street'];
$h_no = $_POST['house_no'];
$z_c = $_POST['zip_code'];
$city = $_POST['city'];
$acct_o = $_POST['acct_owner'];
$iban = $_POST['iban'];

//Build data array
$userInfo = array(
    array("personal_info", $fn, $ln, $ph),
    array("user_address", $str, $h_no, $z_c, $city),
    array("payment_details", $acct_o, $iban)
);

$user->loadUser($userInfo); //Call the function to insert to db

?>