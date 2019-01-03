<?php

global $Queue, $Text, $CQ;
use kjBot\Frame\Message;
requireSeniorAdmin();

if($Text == '')leave('没有参数！');

$escape = false;
$async = false;
$toGroup = false;
$toPerson = false;

do{
    $arg = nextArg();
    switch($arg){
        case '-escape':
            $escape = true;
            break;
        case '-async':
            $async = true;
            break;
        case '-toGroup':
            $toGroup = true;
            $id = nextArg();
            break;
        case '-toPerson':
            $toPerson = true;
            $id = nextArg();
            break;
        default:

    }
}while($arg !== NULL);

if($toGroup){
    if($async)
        $msgID = $CQ->sendGroupMsgAsync($id, $Text, $escape);
    else
        $msgID = $CQ->sendGroupMsg($id, $Text, $escape);
}else if($toPerson){
    if($async)
        $msgID = $CQ->sendPrivateMsgAsync($id, $Text, $escape);
    else
        $msgID = $CQ->sendPrivateMsg($id, $Text, $escape);
}else{
    $Queue[]= sendBack($Text, $escape, $async);
}

if($msgID)
    $Queue[]= sendBack("消息ID：".$msgID);

?>
