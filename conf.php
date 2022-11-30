<?php include_once 'init.php';

if($act=='nginx'){
    $nginx = 'nginx.config';
    if(empty($_POST['domains'])){
        exit('domains is empty!');
    }
    $textarea = trim($_POST['domains']);
    $lists = explode(PHP_EOL, $textarea);
    $lists = array_unique($lists);
    $arrylist = [];
    foreach ($lists as $key=>$item){
        $arr = explode("\t", $item);
        $root = str_replace(',', ' ', $arr[1]);
        $arrylist[$key]['name'] = $arr[0];
        $arrylist[$key]['root'] = trim($root);
    }
    if(!empty($arrylist)){
        $root = filter_nginx_root($_POST['root']);
        foreach ($arrylist as $item){
            $name = $item['name'];
            $domains = $item['root'];
            write_nginx_domains($name,$domains);
            write_nginx_config($nginx,$name,$root,$domains);
        }
    }
}

$path = ROOT_PATH;
$nginx_name = $nginx_config = [];
if(!empty(scandir($path))){
    foreach (scandir($path) as $item){
        if(strstr($item, '.conf')){
            $nginx_name[] = str_replace('.conf', '', $item);
            $nginx_config[] = $item;
        }
    }
    exec('cd '.$path.' && zip -r nginx_conf.zip *.conf');
    exec('cd '.$path.' && mv  nginx_conf.zip '.DEPLOY_PATH.' && rm -rf *.conf');
}
?>

<link href="/css/main.css" rel="stylesheet">
<script type="text/javascript" src="/js/jquery-3.5.1.min.js"></script>
<title><?=count($nginx_config)?></title>

<div class="main">
<p><input type="text" style="width:30%;height:3%;font-size:16px;" value="<?= implode(" ", $nginx_name);?>" id="list_name_text"><button type="button" id="list_name_copy">copy</button></p>
</div>

<div class="main">
<p><input type="text" style="width:50%;height:3%;font-size:16px;" value="<?= implode(" ", $nginx_config);?>" id="list_nginx_text"><button type="button" id="list_nginx_copy">copy</button></p>
</div>


<div class="main">
<form action="conf.php?act=nginx" name="frm-nginx" method="post">
    <textarea rows="10%" cols="90%" name="domains" title="input list domain names"></textarea><br/>
    <input type="text" name="root" value="6655.tv">
    <button type="submit">submit</button>
    <button type="reset">reset</button>
</form>
</div>


<script type="text/javascript">
$("#list_name_copy").click(function(){
	   const input = document.querySelector('#list_name_text');
	   input.select();
	   if (document.execCommand('copy')) {
	       document.execCommand('copy');
	       console.log('复制成功');
	   }
});
$("#list_nginx_copy").click(function(){
	   const input = document.querySelector('#list_nginx_text');
	   input.select();
	   if (document.execCommand('copy')) {
	       document.execCommand('copy');
	       console.log('复制成功');
	   }
});
</script>