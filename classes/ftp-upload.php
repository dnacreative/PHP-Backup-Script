<?php
function ftpSend($filename){
	echo "Start FTP Upload \n ";
	// connect to server
	$conn_id = ftp_connect(FTPSERVER, 21) or die ("Cannot connect to host \n ");
	$login_result = ftp_login($conn_id, FTPUSER, FTPPASS) or die("Cannot login \n ");
	// go to the directory
	if (ftp_chdir($conn_id, FTPDIR)) {
		echo "Changed directory to: ".FTPDIR." \n ";
	} else {
		die("Error while changing directory to ".FTPDIR." \n ");
	}
	// upload the file
	if (ftp_put($conn_id, date("Y-m-d_H-i_").$filename, $filename, FTP_BINARY)) {
		echo "$filename uploaded \n ";
	} else {
		die("Error while uploading $filename \n ");
		
	}
	ftp_close($conn_id);
	// delete the local file
	unlink($filename);
	echo "End FTP Upload \n ";
}
?>