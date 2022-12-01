<?php include_once 'init.php';

if(empty($_REQUEST['act'])){
    echo "request error!";
    exit();
}

if($act=='crt'){
    if(empty($_POST['domains'])){
        exit('domains is empty!');
    }
    $textarea = trim($_POST['domains']);
    $lists = explode(PHP_EOL, $textarea);
    $array_domains = [];
    if(!empty($lists)){
        foreach ($lists as $key=>$item){
            if(!empty(trim($item))){
                $array_domains[] = $item;
            }
        }
    }
    $array_domains = array_unique($array_domains);
    echo '<title>'.count($array_domains).'</title>';
    if(!empty($array_domains)){
        echo "----------------------------------------------";
        echo "<br/><br/>";
        foreach ($array_domains as $k=>$i){
            echo $i .','. (($k+1)%10 == 0 ? "<br><br/>" : " ");
        }
        echo "<br/>";
        echo "----------------------------------------------";
        echo "<br/><br/>";
        foreach ($array_domains as $k=>$i){
            echo $i ."<br>". (($k+1)%10 == 0 ? "<br/>" : " ");
        }
        echo "<br/><br/>";
    }
}

if($act=='nginx'){
    $nginx = 'nginx.config';
    if(empty($_POST['domains']) || empty($_POST['root']) || empty($_POST['name'])){
        exit('domains root name is empty!');
    }
    $room = filter_nginx_root($_POST['root']);
    $name = $_POST['name'];
    $domains = str_replace(',', ' ', $_POST['domains']);
    $info = []; 
    if(is_file($nginx)){
        $file = file($nginx);
        echo "server<br>{<br>";
        $info [] = "server\r\n{\r\n";
        for($i=0;$i<count($file);$i++){
            $nginx_info = $file[$i];
            $nginx_info = str_replace('$root',$room,$nginx_info);
            $nginx_info = str_replace('$name',$name,$nginx_info);
            $nginx_info = str_replace('$domains',$domains,$nginx_info);
            echo "<code>&nbsp;&nbsp;&nbsp;".$nginx_info."</code><br/>";
            $info [] = "".$nginx_info;
        }
        echo "}";
        $info [] = "\r\n}";
        $text = implode('', $info);
        $text = trim($text);
        $path = ROOT_PATH.$name.'.conf';
        write_file($path, $text);
    }
}

if($act=='cdn'){
    if(empty($_POST['domains']) || empty($_POST['ip'])){
        exit('domains ip is empty!');
    }
    $ip = $_POST['ip'];
    $textarea = trim($_POST['domains']);
    $textarea = str_replace(',', PHP_EOL, $textarea);
    $textarea = str_replace(';', PHP_EOL, $textarea);
    $textarea = str_replace('|', PHP_EOL, $textarea);
    $textarea = str_replace(' ', PHP_EOL, $textarea);
    $lists = explode(PHP_EOL, $textarea);
    $lists = array_unique($lists);
    if(!empty($lists)){
        foreach ($lists as $item){
            if(!empty(trim($item))){
                echo "<strong>".trim($item).'|'.$ip."</strong><br/>";
            }
        }
    }
    echo '<title>'.count($lists).'</title>';
}

if($act=='grep'){
    if(empty($_POST['domains'])){
        exit('domains is empty!');
    }
    $textarea = trim($_POST['domains']);
    $textarea = str_replace(',', PHP_EOL, $textarea);
    $textarea = str_replace(';', PHP_EOL, $textarea);
    $textarea = str_replace('|', PHP_EOL, $textarea);
    $textarea = str_replace(' ', PHP_EOL, $textarea);
    $lists = explode(PHP_EOL, $textarea);
    $lists = array_unique($lists);
    $data = [];
    if(!empty($lists)){
        foreach ($lists as $item){
            if(!empty(trim($item))){
                $data [] = trim($item);
            }
        }
    }
    $grep_message = implode('\|', $data);
    $domain_message = implode(' ', $data);
    echo json_encode(['grep_message'=>$grep_message,'domain_message'=>$domain_message]);
}

if($act=='clean'){
    date_default_timezone_set('Asia/Shanghai');
    exec('cd '.ROOT_PATH.' && rm -rf * ');
    exec('cd '.DEPLOY_PATH.' && rm -rf crt_key.zip nginx_conf.zip');
    $message = 'clean ok '.date('Y-m-d H:i:s');
    echo json_encode(['message'=>$message]);
}