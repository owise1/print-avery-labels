<?php


require_once('LabelMaker.php');
require_once('_functions.php');

$dbServer = 'localhost';
$dbUser = 'root';
$dbPass = 'root';
$dbName = 'crossroads';

$showLabelTemplate = false;

mysql_connect($dbServer, $dbUser, $dbPass) or die('no db');
mysql_select_db($dbName);


if ($ids = $_GET['ids']) {
  if ($addresses = fetchIDs($ids)) {
    $lm = new LabelMaker($showLabelTemplate);
    foreach ($addresses as $row) {
      $lm->addAddress($row['first_name'] . ' ' . $row['last_name'], 
                      $row['street_address1'],
                      $row['city'] . ', ' . getStateAbrev($row['state']) . ' ' . $row['zip']);
    }
    $lm->output();
  }
}




?>
