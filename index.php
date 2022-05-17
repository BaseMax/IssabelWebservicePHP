<?php
/*
 * Author: Max Base
 * Name: ISSABEL simple API Restful JSON
 * Date: 15 May, 2022
 */

date_default_timezone_set('Asia/Tehran');

$issabel_cookie = "4co3rsdrkemu1pr9tf5bqpjr22";

function get_req(string $url, string $cookie)
{
  $curl = curl_init($url);
  curl_setopt($curl, CURLOPT_URL, $url);
  curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);

  curl_setopt($curl, CURLOPT_HTTPHEADER, array("Cookie: issabelSession=" . $cookie));
  // curl_setopt($curl, CURLOPT_COOKIEFILE, $ckfile);

  curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
  curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);

  $resp = curl_exec($curl);
  curl_close($curl);
  return $resp;
}

$date_start = date("d") . "+" . date("F") . "+" . date("Y");
$date_end = $date_start;
$url = "https://192.168.1.103/index.php?menu=cdrreport&date_start=". $date_start ."&date_end=" . $date_end . "&field_name=dst&field_pattern=&status=ALL&ringgroup=&exportcsv=yes&rawmode=yes";

$data = [];

$res = get_req($url, $issabel_cookie);
// $res = file_get_contents("data.csv");

// print_r($res);

if($res === null) {
  exit("Sorry!\n");
}
$lines = explode("\n", trim($res));
if (count($lines) > 0) {
  $columns = explode(",", rtrim($lines[0], ","));
  $columns = array_map(function($name) {
    return str_replace("\"", "", $name);
  }, $columns);

  // print_r($columns);
  // print_r($lines);

  $line_count = count($lines);
  for($line_i = 1; $line_i < $line_count; $line_i++) {
    $cols = explode(",", rtrim($lines[$line_i], ","));
    $cols = array_map(function($name) {
      return str_replace("\"", "", $name);
    }, $cols);
    // print_r($cols);
    foreach($columns as $i => $column_name) {
      $data_row[$column_name] = $cols[$i];
    }
    $data[] = $data_row;
  }
}

file_put_contents("cache.json", json_encode($data));
