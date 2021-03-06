<?php

if(_SERVER("REQUEST_METHOD"))
	exit; // avoid php execution via http request

include_once "/lib/sd_340.php";

echo "PHPoC example : P4M-400 / HT / HC-SR04 Ultrasonic Module\r\n";
 
// setup trigger pulse timer
ht_ioctl(0, "set mode output pulse");
ht_ioctl(0, "set div us");
ht_ioctl(0, "set repc 1");
ht_ioctl(0, "set count 5 10"); // 10us pulse width
 
// setup echo capture timer
ht_ioctl(1, "reset");
ht_ioctl(1, "set div us");
ht_ioctl(1, "set mode capture toggle");
ht_ioctl(1, "set trigger from pin rise");
ht_ioctl(1, "set repc 4");
 
while(1)
{
	ht_ioctl(1, "start"); // we should start capture timer first
	ht_ioctl(0, "start"); // start trigger pulse
 
	usleep(100000); // sleep 100ms
	ht_ioctl(1, "stop");
 
	// 1st capture value ("get count 0") is always zero.
	// we should get 2nd capture value;
	$us = ht_ioctl(1, "get count 1");

	$dist = $us * 340.0 / 2; // us to meter conversion
	$dist = $dist / 10000; // meter to centimeter conversion

	printf("%d us, %.1f cm\r\n", $us, $dist);

	sleep(1);
}

?>
