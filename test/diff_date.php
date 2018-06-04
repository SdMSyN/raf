<?php

	$datetime1 = new DateTime(date("Y-m-d"));
	$datetime2 = new DateTime('2016-02-20');
	$interval = $datetime1->diff($datetime2);
	echo $interval->format('%R%a dias');
	
?>