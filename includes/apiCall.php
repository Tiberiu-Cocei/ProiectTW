<?php
function ApiCall($method, $url, $data = false){
    $curl = curl_init();


    curl_setopt($curl, CURLOPT_POST, 1);
    if ($data)
    curl_setopt($curl, CURLOPT_POSTFIELDS, $data);


    curl_setopt($curl, CURLOPT_URL, $url);
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);

    $result = curl_exec($curl);

    curl_close($curl);

    return $result;
}
?>
