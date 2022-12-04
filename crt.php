<?php include_once 'init.php';
$path = ROOT_PATH;
$nginx_zip = [];
if(!empty(scandir($path))){
    foreach (scandir($path) as $item){
        if(strstr($item, '.zip')){
            $nginx_zip[] = $item;
            exec('cd '.$path.' && unzip '.$item);
        }
    }
}

$nginx_name = [];
if(!empty($nginx_zip)){
    foreach ($nginx_zip as $item){
        $nginx_name_zip = explode('nginx',$item);
        $domain = $nginx_name_zip[0];
        $domain = str_replace('-', '.', $domain);
        $domain = rtrim($domain,'.');
        if(!empty($domain)){
            $nginx_name[] = $domain;
        }
    }
}

if(!empty($nginx_name)){
    $crt = [];
    foreach ($nginx_name as $item){
        $crt[] = $item;
        $dir_name = $path.$item;
        if(!file_exists($dir_name)){
            mkdir($dir_name);
            $filename = $path.$item.'_key.key';
            if(file_exists($filename)){
                exec('mv '.$filename.' '.$dir_name.'/ssl.key');
            }
            $filename = $path.$item.'_chain.crt';
            if(file_exists($filename)){
                exec('mv '.$filename.' '.$dir_name.'/ssl.crt');
            }
        }
    }
    exec('cd '.$path.' && zip -r crt_key.zip '.implode('/ ', $crt).'/');
    exec('cd '.$path.' && mv  crt_key.zip '.DEPLOY_PATH);
}

$filename = $path.'crt_key.zip';
if(file_exists($filename)){
    foreach ($nginx_zip as $item){
        if($item != 'crt_key.zip'){
            unlink($path.$item);
        }
    }
    echo "<title>"."crt key ops Sucess!"."</title>";
}

$root = $list_name = $list_conf = [];
if(!empty(scandir($path))){
    foreach (scandir($path) as $k=>$item){
        $filename = $path.$item;
        if($item != '.' && $item != '..' && is_dir($filename) && file_exists($filename)){
            $root[$k]['name'] = $item;
            $root[$k]['key'] = get_ssl_file($filename.'/ssl.key');
            $root[$k]['crt'] = get_ssl_file($filename.'/ssl.crt');
            $list_name [] = $item;
            $list_conf[] = $item.'.conf';
        }
    }
}
?>
<link id="favicon" rel="shortcut icon" href="favicon.svg" type="image/svg+xml" />
<link href="/css/main.css" rel="stylesheet">
<script type="text/javascript" src="/js/jquery-3.5.1.min.js"></script>

<div class="main">
<p><input type="text" style="width:30%;" value="<?= implode(" ", $list_name);?>" id="list_name_text"><button type="button" id="list_name_copy">copy</button></p>
<p><input type="text" style="width:50%;" value="<?= implode(" ", $list_conf);?>" id="list_conf_text"><button type="button" id="list_conf_copy">copy</button></p>
</div>

<?php foreach ($root as $key=>$val){ ?>
<div class="main">
<p><input type="text" value="<?=$val['name']?>" id="room_text<?=$key?>"><button type="button" id="room_copy<?=$key?>">copy</button></p>
<p><textarea rows="3%" cols="90%" id="crt_text<?=$key?>"><?=$val['crt']?></textarea><br/>crt<button type="button" id="crt_copy<?=$key?>">copy</button></p>
<p><textarea rows="2%" cols="90%" id="key_text<?=$key?>"><?=$val['key']?></textarea><br/>key<button type="button" id="key_copy<?=$key?>">copy</button></p>
</div>
<?php }?>


<script type="text/javascript">
$("#list_name_copy").click(function(){
	   const input = document.querySelector('#list_name_text');
	   input.select();
	   if (document.execCommand('copy')) {
	       document.execCommand('copy');
	       console.log('复制成功');
	   }
});
$("#list_conf_copy").click(function(){
	   const input = document.querySelector('#list_conf_text');
	   input.select();
	   if (document.execCommand('copy')) {
	       document.execCommand('copy');
	       console.log('复制成功');
	   }
});
<?php foreach ($root as $key=>$name){ ?>
$("#room_copy<?=$key?>").click(function(){
	   const input = document.querySelector('#room_text<?=$key?>');
	   input.select();
	   if (document.execCommand('copy')) {
	       document.execCommand('copy');
	       console.log('复制成功');
	   }
});
$("#key_copy<?=$key?>").click(function(){
	   const input = document.querySelector('#key_text<?=$key?>');
	   input.select();
	   if (document.execCommand('copy')) {
	       document.execCommand('copy');
	       console.log('复制成功');
	   }
});
$("#crt_copy<?=$key?>").click(function(){
	const input = document.querySelector('#crt_text<?=$key?>');
   input.select();
   if (document.execCommand('copy')) {
       document.execCommand('copy');
       console.log('复制成功');
   }
});
<?php }?>
</script>