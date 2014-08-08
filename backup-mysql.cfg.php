<?php

/* *****************************************
	General
***************************************** */
define("METHOD", ""); // The backup method ("email", "ftp" or "ftps")

/* *****************************************
	FTP Settings
***************************************** */
define("FTPSERVER", ""); // The FTP server
define("FTPUSER", ""); // Your FTP username
define("FTPPASS", ""); // Your FTP password
define("FTPDIR", ""); // The directory to upload the backup
define("FTPPASSIVE", false); // Sets on/off the passive ftp mode

/* *****************************************
	Email Settings
***************************************** */
define("EMAILTO", ""); // The Email Address (To)
define("EMAILFROM", ""); // The "from" Email Address
define("EMAILSUBJ", ""); // The Email Subject

/* *****************************************
	MySQL Settings
***************************************** */
define("DBHOST", ""); // The MySQL host (e.g. localhost)
define("DBUSER", ""); // Your MySQL username
define("DBPASS", ""); // Your MySQL password
// The names of the databases to backup:
$DBNAMES = array(
		"database_one",
		"database_two"
);

/* *****************************************
	Security Settings
***************************************** */
// Some random keys for GET requests
// Example:
// backup-mysql.php?KEY_GET1=randomStuff&KEY_GET2=otherStuff
define("KEY_GET1", "688787d8ff144c502c7f5cffaafe2cc588d86079f9de88304c26b0cb99ce91c6");
define("KEY_GET2", "3608bca1e44ea6c4d268eb6db02260269892c0b42b86bbf1e77a6fa16c3c9282");
