<?php
ini_set("auto_detect_line_endings", true);

$uploaded_files = $_FILES['csv-upload'];
$file_size_limit = 30000;
$file_types_accept = [
  'csv' => 'text/plain'
];
$columns_we_like = ['lat', 'long', 'ipaddress'];

$new_file_path = sprintf('uploaded_csv/%s.%s', sha1_file($uploaded_files['tmp_name']), 'csv');

try {

  //broad look at error sent with file.
  if (
      !isset($uploaded_files['error']) ||
      is_array($uploaded_files['error'])
  ) {
    throw new RuntimeException('Invalid parameters.');
  }

  //checking specific errors.
  switch ($uploaded_files['error']) {
    case UPLOAD_ERR_OK:
      break;
    case UPLOAD_ERR_NO_FILE:
      throw new RuntimeException('No file sent.');
    //checking file size base don front-end specs.
    case UPLOAD_ERR_INI_SIZE:
    case UPLOAD_ERR_FORM_SIZE:
      throw new RuntimeException('Exceeded filesize limit.');
    default:
      throw new RuntimeException('Unknown errors.');
  }

  // checking filesize based on server specs.
  if ($uploaded_files['size'] > $file_size_limit) {
    throw new RuntimeException('Exceeded filesize limit.');
  }

  // Check file type.
  $finfo = new finfo(FILEINFO_MIME_TYPE);
  if (false === $ext = array_search(
          $finfo->file($uploaded_files['tmp_name']),
          $file_types_accept,
          true
      )) {
    throw new RuntimeException('Invalid file format.');
  }

  //check if file already exists
  /*if (file_exists($new_file_path)) {
    throw new RuntimeException('file already exists');
  }*/

  //save file locally
  if (!move_uploaded_file(
      $uploaded_files['tmp_name'],
      $new_file_path
  )) {
    throw new RuntimeException('Failed to move uploaded file.');
  }

} catch (RuntimeException $e) {

  echo $e->getMessage();
  exit;

}

//open file.
//TODO: error handling for file open

$csv_ready_array = [];
$array_keys = [];
$final_array = [];

if($handle = fopen($new_file_path, 'r+')) {
  //get its contents.
  $i = 0;

  while (($row = fgetcsv($handle)) !== false) {
    if (empty($array_keys)) {
      $array_keys = $row;
    } else {
      $final_array[$i] = array_combine($array_keys, $row);
      $i++;
    }
  }

  foreach($final_array as $value) {
    $value = filter_columns($value);
    array_push($csv_ready_array, $value);
  }

  fclose($handle);
};

if ($fp = fopen($new_file_path, 'w')) {

  fputcsv($fp, array_keys($csv_ready_array[0]));

  foreach($csv_ready_array as $value) {
    fputcsv($fp, $value);
  }

  fclose($fp);
}

$sql = "COPY submittedcsv(";
$sql = return_query_cols(array_keys($csv_ready_array[0]), $sql);
$sql .= " FROM '" . realpath($new_file_path) . "' DELIMITERS ',' CSV";

$dbconn = pg_connect("dbname=spacial");

if(!$dbconn) {
  echo 'not connecting to postgresql';
  exit;
}

$result = pg_query($dbconn, $sql);

if(!$result) {
  echo 'query prob <br>' . $sql . '<br><br>Path:<br>' . realpath($new_file_path);
  exit;
}

echo 'looks like the following query worked <br>' . $sql;

function filter_columns($ary) {
  global $columns_we_like;
  $cleaned_up_ary = [];

  foreach($ary as $key => $val){
    if (in_array($key, $columns_we_like)) {
      $cleaned_up_ary[$key] = $val;
    }
  }

  return $cleaned_up_ary;
}

function return_query_cols ($ary, $sql_str) {
  $last_key = count($ary) - 1;
  foreach($ary as $key => $val) {

    if($last_key != $key){
      $sql_str .= $val . ', ';
    } else {
      $sql_str .= $val;
    }
  }

  return $sql_str . ')';
}

ini_set('auto_detect_line_endings',FALSE);