<?php
define('ROOT_PATH', '/mnt/hgfs/share/test/');
define('DEPLOY_PATH', '/mnt/hgfs/share/');
$act = !empty($_REQUEST['act']) ? $_REQUEST['act'] : '';

function get_ssl_file($filename){
    $file = file($filename);
    $stringbuffer = [];
    for($i=0;$i<count($file);$i++){
        $stringbuffer[] = $file[$i];
    }
    if(!empty($stringbuffer)){
        $strings = implode('', $stringbuffer);
        return trim($strings);
    }
}

function write_file($path,$text){
    $myfile = fopen($path, "w") or die("Unable to open file!");
    fwrite($myfile, $text);
    fclose($myfile);
}

function filter_nginx_root($root){
    if(!empty($root)){
        $root = str_replace(',', '', $root);
        $root = str_replace(';', '', $root);
        $root = str_replace('|', '', $root);
        $root = str_replace('/', '', $root);
        $root = str_replace("\\", '', $root);
        return $root;
    }
}

function write_nginx_config($nginx,$name,$root,$domains){
    if(!empty($nginx) && !empty($name) && !empty($root) && !empty($domains)){
        if(is_file($nginx)){
            $file = file($nginx);
            $info [] = "server\r\n{\r\n";
            for($i=0;$i<count($file);$i++){
                $nginx_info = $file[$i];
                $nginx_info = str_replace('$root',$root,$nginx_info);
                $nginx_info = str_replace('$name',$name,$nginx_info);
                $nginx_info = str_replace('$domains',$domains,$nginx_info);
                $info [] = "".$nginx_info;
            }
            $info [] = "\r\n}";
            $text = implode('', $info);
            $text = trim($text);
            $path = ROOT_PATH.$name.'.conf';
            write_file($path, $text);
        }
    }
}

function write_nginx_domains($name,$domains){
    if(!empty($name) && !empty($domains)){
        $text = trim($domains);
        $path = ROOT_PATH.$name.'.arr';
        write_file($path, $text);
    }
}

function check_domains($domains,$name){
    if(!empty($domains)){
        $domains = (str_replace(PHP_EOL, ' ', $domains));
        $domains =  str_replace(',', ' ', $domains);
        $arrys = explode(' ', $domains);
       var_dump($arrys);exit;
    }
}