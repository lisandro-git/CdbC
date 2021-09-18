<?php
// Add the required information below
$HOSTNAME    = "";
$USERNAME    = "";
$PASSWORD    = "";
$DB_NAME     = "";
$TABLE_NAME  = "";

date_default_timezone_set("Europe/Paris");
session_start();

function db_exists($SQL, $DB_NAME)
{
  $create_delete = "CREATE DATABASE $DB_NAME";
  if (mysqli_query($SQL, $create_delete)) {
    return False;
  } else { // edode : not exist
    return True;
  }
}

function connect_mysql($HOSTNAME, $USERNAME, $PASSWORD)
{
  $SQL = mysqli_connect($HOSTNAME, $USERNAME, $PASSWORD);

  if (mysqli_connect_error()) {
    die("Connection failed: " . mysqli_connect_error());
  } else {
    return $SQL;
  }
}

function manage_db($SQL, $DB_NAME, $create=null, $db_connect=null)
{
  if ($db_connect !== null)
  {
    if ($db_connect){
      echo "[*] Connecting to Database $DB_NAME\n";
      mysqli_select_db($SQL, $DB_NAME);
      return;
    }
  }

  if ($create !== null) {
    if ($create) {
      echo "[*] Creating Database $DB_NAME\n";
      $create_delete = "CREATE DATABASE $DB_NAME";
    } else {
      echo "[*] Dropping Database $DB_NAME\n";
      $create_delete = "DROP DATABASE $DB_NAME";
    }

    mysqli_query($SQL, $create_delete);
    if (mysqli_connect_error()) die(mysqli_connect_error());
    return;
  }
}

function create_table($SQL, $TABLE_NAME, $DB_NAME)
{
  echo "[*] Creating Table in $DB_NAME Database\n";

  $query = ""; // Add your SQL statement that will create your table

  if (mysqli_query($SQL, $query) === TRUE) {
    echo "Table $TABLE_NAME created successfully\n";
  } else {
    echo "Error creating table: " . mysqli_error($SQL);
  }
}

function connect_db($SQL, $DB_NAME, $TABLE_NAME) // edode : can be used if the DB suddenly "disappears"
{
  if (!db_exists($SQL, $DB_NAME)) {
    manage_db($SQL, $DB_NAME, $create=true, $db_connect=null);
    manage_db($SQL, $DB_NAME, $create=null, $db_connect=true);
    create_table($SQL, $TABLE_NAME, $DB_NAME);
  } else {
    manage_db($SQL, $DB_NAME, $create=null, $db_connect=true);
  }
  echo "Working on " . $DB_NAME . "\n";
}

$SQL = connect_mysql($HOSTNAME, $USERNAME, $PASSWORD);
connect_db($SQL, $DB_NAME, $TABLE_NAME);
