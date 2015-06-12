<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
<title>Examples</title>
<meta name="description" content="">
<meta name="keywords" content="">
<link href="" rel="stylesheet">
<style type="text/css">
	textarea{
		margin: 0;
		padding: 0;
		border: none;
		width: 100%;
		height: 600px;
		font-size: 12px;
	}
</style>
</head>
<body style="font-size:24px;">
    <?php
/**
 * 读取汉字点阵数据
 *
 * @author    legend <legendsky@hotmail.com> 
 * @link      http://www.ugia.cn/?p=82
 * @Copyright www.ugia.cn  
 */

$str = "中国民423142134人14234民1234共和斯拉@@夫@$%^*&(*)(dasg好！fafafasdfasdf。13241234.Welcome!";
$str = iconv('UTF-8', 'GB2312', $str);

//echo strlen($str).'<br>';

$font_file_name   = "simsun12.fon"; // 点阵字库文件名
$font_width       = 12;  // 单字宽度
$font_height      = 12;  // 单字高度
$start_offset     = 0; // 偏移

$fp = fopen($font_file_name, "rb");

$offset_size = $font_width * $font_height / 8;
$string_size = $font_width * $font_height;
$dot_string  = "";

for ($i = 0; $i < strlen($str); $i ++)
{
    if (ord($str{$i}) > 160)
    {
        // 先求区位码，然后再计算其在区位码二维表中的位置，进而得出此字符在文件中的偏移
        $offset = ((ord($str{$i}) - 0xa1) * 94 + ord($str{$i + 1}) - 0xa1) * $offset_size;
        $i ++;
    }
    else
    {
        $offset = (ord($str{$i}) + 156 - 1) * $offset_size;        
    }
    
    // 读取其点阵数据
    fseek($fp, $start_offset + $offset, SEEK_SET);
    $bindot = fread($fp, $offset_size);
    
    for ($j = 0; $j < $offset_size; $j ++)
    {
        // 将二进制点阵数据转化为字符串
        $dot_string .= sprintf("%08b", ord($bindot{$j}));
    }
}

fclose($fp);

//echo $dot_string;

//echo mb_strlen($str,'gb2312');
$str = iconv('GB2312', 'UTF-8', $str);
$rows=str_split($dot_string,$font_width);

//echo mb_strlen($str,'utf8');

$b_str='';

$n=0;

$w=5;//一行5个字

$h=ceil(count($rows)/($font_height*$w));//共几行

$sn=mb_strlen($str,'utf8');//总字数

for ($c=0; $c < $h; $c++) {
	$nn=$n=$c*$w*12;
	$w=($c==($h-1)&&($sn-$c*$w)!=0)? ($sn-$c*$w) : $w;
	for ($i=0; $i < $font_height; $i++) {
		//var_dump($w);
		for ($r=0; $r < $w; $r++) {
			$b_str.=str_replace('1','说',str_replace('0', '　', $rows[$n])).'　';
			$n+=$font_height;
		}
		$n=($c==0)? ($i+1) : ($i+$nn);
		$b_str.="\r\n";
	}
	$b_str.="\r\n";
}



// for ($i=0; $i < 12; $i++) {
// 	$n=$i;
// 	for ($r=0; $r < 10; $r++) {
// 		$b_str.=$n.'　　　';
// 		$n+=12;
// 	}
// 	$b_str.="<br>";
// }

// echo $b_str;






// for ($i=0; $i < $font_height; $i++) {
// 	$n=$i;
// 	for ($r=0; $r < mb_strlen($str,'utf8'); $r++) {
// 		$b_str.=str_replace('1','说',str_replace('0', '　', $rows[$n])).'　';
// 		$n+=$font_height;
// 	}
// 	$b_str.="\r\n";
// }
echo '<textarea readonly>'.$b_str.'</textarea>';
//echo '<textarea readonly>'.$b_str.'</textarea>';
?>
</body>
</html>