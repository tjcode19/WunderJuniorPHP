<?php

//Created 12-03-2012 by T. J. Ogunleye
//Modified 20-06-2013
//Modified 11-11-2016
//Modified 10-02-2017
//Modified 02-10-2018

class database{

public $host;
public $username;
public $password;

public $con;
private $db_name;
private $sqlstr;
private $db_result;
public $data;
public $status, $last_id;
public $no_rec;


//set host server
function set_host($path){
	$this->host = $path;
}

//set host server
function set_con($con){
	$this->con = $con;
}

//set host user parameters
function set_user($n, $p){
	$this->username = $n;
	$this->password = $p;
}

//set host database
function set_db($db){
	$this->db_name = $db;
}

//set slq string
function set_sqlstr($str){
	$this->sqlstr = $str;
}

//set slq result
function set_result($str){

	$this->db_result = $str;
}

//initialise database
public function __construct(){
	$this->host = "localhost";
	$this->username = "root";
	$this->password = "";
	$this->con = '';
	$this->db_name = 'wunder_users';
	$this->sqlstr = '';
	$this->status = '';

}


//function to connect to the database
public function connect(){
    //var_dump($this->host."mn");
    $conn = mysqli_connect($this->host,$this->username,$this->password,$this->db_name) or die("couldn't connect");
	$this->set_con($conn);
} 

//function to  close connection to the database
function close_connection(){
    $conn= $this->con;
	mysqli_close($conn);
}

//method to create and connect to a DB
 function create_db($dummy_db_name){
 	$this->connect();
	$this->sqlstr = "CREATE DATABASE ". $dummy_db_name ; 
 	mysqli_query($this->sqlstr) or die("create database error");
	$this->set_db($dummy_db_name);
	$this->close_connection();
 	//mysql_select_db($dummy_db_name) or die("Couldn't select DB");
 
 }
 

 //method to perform scalar operation
 function ex_scalar(){
    $this->status = 0;
 	$this->connect();
 	mysqli_select_db($this->con,$this->db_name) or die("Couldn't select DB") ;
//	echo $this->sqlstr;
	mysqli_query($this->con,$this->sqlstr) or die("scalar error".mysqli_error($this->con)) ;
	$this->status = 1;
	$this->close_connection();
	
 } 

 //method to perform multiple scalar operation 
 function ex_scalar_multi(){
    $this->status = 0;
 	$this->connect();
 	mysqli_select_db($this->con,$this->db_name) or die("Couldn't select DB") ;
//	echo $this->sqlstr;
	mysqli_multi_query($this->con,$this->sqlstr) or die("scalar error".mysqli_error($this->con)) ;
    $this->status = 1;
    $this->last_id = mysqli_insert_id($this->con);
	$this->close_connection();
	
 } 

 
// method to query the DB
function querydata(){
	$this->status = 0;
	$this->connect();
	mysqli_select_db($this->con, $this->db_name) or die("Couldn't select DB".mysqli_error($this->con));
	$db_resultset = mysqli_query($this->con, $this->sqlstr) or die("query error".mysqli_error($this->con));
	$this->no_rec = mysqli_num_rows($db_resultset);
	$this->set_result( $db_resultset);
	$this->fetchdata();
	$this->status = 1;
	$this->close_connection();
}

// method to query the DB
function fetchdata(){
        $result = mysqli_fetch_array($this->db_result);
        if ($this->data == $result) {return true;}
        else{return false;}
	
	}
}

?>