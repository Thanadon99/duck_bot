<?php

function Flight($x)
{
	if ("$x"<"1")
	{
		fwrite($myfile, $x+1);
	}
	else
	{
		fwrite($myfile, $x-22);
	}	
	
	$is_message = 1;
	$typeMessage = 'text';
	$userMessage = "ทดสอบ";
	$result = array($is_message,$typeMessage,$userMessage,$x);
	return $result;
}



?>