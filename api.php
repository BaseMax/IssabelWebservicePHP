<?php
/*
 * Author: Max Base
 * Name: ISSABEL simple API Restful JSON
 * Date: 15 May, 2022
 */

date_default_timezone_set('Asia/Tehran');
header("Content-type: application/json");

$json_file = "cache.json";
if (!file_exists($json_file)) {
  print json_encode([
    "status" => 0,
  ]);
}
else {
  $data = file_get_contents($json_file);
  $obj = json_decode($data, true);
  if($obj === null || $obj === [] || $data === "" || $data === null) {
    print json_encode([
      "status" => 0,
    ]);
  }
  else {
    print json_encode([
      "status" => 1,
      "data" => $obj,
      "lastupdate" => date("F d Y H:i:s", filemtime($json_file))
    ]);
  }
}
