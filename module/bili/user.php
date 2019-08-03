<?php

	global $Queue, $Event;
	$api = "http://api.bilibili.com/x/relation/stat?vmid=";
	$api2 = "https://api.bilibili.com/x/space/acc/info?mid=";

	$uid = nextArg();
	if(parseQQ($uid))$uid = getData("bili/user/".parseQQ($uid));
	if(!$uid)$uid = getData("bili/user/".$Event['user_id']);
	if($uid == "")leave("请提供uid！如需绑定请使用 #bili.bind ！");
	else if(intval($uid) === NULL)leave('请提供纯数字uid！');
	if(!($data = json_decode(file_get_contents($api.$uid),ture)['data']))leave('查询失败！');
	if(!($data2 = json_decode(file_get_contents($api2.$uid),ture)['data']))leave('查询失败！');

	$following = $data['following'];
	$black = $data['black'];
	$follower = $data['follower'];
	$name = $data2['name'];
	$sign = $data2['sign'];
	$face = $data2['face'];
	$level = $data2['level'];
	$coins = $data2['coins'];
	$official = $data2['official']['title'];
	$sex = $data2['sex'];
	$official = $official?"官方认证：".$official:"暂未进行个人认证";

	do{
		$n += 1;
		$api3 = "http://space.bilibili.com/ajax/member/getSubmitVideos?pagesize=100&tid=0&page=".$n."&keyword=&order=pubdate&mid=".$uid;
		$data = json_decode(file_get_contents($api3), true)['data'];
		$vlists[] = $data['vlist'];
	}while($data['pages'] > $n);

	foreach($vlists as $vlist)
		foreach($vlist as $video){
		$duration = explode(":", $video['length']);
		$minutes = intval($duration[0]);
		$seconds = intval($duration[1]);
		$sumseconds += 60*$minutes + $seconds;
		$sumplay += $video['play'];
	}

	$sumtime = $sumseconds?"看完".($sex == "女"?"她":"他")."的全部视频需要".intval($sumseconds / 86400)."天".intval($sumseconds % 86400 / 3600)."小时".intval($sumseconds % 3600 / 60)."分钟".($sumseconds % 60)."秒":($sex == "女"?"她":"他")."还没有发过视频嗷";

	$msg = <<<EOT
Bilibili 用户 uid{$uid} 的数据：
[CQ:image,file={$face}]
{$name}
{$sign}
{$official}
{$sumtime}

{$level}级/{$following}关注/{$follower}粉丝/{$sumplay}播放
EOT;
	$Queue[]= sendBack($msg);

?>
