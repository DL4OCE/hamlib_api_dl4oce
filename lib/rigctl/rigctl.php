<?php
function poll_trx($trx_id, $command){
    // lookup TRX coordinates (port, speed, ...
    $rigctl_executable = exec('echo $(whereis rigctl) | awk \'{ print $2 }\'', $output, $result_code);
    $syscall = "$rigctl_executable -m " . $GLOBALS["model"] . " -r " . $GLOBALS["device"] . " -s 38400";
    echo "$syscall $command\n";
    // $syscall = "pwd";
    // $result = exec("echo $({$rigctl_executable} {$command})", $output, $result_code);
    $result = exec("$syscall $command", $output, $result_code);
    // var_dump($output);
    // die();
    return($output);
}

function get_trx_qrg(int $trx_id){
    build_response(array(
        "qrg" => poll_trx($trx_id, "f")[1]
    ));
}


function set_trx_qrg($requestBody, int $trx_id=0){
    poll_trx($trx_id, "F " . $requestBody['newValue']);
    build_response(array());
    // build_response(array(
    //     "qrg" => poll_trx($trx_id, "F " . $requestBody['newValue'])
    // ));
}

function get_trx_mode(int $trx_id){
    $response = poll_trx($trx_id, "m");
    build_response(array(
        "mode" => $response[1],
        "passband" => $response[2]
    ));
}


function set_trx_mode($requestBody, int $trx_id=0){
    poll_trx($trx_id, "M " . $requestBody['mode'] . " " .$requestBody['passband']);
    build_response(array());
    // build_response(array(
    //     "qrg" => poll_trx($trx_id, "F " . $requestBody['newValue'])
    // ));
}



?>