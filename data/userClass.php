<?php

//Created on 02-10-2018 by T.J. Ogunleye 

require_once("database.php");


class userClass extends database{

    function loadUser($arr) {
        //$this->database();
       
        $sql = "INSERT INTO ".$arr[0][0]."(fname, lname, phone)
        VALUES ('".$arr[0][1]."', '".$arr[0][2]."', '".$arr[0][3]."');"
        ." INSERT INTO ".$arr[1][0]." (street, house_no, zip_code, city)
        VALUES ('".$arr[1][1]."', '".$arr[1][2]."', '".$arr[1][3]."', '".$arr[1][4]."');"
        . " INSERT INTO ".$arr[2][0]." (account_owner, iban, paymentdataid)
        VALUES ('".$arr[2][1]."','".$arr[2][2]."','00')";

        $this->set_sqlstr($sql);
        $this->ex_scalar_multi();

        $this->consumeAPI($this->last_id, $arr[2][2],$arr[2][1]);
    }

    function consumeAPI($userID, $iban, $owner){
        $data_array =   array(
            'customerId' => $userID,
            'iban' => $iban,
            'owner' => $owner
        );

      $url = 'https://37f32cl571.execute-api.eu-central-1.amazonaws.com/default/'.
      'wunderfleet-recruiting-backend-dev-save-payment-data';
      
      $make_call = $this->callAPI('POST', $url, json_encode($data_array));
      $response = json_decode($make_call, true);
      $data = $response['paymentDataId'];

      $this->updateRecord($data, $userID); //update database with paymentdataid return from endpoint
      echo $data;
    }

    function callAPI($method, $url, $data){
        $curl = curl_init();
     
        switch ($method){
           case "POST":
              curl_setopt($curl, CURLOPT_POST, 1);
              if ($data)
                 curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
              break;
           case "PUT":
              curl_setopt($curl, CURLOPT_CUSTOMREQUEST, "PUT");
              if ($data)
                 curl_setopt($curl, CURLOPT_POSTFIELDS, $data);			 					
              break;
           default:
              if ($data)
                 $url = sprintf("%s?%s", $url, http_build_query($data));
        }
     
        // OPTIONS:
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($curl, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
        curl_setopt($curl, CURLOPT_CONNECTTIMEOUT, 20);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
     
        // EXECUTE:
        $result = curl_exec($curl);
        if(!$result){die("Connection Failure");}
        curl_close($curl);
        return $result;
     }

    
    function updateRecord($dataid, $userID){
        //Update database with the returned paymentdataid
        $update_sql= "UPDATE payment_details SET paymentdataid = '".$dataid."' WHERE user_id='".$userID."' ";
        $this->set_sqlstr($update_sql);
        $this->ex_scalar();
    }
}
?>