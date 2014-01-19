<?
define('HEIGHT',30);
define('WIDTH',100);
define('FONT_URL','https://github.com/13DaGGeR/oneFileCaptcha/blob/master/arial.ttf?raw=true');
define('FONT_FN','/tmp/arial.ttf');
session_start();
header('Content-type: image/png');

list($string,$answer)=generateData();
$_SESSION['captcha']=$answer;
render($string);
die;

function render($string){
	$im=imagecreatetruecolor(WIDTH,HEIGHT);
	$back=imagecolorallocate($im,200,200,200);
	imagefill($im,0,0,$back);
	$fontColor=imagecolorallocate($im,50,50,50);
	if(!is_file(FONT_FN)){
		file_put_contents(FONT_FN,file_get_contents(FONT_URL));
		chmod(FONT_FN,0777);
	}
	$letters=str_split($string);
	$cn=count($letters);
	$rnd=round(HEIGHT*.01);
	$wPadding=round(WIDTH*.1);
	$wStep=(WIDTH-$wPadding*2)/$cn;
	$hPadding=round(HEIGHT/2)+round(HEIGHT/4);
	$i=0;
	foreach($letters as $l){
		imagefttext($im,HEIGHT/2,rand(-15,15),
			$wPadding+($i++)*$wStep+rand(-1*$rnd,$rnd), 
			$hPadding+rand(-1*$rnd,$rnd),
			$fontColor,FONT_FN,$l);
	}
	#garbage($im,$fontColor);
	imagepng($im);
}

function generateData(){
	$x=rand(1,40);
	$y=rand(1,$x);
	$sign=rand(0,1);
	if($sign){
		$string="$x + $y =";
		$answer=$x+$y;
	}else{
		$string="$x - $y =";
		$answer=$x-$y;
	}
	return [$string,$answer];
}
function garbage($im,$color){
	for($i=0;$i<5;++$i){
		imageline($im,rand(0,WIDTH),rand(0,HEIGHT),rand(0,WIDTH),rand(0,HEIGHT), $color);
	}
}
