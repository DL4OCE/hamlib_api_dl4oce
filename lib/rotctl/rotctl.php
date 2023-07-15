<?php

function poll_rotator($rotator_id, $command){
    global $device_array;
    $rotator_data = $device_array['rotators'][$rotator_id];
    if(isset($rotator_data)){
        $rigctl_executable = exec('echo $(whereis rotctl) | awk \'{ print $2 }\'', $output, $result_code);
        // if($GLOBALS["dummy"]==true){
        if($rotator_data['dummy_mode'] == 1){
            $syscall = $rigctl_executable;
        } else {
            // $syscall = "$rigctl_executable -m " . $GLOBALS["model"] . " -r " . $GLOBALS["device"] . " -s 38400";
            $syscall = "$rigctl_executable -m " . $rotator_data['rotctl_model'] . " -r " . $rotator_data['device'];
            if(isset($rotator_data['serial_speed'])) $syscall .= " -s " . $rotator_data['serial_speed'];
        }
        echo "$syscall $command\n";
        // $syscall = "pwd";
        // $result = exec("echo $({$rigctl_executable} {$command})", $output, $result_code);
        $result = exec("$syscall $command", $output, $result_code);
        foreach($output as $line){
            // echo $line."\n";
            if(preg_match('/.*returning2\((.*)\).*/', $line, $matches)){
                // echo "\n" . $matches[1] . "\n\n";// $line;
                $error_code = $matches[1];
            }
        }
        if(isset($error_code)){
            build_error_response_rigctl($output, $error_code, __FUNCTION__);
        } else {
            return($output);
        }   
    } else {
        build_error_response("", "RIGCTL-002", "TRX with id=$rotator_id not defined", __FUNCTION__);
    }
}




function get_rotator_position(int $rotator_id){
    $response = poll_rotator($rotator_id, "p");
    build_response(array(
        "position" => $response
    ));
}

function set_rotator_position($requestBody, int $rotator_id=0){
    poll_rotator($rotator_id, "P " . $requestBody['azimuth'] . " " . $requestBody['elevation']);
    build_response(array());
}

function set_rotator_park($requestBody, int $rotator_id=0){
    poll_rotator($rotator_id, "K");
    build_response(array());
}
function set_rotator_stop($requestBody, int $rotator_id=0){
    poll_rotator($rotator_id, "S");
    build_response(array());
}

function set_rotator_reset($requestBody, int $rotator_id=0){
    poll_rotator($rotator_id, "R");
    build_response(array());
}
function set_rotator_move($requestBody, int $rotator_id=0){
    poll_rotator($rotator_id, "M " . $requestBody['direction'] . " " . $requestBody['speed']);
    build_response(array());
}

// function get_rotator_level(int $rotator_id){
//     $response = poll_rotator($rotator_id, "p");
//     build_response(array(
//         "position" => $response
//     ));
// }

// function set_rotator_position($requestBody, int $rotator_id=0){
//     poll_rotator($rotator_id, "P " . $requestBody['azimuth'] . " " . $requestBody['elevation']);
//     build_response(array());
// }

function get_rotator_level($level_param, int $rotator_id=0){
    poll_rotator($rotator_id, "v $level_param");
    build_response(array());
}
function set_rotator_level($requestBody, $level_param, int $rotator_id=0){
    poll_rotator($rotator_id, "V $level_param " . $requestBody['newValue']);
    build_response(array());
}

function get_rotator_function($function_param, int $rotator_id=0){
    poll_rotator($rotator_id, "u $function_param");
    build_response(array());
}
function set_rotator_function($requestBody, $function_param, int $rotator_id=0){
    poll_rotator($rotator_id, "U $function_param " . $requestBody['newValue']);
    build_response(array());
}

function get_rotator_parameter($parameter, int $rotator_id=0){
    poll_rotator($rotator_id, "u $parameter");
    build_response(array());
}
function set_rotator_parameter($requestBody, $parameter, int $rotator_id=0){
    poll_rotator($rotator_id, "U $parameter " . $requestBody['newValue']);
    build_response(array());
}

function get_rotator_info(int $rotator_id){
    $response = poll_rotator($rotator_id, "_");
    build_response(array(
        "info" => $response
    ));
}

function get_rotator_status(int $rotator_id){
    $response = poll_rotator($rotator_id, "s");
    build_response(array(
        "status" => $response
    ));
}

function get_rotator_state(int $rotator_id){
    $response = poll_rotator($rotator_id, "dump_state");
    build_response(array(
        "state" => $response
    ));
}

function get_rotator_capabilities(int $rotator_id){
    $response = poll_rotator($rotator_id, "1");
    build_response(array(
        "capabilities" => $response
    ));
}



?>