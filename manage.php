<?php
//Author:	Anthony Hess
//File:		display.php
// This file is currently used for uploading the new taf and metar reports
// to the system by way of the webpage. This file is currently called by new_wx.php by
// clicking the Update CSV button on the page. Eventually this file
// will update automatically at set intervals and manage other parts of the site.  
	$cwd = getcwd();
	exec('/usr/bin/python ' . $cwd .'/load_CSV_met.py');
	exec('/usr/bin/python ' . $cwd .'/load_CSV_taf.py');
	echo "New Weather uploaded";

?>