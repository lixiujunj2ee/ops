<?php include_once 'init.php';
$path = ROOT_PATH;

$root = $list_name = $list_arr = [];
if(!empty(scandir($path))){
    foreach (scandir($path) as $k=>$item){
        $filename = $path.$item;
        if($item != '.' && $item != '..' && strstr($item, '.arr') && file_exists($filename)){
            $root[$k]['name'] = str_replace('.arr', '', $item);
            $text = file_get_contents($filename);
            $root[$k]['count'] = count(explode(' ',$text)); 
            $text = str_replace(' ', "\n", $text);
            $root[$k]['arr'] = $text;
        }
    }
}


?>
<link id="favicon" rel="shortcut icon" href="favicon.svg" type="image/svg+xml" />
<link href="/css/main.css" rel="stylesheet">
<script type="text/javascript" src="/js/jquery-3.5.1.min.js"></script>
<title><?=count($root);?></title>


<?php foreach ($root as $key=>$val){ ?>
<div class="main">
<p><textarea rows="2%" cols="90%" id="arr_text<?=$key?>"><?=$val['arr']?></textarea><br/><?=$val['name']?> cdn ssl<button type="button" id="arr_copy<?=$key?>">copy</button>[<?=$val['count']?>]</p>
</div>
<?php }?>


<script type="text/javascript">
<?php foreach ($root as $key=>$name){ ?>
$("#name_copy<?=$key?>").click(function(){
	   const input = document.querySelector('#name_text<?=$key?>');
	   input.select();
	   if (document.execCommand('copy')) {
	       document.execCommand('copy');
	       console.log('复制成功');
	   }
});
$("#arr_copy<?=$key?>").click(function(){
	   const input = document.querySelector('#arr_text<?=$key?>');
	   input.select();
	   if (document.execCommand('copy')) {
	       document.execCommand('copy');
	       console.log('复制成功');
	   }
});
<?php }?>
</script>