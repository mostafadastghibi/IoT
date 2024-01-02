<?php

$path = "./data.txt";

if (isset($_REQUEST['getState'])) {
    if (file_exists($path)) {
        echo file_get_contents($path);
    }
}

if (isset($_REQUEST['setState'])) {
    http_response_code(200);
    file_put_contents($path, $_REQUEST['setState']);
}

if (file_exists($path)) {
    if (file_get_contents($path) == true) {
        echo "on";
    } else {
        echo "off";
    }
}
