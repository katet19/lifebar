<?php
function customError($level, $msg, $file, $line){
	$subject = "Error: ".$level;
	$to = "lifebar.ttzfu@zapiermail.com";
	$msg = $msg."</b> in ".$file." @ line ".$line."<br>";
	
	$trace = array_reverse(debug_backtrace());
    array_pop($trace);
    if(php_sapi_name() == 'cli') {
        $msg = $msg.'Stack from ' . $type . ' \'' . $errstr . '\' at ' . $errfile . ' ' . $errline . ':' . "\n";
        foreach($trace as $item)
            $msg = $msg. '  ' . (isset($item['file']) ? $item['file'] : '<unknown file>') . ' ' . (isset($item['line']) ? $item['line'] : '<unknown line>') . ' calling ' . $item['function'] . '()' . "\n";
    } else {
        $msg = $msg. '<p class="error_backtrace">' . "\n";
        $msg = $msg. '  Stack from ' . $type . ' \'' . $errstr . '\' at ' . $errfile . ' ' . $errline . ':' . "\n";
        $msg = $msg. '  <ol>' . "\n";
        foreach($trace as $item)
            $msg = $msg. '    <li>' . (isset($item['file']) ? $item['file'] : '<unknown file>') . ' ' . (isset($item['line']) ? $item['line'] : '<unknown line>') . ' calling ' . $item['function'] . '()</li>' . "\n";
        $msg = $msg. '  </ol>' . "\n";
        $msg = $msg. '</p>' . "\n";
    }
	
	//error_log($subject." - ".$msg);
	SendEmail($to, $subject, $msg);
	echo "<div class='error_msg'>Whoops, something didn't go as planned.<br> Please, hit F5 or refresh and we will take a look.</div>";
}
?>