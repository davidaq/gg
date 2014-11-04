<?php
function fetchUrl($url, $proxy=false, $UA=NULL) {
    $curl = curl_init();
    curl_setopt_array($curl, array(
        CURLOPT_URL            => $url,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_FOLLOWLOCATION => true,
    ));
    if($proxy) 
        curl_setopt($curl, CURLOPT_PROXY, 'dl.niven.cn:21');
    if($UA)
        curl_setopt($curl, CURLOPT_USERAGENT, $UA);
    $output = curl_exec($curl);
    return $output;
}
$UA = array();
$UA['iphone'] = 'Mozilla/5.0 (iPhone; U; CPU iPhone OS 4_0 like Mac OS X; en-us) AppleWebKit/532.9 (KHTML, like Gecko) Version/4.0.5 Mobile/8A293 Safari/6531.22.7';

$url = $_GET['q'];
$host = parse_url($url, PHP_URL_HOST);
$hostparts = explode('.', $host);
if(isset($_GET['gg'])) {
    if(isset($hostparts[2]) && !is_numeric($hostparts[0])) {
        unset($hostparts[0]);
        $host = implode('.', $hostparts);
    }
    if(in_array($host, array(
        'serverfault.com',
        'stackoverflow.com',
        'stackexchange.com',
    ))) {
        $output = fetchUrl($url);
        $output = str_replace('//ajax.googleapis.com/ajax/libs/jquery/1.7.1/jquery.min.js', 
                        'http://cdn.bootcss.com/jquery/1.7.1/jquery.min.js', $output);
        $output = str_replace('<head>', '<head><base href="//' . $host . '"/>', $output);
        echo $output;
    } else {
        $output = fetchUrl($url, true, $UA['iphone']);
        $output = preg_replace('/\<iframe.*?\>\<\/iframe\>/is', '', $output);
        $output = preg_replace('/\<link.*?(wordpress|google|blogspot\.com|blogger.com).*?\>/', '', $output);
        $output = preg_replace('/\<script.*?(wordpress|google|blogspot\.com)|blogger.com.*?\>\<\/script\>/', '', $output);
        echo $output;
    }
} else {
    header('location:' . $url);
}
