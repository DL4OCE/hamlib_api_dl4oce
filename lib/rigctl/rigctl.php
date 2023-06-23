<?php

function get_trx_qrg(int $trx_id=0){
    // lookup TRX coordinates (port, speed, ...
    $rigctl_executable = exec('echo $(whereis rigctl) | awk \'{ print $2 }\'', $output, $result_code);
    $syscall = "$rigctl_executable -m " . $GLOBALS["model"] . " -r " . $GLOBALS["device"] . " ";
    // echo $syscall . "<br>";
    $result = exec("echo $({$rigctl_executable} f)", $output, $result_code);
    print_r($result);
    // print_r($output);
    
    // return($output);
}

?>