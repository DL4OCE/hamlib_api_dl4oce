<?php

function get_trx_qrg(int $trx_id=0){
    build_response(array(
        "qrg" => poll_trx($trx_id, "f")
    ));
}

function poll_trx($trx_id, $command){
    // lookup TRX coordinates (port, speed, ...
    $rigctl_executable = exec('echo $(whereis rigctl) | awk \'{ print $2 }\'', $output, $result_code);
    $syscall = "$rigctl_executable -m " . $GLOBALS["model"] . " -r " . $GLOBALS["device"] . " ";
    // echo $syscall . "<br>";
    $result = exec("echo $({$rigctl_executable} {$command})", $output, $result_code);
    return($result);
    // print_r($result);
}

function set_trx_qrg(int $trx_id=0, $requestBody){
    // lookup TRX coordinates (port, speed, ...
    $rigctl_executable = exec('echo $(whereis rigctl) | awk \'{ print $2 }\'', $output, $result_code);
    $syscall = "$rigctl_executable -m " . $GLOBALS["model"] . " -r " . $GLOBALS["device"] . " ";
    // echo $syscall . "<br>";
    $shell_cmd = "echo $({$rigctl_executable} F " . $requestBody['newValue'] . ")";
    $result = exec($shell_cmd, $output, $result_code);
    print_r($result);
    // print_r($output);
    
    // return($output);
}



?>