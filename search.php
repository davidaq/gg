<?php
$url = 'https://www.google.com.au/search?q=' . $_GET['keyword'];
$UA = 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_6_8) AppleWebKit/534.30 (KHTML, like Gecko) Chrome/12.0.742.112 Safari/534.30';
exec("curl --proxy dl.niven.cn:21 -A '$UA' '$url'", $output);
$output = implode('', $output);
echo $output;
