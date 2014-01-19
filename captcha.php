<?
define('HEIGHT',50);
define('WIDTH',100);
define('FONT_URL','http://www.fontsupply.com/fonts/arial.ttf');
define('FONT_FN','/tmp/arial.ttf');
session_start();
header('Content-type: image/png');

list($string,$answer)=generateData();
$_SESSION['captcha']=$answer;
render($string);
die;

function render($string){
	$im=imagecreate(WIDTH,HEIGHT);
	$back=imagecolorallocate($im,200,200,200);
	imagefill($im,0,0,$back);
	$fontColor=imagecolorexact($im,50,50,50);
	if(!is_file(FONT_FN)){
		file_put_contents(FONT_FN,file_get_contents(FONT_URL));
		chmod(FONT_FN,0777);
	}
	$letters=str_split($string);
	$cn=count($letters);
	$rnd=round(HEIGHT*.05);
	$wPadding=round(WIDTH*.1);
	$wStep=(WIDTH-$wPadding*2)/$cn/3;
	$hPadding=round(HEIGHT*.1);
	$i=0;
	foreach($letters as $l){
		imagefttext($im,HEIGHT/2,rand(-15,15),$wPadding+($i++)*$wStep, $hPadding,
			$fontColor,FONT_FN,$l);
	}
	#add garbage
	imagepng($im);
}

function generateData(){
	$x=rand(1,40);
	$y=rand(1,20);
	$sign=rand(0,1);
	if($sign){
		$string="$x+$y=";
		$answer=$x+$y;
	}else{
		$string="$x-$y=";
		$answer=$x-$y;
	}
	return [$string,$answer];
}

