<?php
function poll_trx($trx_id, $command){
    global $device_array;
    $trx_data = $device_array['transceivers'][$trx_id];
    if(isset($trx_data)){
        $rigctl_executable = exec('echo $(whereis rigctl) | awk \'{ print $2 }\'', $output, $result_code);
        if($trx_data['dummy_mode'] == 1){
            $syscall = $rigctl_executable;
        } else {
            $syscall = "$rigctl_executable -m " . $trx_data['rigctl_model'] . " -r " . $trx_data['device'] . " -s 38400";
        }
        echo "$syscall $command\n";
        $result = exec("$syscall $command", $output, $result_code);
        foreach($output as $line){
            if(preg_match('/.*returning2\((.*)\).*/', $line, $matches)){
                $error_code = $matches[1];
            }
        }
        if(isset($error_code)){
            build_error_response_rigctl($output, $error_code, __FUNCTION__);
        } else {
            return($output);
        }   
    } else {
        build_error_response("", "RIGCTL-002", "TRX with id=$trx_id not defined", __FUNCTION__);
    }
}

function get_trx_frequency(int $trx_id=0){
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

function get_trx_level_list(int $trx_id){
    $response = poll_trx($trx_id, "l ?");
    $capabilities = [];
    foreach(explode(" ", $response[1]) as $capability) array_push($capabilities, $capability);
    $capabilities = array(
        "capabilities" => $capabilities
    );
    build_response($capabilities);
}

function set_trx_level($level_param, $requestBody, int $trx_id=0){
    poll_trx($trx_id, "L $level_param " . $requestBody['newValue']);
    build_response(array());
}


function get_trx_function($function_param, int $trx_id){
    build_response(array(
        "function" => poll_trx($trx_id, "u $function_param")[1]
    ));
}

function get_trx_function_list(int $trx_id){
    $response = poll_trx($trx_id, "u ?");
    $capabilities = [];
    foreach(explode(" ", $response[1]) as $capability) array_push($capabilities, $capability);
    $capabilities = array(
        "capabilities" => $capabilities
    );
    build_response($capabilities);
}

function set_trx_function($function_param, $requestBody, int $trx_id=0){
    poll_trx($trx_id, "U $function_param " . $requestBody['newValue']);
    build_response(array());
}

function get_trx_parameter($parameter, int $trx_id){
    build_response(array(
        "function" => poll_trx($trx_id, "p $parameter")[1]
    ));
}

function get_trx_parameter_list(int $trx_id){
    $response = poll_trx($trx_id, "u ?");
    $capabilities = [];
    foreach(explode(" ", $response[1]) as $capability) array_push($capabilities, $capability);
    $capabilities = array(
        "capabilities" => $capabilities
    );
    build_response($capabilities);
}

function set_trx_parameter($parameter, $requestBody, int $trx_id=0){
    poll_trx($trx_id, "U $parameter " . $requestBody['newValue']);
    build_response(array());
}

function get_trx_scan($scan_parameter, int $trx_id){
    build_response(array(
        "function" => poll_trx($trx_id, "p $scan_parameter")[1]
    ));
}

function get_trx_scan_list(int $trx_id){
    $response = poll_trx($trx_id, "u ?");
    $capabilities = [];
    foreach(explode(" ", $response[1]) as $capability) array_push($capabilities, $capability);
    $capabilities = array(
        "capabilities" => $capabilities
    );
    build_response($capabilities);
}

function set_trx_scan($scan_parameter, $requestBody, int $trx_id=0){
    poll_trx($trx_id, "U $scan_parameter " . $requestBody['newValue']);
    build_response(array());
}

function get_trx_transceive($transceive_parameter, int $trx_id){
    build_response(array(
        "function" => poll_trx($trx_id, "a $transceive_parameter")[1]
    ));
}

function get_trx_transceive_list(int $trx_id){
    $response = poll_trx($trx_id, "A ?");
    $capabilities = [];
    foreach(explode(" ", $response[1]) as $capability) array_push($capabilities, $capability);
    $capabilities = array(
        "capabilities" => $capabilities
    );
    build_response($capabilities);
}

function set_trx_transceive($requestBody, int $trx_id=0){
    poll_trx($trx_id, "U $transceive_parameter " . $requestBody['newValue']);
    build_response(array());
}

function get_trx_repeater_shift(int $trx_id){
    $response = poll_trx($trx_id, "r");
    build_response(array(
        "shift_direction" => $response[1]
    ));
}

function set_trx_repeater_shift($requestBody, int $trx_id=0){
    poll_trx($trx_id, "R " . $requestBody['newValue']);
    build_response(array());
}

function get_trx_repeater_offset(int $trx_id){
    $response = poll_trx($trx_id, "o");
    build_response(array(
        "repeater_offset" => $response[1]
    ));
}

function set_trx_repeater_offset($requestBody, int $trx_id=0){
    poll_trx($trx_id, "O " . $requestBody['newValue']);
    build_response(array());
}

function get_trx_ctcss_tone(int $trx_id){
    $response = poll_trx($trx_id, "c");
    build_response(array(
        "ctcss_tone" => $response[1]
    ));
}

function set_trx_ctcss_tone($requestBody, int $trx_id=0){
    poll_trx($trx_id, "C " . $requestBody['newValue']);
    build_response(array());
}

function get_trx_dcs_tone(int $trx_id){
    $response = poll_trx($trx_id, "d");
    build_response(array(
        "dcs_tone" => $response[1]
    ));
}

function set_trx_dcs_tone($requestBody, int $trx_id=0){
    poll_trx($trx_id, "D " . $requestBody['newValue']);
    build_response(array());
}

function get_trx_vfo(int $trx_id){
    $response = poll_trx($trx_id, "v");
    build_response(array(
        "vfo_name" => $response[1]
    ));
}

function set_trx_vfo($requestBody, int $trx_id=0){
    poll_trx($trx_id, "V " . $requestBody['newValue']);
    build_response(array());
}

function get_trx_ptt(int $trx_id){
    $response = poll_trx($trx_id, "t");
    build_response(array(
        "ptt" => $response[1]
    ));
}

function set_trx_ptt($requestBody, int $trx_id=0){
    poll_trx($trx_id, "T " . $requestBody['newValue']);
    build_response(array());
}

function get_trx_memory(int $trx_id){
    $response = poll_trx($trx_id, "e");
    build_response(array(
        "memory" => $response[1]
    ));
}

function set_trx_memory($requestBody, int $trx_id=0){
    poll_trx($trx_id, "E " . $requestBody['newValue']);
    build_response(array());
}

function get_trx_channel(int $trx_id){
    $response = poll_trx($trx_id, "h");
    build_response(array(
        "channel" => $response[1]
    ));
}

function set_trx_channel($requestBody, int $trx_id=0){
    poll_trx($trx_id, "H " . $requestBody['newValue']);
    build_response(array());
}

function get_trx_info(int $trx_id){
    $response = poll_trx($trx_id, "_");
    build_response(array(
        "info" => $response[1]
    ));
}

function get_trx_rit(int $trx_id){
    $response = poll_trx($trx_id, "j");
    build_response(array(
        "rit" => $response[1]
    ));
}

function set_trx_rit($requestBody, int $trx_id=0){
    poll_trx($trx_id, "J " . $requestBody['newValue']);
    build_response(array());
}



function get_trx_xit(int $trx_id){
    $response = poll_trx($trx_id, "z");
    build_response(array(
        "xit" => $response[1]
    ));
}

function set_trx_xit($requestBody, int $trx_id=0){
    poll_trx($trx_id, "Z " . $requestBody['newValue']);
    build_response(array());
}

function get_trx_antenna(int $trx_id){
    $response = poll_trx($trx_id, "y 0");
    build_response(array(
        "antennas" => array_slice($response, 1, sizeof($response)-1)
    ));
}

function set_trx_antenna($requestBody, int $trx_id=0){
    poll_trx($trx_id, "Y " . $requestBody['newValue']);
    build_response(array());
}

function set_trx_raw_command(int $trx_id){
    $response = poll_trx($trx_id, "w " . $requestBody['raw_command']);
    build_response(array(
        "antennas" => array_slice($response, 1, sizeof($response)-1)
    ));
}

function set_trx_raw_command_rx($requestBody, int $trx_id=0){
    poll_trx($trx_id, "W " . $requestBody['raw_command'] . $requestBody['number_of_expected_rx_bytes'] );
    build_response(array());
}

function get_trx_mw_power($requestBody, int $trx_id=0){
    $response = poll_trx($trx_id, "4 " . $requestBody['power_mW'] . " " . $requestBody['frequency'] . " " . $requestBody['mode'] );
    build_response(array(
        "power_factor" => $response[1]
    ));
}

function get_trx_power_mw($requestBody, int $trx_id=0){
    $response = poll_trx($trx_id, "2 " . $requestBody['power_factor'] . " " . $requestBody['frequency'] . " " . $requestBody['mode'] );
    build_response(array(
        "power_mw" => $response[1]
    ));
}

function set_trx_capabilities(int $trx_id=0){
    $response = poll_trx($trx_id, "1");
    build_response(array(
        "response" => $response
    ));
}

function set_trx_configuration(int $trx_id=0){
    $response = poll_trx($trx_id, "3");
    build_response(array(
        "response" => $response
    ));
}

function set_trx_morse($requestBody, int $trx_id=0){
    $response = poll_trx($trx_id, "b " . $requestBody['text']);
    build_response(array(
        "response" => $response[1]
    ));
}

function set_trx_morse_stop($requestBody, int $trx_id=0){
    $response = poll_trx($trx_id, "stop_morse ");
    build_response(array(
        "response" => $response[1]
    ));
}

function get_trx_ctcss_sql(int $trx_id){
    $response = poll_trx($trx_id, "get_ctcss_sql");
    build_response(array(
        "ctcss_sql" => $response[1]
    ));
}

function set_trx_ctcss_sql($requestBody, int $trx_id){
    $response = poll_trx($trx_id, "set_ctcss_sql " . $requestBody['newValue']) ;
    build_response(array(
        "ctcss_sql" => $response
    ));
}

function get_trx_dcs_sql(int $trx_id){
    $response = poll_trx($trx_id, "get_dcs_sql");
    build_response(array(
        "dcs_sql" => $response[1]
    ));
}

function set_trx_dcs_sql($requestBody, int $trx_id){
    $response = poll_trx($trx_id, "set_dcs_sql " . $requestBody['newValue']) ;
    build_response(array(
        "ctcss_sql" => $response
    ));
}

function get_trx_dtmf(int $trx_id){
    $response = poll_trx($trx_id, "recv_dtmf");
    build_response(array(
        "dtmf" => $response[1]
    ));
}

function get_trx_morse(int $trx_id){
    $response = poll_trx($trx_id, "wait_morse");
    build_response(array(
        "text " => $response
    ));
}

function get_trx_dcd(int $trx_id){
    $response = poll_trx($trx_id, "get_dcd");
    build_response(array(
        "dcd " => $response[1]
    ));
}

function get_trx_twiddle(int $trx_id){
    $response = poll_trx($trx_id, "get_twiddle");
    build_response(array(
        "twiddle " => $response[1]
    ));
}

function get_trx_cache(int $trx_id){
    $response = poll_trx($trx_id, "get_cache");
    build_response(array(
        "cache " => $response[1]
    ));
}

function set_trx_cache($requestBody, int $trx_id=0){
    $response = poll_trx($trx_id, "set_cache " . $requestBody['newValue']);
    build_response(array(
        "response" => $response
    ));
}

function set_trx_state($requestBody, int $trx_id=0){
    $response = poll_trx($trx_id, "dump_state");
    build_response(array(
        "response" => $response
    ));
}

function get_trx_rig_info($requestBody, int $trx_id=0){
    $response = poll_trx($trx_id, "get_rig_info");
    build_response(array(
        "response" => $response
    ));
}

function get_trx_modes($requestBody, int $trx_id=0){
    $response = poll_trx($trx_id, "get_modes");
    build_response(array(
        "response" => $response
    ));
}

function get_trx_power_state(int $trx_id){
    $response = poll_trx($trx_id, "get_powerstat");
    build_response(array(
        "power_state" => $response
    ));
}

function set_trx_power_state($requestBody, int $trx_id=0){
    poll_trx($trx_id, "set_powerstate " . $requestBody['newValue']);
    build_response(array());
}

function set_trx_dtmf($requestBody, int $trx_id=0){
    poll_trx($trx_id, "send_dtmf " . $requestBody['newValue']);
    build_response(array());
}

function set_trx_voice_mem($requestBody, int $trx_id=0){
    poll_trx($trx_id, "send_voice_mem " . $requestBody['newValue']);
    build_response(array());
}

function set_trx_twiddle($requestBody, int $trx_id=0){
    poll_trx($trx_id, "set_twiddle " . $requestBody['newValue']);
    build_response(array());
}






// $router->get('/trx/(\d+)/function/(\w+)', function($trx_id, $level_param) { echo ($function_param, $trx_id);});
// $router->post('/trx/(\d+)/function/(\w+)', function($trx_id, $level_param) { echo ($function_param, json_decode(getRequestBody(), true), $trx_id);});
// $router->get('/trx/(\d+)/function', function($trx_id) { echo ($trx_id);});


?>