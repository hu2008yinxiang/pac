<?php
header('Content-Type: text/plain;charset=UTF-8', true);
$path = trim(str_ireplace($_SERVER['SCRIPT_NAME'], '', $_SERVER['REQUEST_URI']), '/');
$path = str_ireplace('.pac', '', $path);
list ($host, $port, $db, $type) = explode('/', $path . '///');
if (empty($host) || empty($port)) {
    die('proxy host and port should be set.');
}
if (! in_array($db, array(
    'white',
    'black'
))) {
    $db = 'white';
}

if (! in_array($type, array(
    'PROXY',
    'HTTPS',
    'SOCKS',
    'SOCKS5'
))) {
    $type = 'PROXY';
}
$custom_list = array(
    '.appannie.com'
);
switch ($db) {
    case 'white':
        include __DIR__ . '/WhiteListManager.php';
        die(WhiteListManager::doMerge($host . ':' . $port, $custom_list, $type));
        break;
    case 'black':
        break;
    default:
        ?>
function FindProxyForURL(url, host){
    return "DIRECT; <?php echo $type, ' ',$host,':',$port;?>";
}
        <?php
}
