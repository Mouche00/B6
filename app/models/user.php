<?php

require_once('DataProvider.php');

class Users extends DataProvider {
   public function addUser($username,$pw,$gendre,$role,$ville, $quartier,$rue,$codePostal,$email,$tel) {
    $db=$this->connect();
    if($db==null){
        return null;
   }
   $sql= 'START TRANSACTION;

         INSERT INTO users (username, pw, gendre)
         VALUES (:username,:pw,:gendre);
   
   
         SET @userId = LAST_INSERT_ID();
   
        INSERT INTO roleofuser (userId, name)
        VALUES (@userId, :role);
   
        INSERT INTO adress (ville, quartier,rue,codePostal,email,tel,userId)
        VALUES (:ville, :quartier,:rue,:codePostal,:email,:tel,@userId);

   
        COMMIT;';
   $stmt = $db->prepare($sql);
   $stmt->execute([
    ':username'=> $username,
    ":pw" => $pw,
    ":gendre" => $gendre,
    ":role" => $role,
    ":ville"=> $ville,
    ":quartier"=> $quartier,
    ":rue"=> $rue,
    ":codePostal"=> $codePostal,
    ":email"=> $email,
    ":tel"=> $tel
   ]);
   $db=null;
   $stmt=null;
}


public function displayUser(){

    $db=$this->connect();
    if($db==null){
        return null;
   }

   $query = $db->query('SELECT 
   users.userId,
   users.username,
   adress.email,
   roleofuser.name
FROM users
JOIN roleofuser ON users.userId = roleofuser.userId
JOIN adress ON users.userId = adress.userId;');

   $data_users=$query->fetchAll(PDO::FETCH_OBJ);

   $query = null;
   $db=null;
   return $data_users;
}




public function updateUser($id,$username,$pw,$gendre,$role,$ville, $quartier,$rue,$codePostal,$email,$tel) {
      
    $db = $this->connect();

    if($db == null) {
        return;
    }

    $sql = "UPDATE users SET username = :username, pw = :pw, gendre = :gendre  WHERE userId = :id;
    UPDATE adress SET ville = :ville, quartier = :quartier, rue = :rue, codePostal= :codePostal , email=:email , tel= :tel WHERE userId = :id ;
    UPDATE roleofuser SET name = :role WHERE userId = :id ;";

    $stmt = $db->prepare($sql);

    $stmt->execute([
    "id"=> $id,
    ':username'=> $username,
    ":pw" => $pw,
    ":gendre" => $gendre,
    ":role" => $role,
    ":ville"=> $ville,
    ":quartier"=> $quartier,
    ":rue"=> $rue,
    ":codePostal"=> $codePostal,
    ":email"=> $email,
    ":tel"=> $tel
       ]);

    $smt = null;
    $db = null;
}




public function displayUserOne($id){

    $db=$this->connect();
    if($db==null){
        return null;
   }

   $query ='SELECT 
   users.*,
   adress.*,
   roleofuser.*
FROM users
JOIN roleofuser ON users.userId = roleofuser.userId
JOIN adress ON users.userId = adress.userId
WHERE users.userId = :id;';

$stmt = $db->prepare($query);
$stmt->execute([
    "id"=> $id,]);

   $data_user=$stmt->fetchAll(PDO::FETCH_OBJ);

   $query = null;
   $db=null;
   return $data_user;
}













public function deleteUser($id) {
  
    $db = $this->connect();

    if($db == null) {
        return;
    }

    $sql = "DELETE FROM users WHERE userId = :id";

    $smt = $db->prepare($sql);

    $smt->execute([
        ":id" => $id
    ]);

    $smt = null;
    $db = null;
}





public function displayUserAcc($id){

    $db=$this->connect();
    if($db==null){
        return null;
   }

$query = 'SELECT users.username, account.*
FROM users
JOIN account ON users.userId = account.userId
WHERE users.userId = :id ';


$stmt = $db->prepare($query);
$stmt->execute([
    "id"=> $id,]);

   $acc_user=$stmt->fetchAll(PDO::FETCH_OBJ);

   $query = null;
   $db=null;
   return $acc_user;
}



}







?>