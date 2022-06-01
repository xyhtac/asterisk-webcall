<?php
// WebCall v1.3

parse_str($_SERVER['QUERY_STRING'], $params);

$timeout = 10;
$asterisk_ip = "127.0.0.1";
$delay = 0;
$secret = "993mfWsfd022dsfkkfsdf0hdSDF";


$num = $params['phone'];
$ext = $params['exten'];
$num = preg_replace( "/^\+7/", "8", $num );
$num = preg_replace( "/\D/", "", $num );

$dat =  date("n-j-G");

$checkhash = md5($num."-".$ext."-".$dat);

if ($params['debug']) {
	$cr = "\\n";

	echo "<script language='javascript'>";
	echo "var debug = ' Month-Date-Hour: $dat $cr Dial: $num $cr Ext: $ext $cr Server Hash: $checkhash $cr Client Hash: $params[h]';";
	echo "alert (debug);";
	echo "</script>";
};


if (!empty($params['phone']) && !empty($params['exten']) && $checkhash == $params['h'] ) {

         if ( !empty( $num ) ) {
                echo "Dialing $num\r\n";
                $errno=0 ;
                $errstr=0 ;

                $socket = fsockopen($asterisk_ip,"5038", &$errno, &$errstr, $timeout);
                fputs($socket, "Action: login\r\n");
                fputs($socket, "Username: webcall\r\n");
                fputs($socket, "Events: off\r\n");
                fputs($socket, "Secret: " + $secret + "\r\n\r\n");

                usleep(200);

                fputs($socket, "Action: Originate\r\n" );
                fputs($socket, "Channel: SIP/$ext\r\n" );
                fputs($socket, "Callerid: $ext\r\n" );
                fputs($socket, "Timeout: 10000\r\n" );
                fputs($socket, "Exten: $num\r\n" );
                fputs($socket, "Context: from-internal\r\n" );
                fputs($socket, "Priority: 1\r\n\r\n" );
                usleep(200);
                fputs($socket, "Async: yes\r\n\r\n");
                usleep(300);

                fputs($socket, "Action: Logoff\r\n\r\n" );
                usleep(300);
                fclose($socket);

        } else {
                echo "Unable to extract number from (" . $params['phone'] . ")\r\n";
        }

} else {
   echo "Wrong number or blueprint error.";
};
?>

