<?php
function poll_trx($trx_id, $command){
    // lookup TRX coordinates (port, speed, ...
    $rigctl_executable = exec('echo $(whereis rigctl) | awk \'{ print $2 }\'', $output, $result_code);
    if($GLOBALS["dummy"]==true){
        $syscall = $rigctl_executable;
    } else {
        $syscall = "$rigctl_executable -m " . $GLOBALS["model"] . " -r " . $GLOBALS["device"] . " -s 38400";
    }
    echo "$syscall $command\n";
    // $syscall = "pwd";
    // $result = exec("echo $({$rigctl_executable} {$command})", $output, $result_code);
    $result = exec("$syscall $command", $output, $result_code);
    // var_dump($output);
    // die();
    return($output);
}

function get_trx_frequency(int $trx_id){
    build_response(array(
        "freq" => poll_trx($trx_id, "f")[1]
    ));
}

function set_trx_frequency($requestBody, int $trx_id=0){
    poll_trx($trx_id, "F " . $requestBody['newValue']);
    build_response(array());
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
}

function get_trx_split_frequency(int $trx_id){
    build_response(array(
        "qrg" => poll_trx($trx_id, "i")[1]
    ));
}

function set_trx_split_frequency($requestBody, int $trx_id=0){
    poll_trx($trx_id, "I " . $requestBody['newValue']);
    build_response(array());
}

function get_trx_split_mode(int $trx_id){
    $response = poll_trx($trx_id, "x");
    build_response(array(
        "mode" => $response[1],
        "passband" => $response[2]
    ));
}

function set_trx_split_mode($requestBody, int $trx_id=0){
    poll_trx($trx_id, "X " . $requestBody['mode'] . " " . $requestBody['passband']);
    build_response(array());
}

function get_trx_split_frequency_mode(int $trx_id){
    $response = poll_trx($trx_id, "k");
    build_response(array(
        "frequency" => $response[1],
        "mode" => $response[2],
        "passband" => $response[3]
    ));
}

function set_trx_split_frequency_mode($requestBody, int $trx_id=0){
    poll_trx($trx_id, "K " . $requestBody['frequency'] . " " . $requestBody['mode'] . " " . $requestBody['passband']);
    build_response(array());
}

function get_trx_split_vfo(int $trx_id){
    $response = poll_trx($trx_id, "s");
    build_response(array(
        "split_mode" => $response[1],
        "tx_vfo" => $response[2]
    ));
}

function set_trx_split_vfo($requestBody, int $trx_id=0){
    poll_trx($trx_id, "S " . $requestBody['split_mode'] . " " . $requestBody['tx_vfo']);
    build_response(array());
}

function get_trx_tuningstep(int $trx_id){
    build_response(array(
        "tuningstep" => poll_trx($trx_id, "n")[1]
    ));
}

function set_trx_tuningstep($requestBody, int $trx_id=0){
    poll_trx($trx_id, "N " . $requestBody['newValue']);
    build_response(array());
}


function get_trx_level($level_param, int $trx_id){
    build_response(array(
        "level" => poll_trx($trx_id, "l $level_param")[1]
    ));
}

function set_trx_level($level_param, $requestBody, int $trx_id=0){
    poll_trx($trx_id, "L $level_param " . $requestBody['newValue']);
    build_response(array());
}



?>