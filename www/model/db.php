<?php

function get_db_connect(){
  // MySQL用のDSN文字列
  $dsn = 'mysql:dbname='. DB_NAME .';host='. DB_HOST .';charset='.DB_CHARSET;
 
  try {
    // データベースに接続
    $dbh = new PDO($dsn, DB_USER, DB_PASS, array(PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES utf8mb4'));
    $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $dbh->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
    $dbh->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
  } catch (PDOException $e) {
    exit('接続できませんでした。理由：'.$e->getMessage() );
  }
  return $dbh;
}

function fetch_query($db, $sql, $params = array()){
  try{
    $statement = $db->prepare($sql);
    $statement->execute($params);
    $data = $statement->fetch();
    if ($data !== FALSE) {
      return entity_array($data);
    } else {
      return FALSE;
    }
  }catch(PDOException $e){
    set_error('データ取得に失敗しました。');
  }
  return false;
}

function fetch_all_query($db, $sql, $params = array()){
  try{
    $statement = $db->prepare($sql);
    $statement->execute($params);
    $data = $statement->fetchAll();
    if ($data !== FALSE) {
      return entity_double_array($data);
    } else {
      return FALSE;
    }
  }catch(PDOException $e){
    set_error('データ取得に失敗しました。');
  }
  return false;
}

function execute_query($db, $sql, $params = array()){
  try{
    $statement = $db->prepare($sql);
    return $statement->execute($params);
  }catch(PDOException $e){
    set_error('更新に失敗しました。');
  }
  return false;
}


/**
* 1次元配列の文字列型の値のみをHTMLエンティティに変換する
*/
function entity_array($array) {
  foreach ($array as $key => $value) {
    if (is_string($value) === TRUE) {
      $array[$key] = h($value);
    } else {
      $array[$key] = $value;
    }
  }
  return $array;
}

/**
* 2次元配列の文字列型の値のみをHTMLエンティティに変換する
*/
function entity_double_array($double_array) {
  foreach ($double_array as $key => $value) {
    foreach ($value as $keys => $values) {
      if (is_string($values) === TRUE) {
        $value[$keys] = h($values);
      } else {
        $value[$keys] = $values;
      }
    }
    $double_array[$key] = $value;
  }
  return $double_array;
}