<?php

// the configuration file
require "backup-files.cfg.php";

// check the security keys
if($_GET['KEY_GET1'] != KEY_GET1 || $_GET['KEY_GET2'] != KEY_GET2){
	die;
}

// set errors & time limit
error_reporting(E_ALL);
set_time_limit(240);



/* *****************************************
	The File Backup
***************************************** */
echo "START FILE BACKUP \n ";

class Utils
{
  public static function listDirectory($dir)
  {
    $result = array();
    $root = scandir($dir);
    foreach($root as $value) {
      if($value === '.' || $value === '..') {
        continue;
      }
      if(is_file("$dir$value")) {
        $result[] = "$dir$value";
        continue;
      }
      if(is_dir("$dir$value")) {
        $result[] = "$dir$value/";
      }
      foreach(self::listDirectory("$dir$value/") as $value)
      {
        $result[] = $value;
      }
    }
    return $result;
  }
}

foreach($BACKUPDIRS as $backupInfo){
	// $backupInfo[0]; // the Name
	// $backupInfo[1]; // the Directory
	$source_dir = $backupInfo[1];
	$zip_file = $backupInfo[0].'.zip';
	$file_list = Utils::listDirectory($source_dir);
	$zip = new ZipArchive();
	if ($zip->open($zip_file, ZIPARCHIVE::CREATE) === true) {
	  foreach ($file_list as $file) {
	    if ($file !== $zip_file) {
	      $zip->addFile($file, substr($file, strlen($source_dir)));
	    }
	  }
	  $zip->close();
	}

	if(METHOD == "ftp"){
		// upload the .zip to ftp
		require_once "classes/ftp-upload.php";
		ftpSend($backupInfo[0].'.zip');
	}elseif(METHOD == "ftps"){
		// upload the .zip to ftp
		require_once "classes/ftp-upload.php";
		ftpSend($backupInfo[0].'.zip', true);
	}elseif(METHOD == "email"){
		// send the .zip file via mail
		require_once "classes/email-send.php";
		emailSend($backupInfo[0].'.zip');
	}
}
