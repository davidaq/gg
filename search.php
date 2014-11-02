<?php
header('Content-type:text/html; charset=utf-8');
$url = 'https://www.google.com.au/search?nfpr=1&spell=&q=' . $_GET['keyword'];
$start = isset($_GET['page']) ? $_GET['page'] * 10 - 10: 0;
if($start)
    $url .= '&start=' . $start;
$UA = 'Mozilla/5.0 (iPhone; U; CPU iPhone OS 4_0 like Mac OS X; en-us) AppleWebKit/532.9 (KHTML, like Gecko) Version/4.0.5 Mobile/8A293 Safari/6531.22.7';
exec("curl --proxy dl.niven.cn:21 -A '$UA' '$url'", $output);
$output = implode('', $output);
$output = preg_replace('/\<script.*>\>.*?<\/script\>/is' ,'', $output);
$output = preg_replace('/\<style.*?\>.*?<\/style\>/is' ,'', $output);
$typo = strstr($output, '>Did you mean');
if($typo) {
    $output = $typo;
    echo '<div class="typo">';
    preg_match('/\<a.*?\>(.*?)\<\/a\>/is', $output, $m);
    $t = preg_replace('/\<.*?\>/is', '', $m[1]);
    echo "<a href='#" . urlencode($t) . "'>$t</a>";
    echo '</div>';
}
preg_match_all('/\<li class="g"\>(.*?)\<\/li\>(?:\<li class="g"\>|\<\/ol\>\<\/div\>\<\/div\>\<\/div\>)/is', $output, $match);
echo '<div class="result">';
foreach($match[1] as $v) {
    echo '<div class="item">';
    echo $v;
    echo '</div>';
}
echo '</div>';
$output = strstr($output, 'Searches related to');
preg_match('/\<table.*?\>(.*?)\<\/table\>/is', $output, $m);
echo '<div class="related">';
echo $m[1];
echo '</div>';
