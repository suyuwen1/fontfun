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
	span{
		display: inline-block;
		margin: 0;
		padding: 0 5px;
	}
</style>
</head>
<body style="font-size:10px;">
    <?php
/**
 * 读取汉字点阵数据
 *
 * @author    legend <legendsky@hotmail.com> 
 * @link      http://www.ugia.cn/?p=82
 * @Copyright www.ugia.cn  
 */

$str = "欢迎来say588!";
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

$b_str='';
for ($i=0; $i < $font_height; $i++) {
	$n=$i;
	for ($r=0; $r < mb_strlen($str,'utf8'); $r++) {
		$b_str.=str_replace('1','说',str_replace('0', '　', $rows[$n])).'　';
		$n+=$font_height;
	}
	$b_str.="\r\n";
}
echo '<textarea readonly style="height:400px;width:100%;">'.$b_str.'</textarea>';
?>
</body>
</html>