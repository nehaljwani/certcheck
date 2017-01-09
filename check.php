<?php

    $fqdn = $_POST["fqdn"];
    $port = $_POST["port"];
    $get = stream_context_create(array("ssl" => array("capture_peer_cert" => TRUE)));
    $read = stream_socket_client("ssl://".$fqdn.":".$port, $errno, $errstr, 30, STREAM_CLIENT_CONNECT, $get);
    $cert = stream_context_get_params($read);
    $certinfo = openssl_x509_parse($cert['options']['ssl']['peer_certificate']);

    print_r(json_encode($certinfo));

?>
