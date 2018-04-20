<?php
/**
 * Created by PhpStorm.
 * User: f-mkotsovoulou
 * Date: 3/16/2018
 * Time: 2:00 PM
 */

function getArtists()
{
    include "db.php";
    $results = $db->query("select * from artists");
    $resultsArray = $results->fetchAll(PDO::FETCH_ASSOC);
    return $resultsArray;
}


function getPaintings()
{
    include "db.php";
    $results = $db->query("select id, title, filename from paintings");
    $resultsArray = $results->fetchAll(PDO::FETCH_ASSOC);
    return $resultsArray;
}

function getPaintingBlob($id) {
    include "db.php";
    $results = $db->prepare("select title, image, price, name as artist from paintings p, artists a where p.artists_id = a.id and p.id = ?");
    $results->bindValue(1, $id);
    $results->execute();
    
    $resultsArray = $results->fetchAll(PDO::FETCH_ASSOC);
    return $resultsArray;
     
}

function findArtist($name) {
    include "db.php";
    $results = $db->prepare("select * from artists where name like ?");
    $name = '%'.$name.'%';
    $results->bindValue(1, $name);
    $results->execute();
    
    $resultsArray = $results->fetchAll(PDO::FETCH_ASSOC);
    return $resultsArray;
     
}


function addArtist($artistName) {
      include "db.php";
      try{ 
      $sql = "insert into artists (name) values (?)";
      $result = $db->prepare($sql);
      $result->bindValue(1,$artistName);
      $result->execute();
      return true;
      } catch (PDOException $e) {
          echo "Error " . $e->getMessage();
          return false;
      }
}   
function deleteArtist($id) {
      include "db.php";
      try{ 
      $sql = "delete from artists where id = ?";
      $result = $db->prepare($sql);
      $result->bindValue(1,$id);
      $result->execute();
    
      return true;
      } catch (PDOException $e) {
          echo "Error " . $e->getMessage();
          return false;
      }
}

function updateArtist($id, $newArtistName) {
      include "db.php";
      try{ 
      $sql = "update artists set name = ? where id = ?";
      $result = $db->prepare($sql);
      $result->bindValue(1,$newArtistName);
      $result->bindValue(2,$id);
      $result->execute();
      return true;
      } catch (PDOException $e) {
          echo "Error " . $e->getMessage();
          return false;
      }
}

function login ($email, $pass) {
    require('db.php');
    try {
        $myquery = $db->prepare("select username, level from users where status='A' and email=? and password=?");
        $myquery->bindParam(1, $email);
        $myquery->bindParam(2, $pass);
        $myquery->execute();
        if ($myquery->rowCount()== 0) return false;
        else {
            $userInfo = $myquery->fetch();
            $_SESSION['username'] = $userInfo['username'];
            $_SESSION['level'] = $userInfo['level']; 
            //level: U=user and A=admin
            return true;
        }

    } catch (PDOException $e) {
        echo 'Error selecting from the users table...' . $e;
        return false;
    }
}

