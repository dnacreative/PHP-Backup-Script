<?php

// the configuration file
require "backup-mysql.cfg.php";

// check the security keys
if($_GET['KEY_GET1'] != KEY_GET1 || $_GET['KEY_GET2'] != KEY_GET2){
	die;
}

// set errors & time limit
error_reporting(E_ALL);
set_time_limit(240);

/* *****************************************
	ZIP Creation Function
***************************************** */
function createZipFile($name){
	// create .zip archive
	$zip = new ZipArchive();
	$zip->open("mysql-".$name.".zip", ZIPARCHIVE::CREATE);
	// add the .sql file
	$zip->addFile("mysql-".$name.".sql");
	$zip->close();
	// delete the .sql file
	unlink("mysql-".$name.".sql");
	echo " ZIP archive created \n ";
}


/* *****************************************
	The MySQL Backup
***************************************** */
// start
echo "MySQL Backup Started \n ";

foreach($DBNAMES as $dbname){
	$name = $dbname;
	// connect to db
	$conn = mysql_connect(DBHOST, DBUSER, DBPASS) or die(mysql_error());
	mysql_select_db($dbname);
	// create the backup file
	$f = fopen("mysql-".$name.".sql", "w");
	// create the MySQL backup
	$tables = mysql_list_tables($dbname);
	while ($cells = mysql_fetch_array($tables)){
	    $table = $cells[0];
	    fwrite($f,"DROP TABLE `".$table."`;\n");
	    $res = mysql_query("SHOW CREATE TABLE `".$table."`");
	    if ($res){
	        $create = mysql_fetch_array($res);
	        $create[1] .= ";";
	        $line = str_replace("\n", "", $create[1]);
	        fwrite($f, $line."\n");
	        $data = mysql_query("SELECT * FROM `".$table."`");
	        $num = mysql_num_fields($data);
	        while ($row = mysql_fetch_array($data)){
	            $line = "INSERT INTO `".$table."` VALUES(";
	            for ($i=1;$i<=$num;$i++){
	                $line .= "'".mysql_real_escape_string($row[$i-1])."', ";
	            }
	            $line = substr($line,0,-2);
	            fwrite($f, $line.");\n");
	        }
	    }
	}
	// finished
	fclose($f);
	echo "MySQL backup of ".$dbname." was successful \n ";

	// put .sql in zip archive
	createZipFile($name);

	if(METHOD == "ftp"){
		// upload the .zip to ftp
		require_once "classes/ftp-upload.php";
		ftpSend("mysql-".$name.".zip");
	}elseif(METHOD == "ftps"){
		// upload the .zip to ftp
		require_once "classes/ftp-upload.php";
		ftpSend("mysql-".$name.".zip", true);
	}elseif(METHOD == "email"){
		// send the .zip file via mail
		require_once "classes/email-send.php";
		emailSend("mysql-".$name.".zip");
	}
}
echo "End MySQL Backup \n ";
