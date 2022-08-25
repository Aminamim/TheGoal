  
<?php

function loglogFile($rtn){
	$f=fopen("log/loglog.txt","a");
	fwrite($f, $rtn . "\n");
	fclose($f);
	}

?>