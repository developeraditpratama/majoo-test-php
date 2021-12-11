<?php
function filterIP($ip, bool $allow_private_ip, $pattern=array()) {
    $ip2 = explode(".", $ip);

    if ($allow_private_ip == true) {
        print_r($ip2);
        if (($ip2[0]=="10") || ($ip2[1]=="172" && (int)$ip2[1] >= 16 && (int)$ip2[1]<=31 ) || (($ip2[0]=="192") && $ip2[0]="168") ) {
            return true;
        }
    }
    foreach($pattern as $k => $v) {
        if (fnmatch($v,$ip)) {
            return 1;
        }
    }
    return "false";
}

$a = filterIP("201.121.91.120", true, array("201.121.91.*"));
$b = filterIP("201.121.123.123", true, array("201.121.*.123"));
$c = filterIP("192.168.1.1", true, array("201.121.91.*"));
$d = filterIP("192.168.1.1", false, array("201.121.91.*"));
var_dump($a, $b, $c, $d);

?>
