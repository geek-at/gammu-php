<?php
$log = date('Y-m-d H:i:s') . PHP_EOL;
$file = 'data/sms.log';

if (php_sapi_name() == "cli") {
    if (!$argv[1] || !$argv[2]) exit("[ERR] Usage: php send.php <phone number> \"<text>\"\n");
    $text = $argv[2];
    $rec = filter_var($argv[1], FILTER_SANITIZE_NUMBER_INT);
} else {
    $text = trim($_REQUEST['text']);
    $rec = trim($_REQUEST['phone']);

    if(!$text && !$rec)
    {
        $inputJSON = file_get_contents('php://input');
        $input = json_decode($inputJSON, TRUE); //convert JSON into array
        $text = $input['text'];
        $rec = $input['phone'];
    }

    if (!$text || !$rec)
        exit(json_encode(['status' => 'err', 'reason' => 'Phone number and text required']));
}


ob_start();
$cmd = sprintf('gammu-smsd-inject TEXT %s -unicode -text %s', $rec, escapeshellarg($text));
$log .= $cmd . PHP_EOL;
$log .= shell_exec($cmd) . PHP_EOL . PHP_EOL;
file_put_contents($file, $log, FILE_APPEND | LOCK_EX);
echo $log;
$cont=  ob_get_contents();
ob_end_clean();
exit(json_encode(['status' => 'ok', 'log' => $log]));