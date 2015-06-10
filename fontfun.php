<?php
/**
 * ��ȡ���ֵ�������
 *
 * @author    legend <legendsky@hotmail.com> 
 * @link      http://www.ugia.cn/?p=82
 * @Copyright www.ugia.cn  
 */

$str = "�л����񹲺͹�";

$font_file_name   = "simsun12.fon"; // �����ֿ��ļ���
$font_width       = 12;  // ���ֿ���
$font_height      = 12;  // ���ָ߶�
$start_offset     = 0; // ƫ��

$fp = fopen($font_file_name, "rb");

$offset_size = $font_width * $font_height / 8;
$string_size = $font_width * $font_height;
$dot_string  = "";

for ($i = 0; $i < strlen($str); $i ++)
{
    if (ord($str{$i}) > 160)
    {
        // ������λ�룬Ȼ���ټ���������λ���ά���е�λ�ã������ó����ַ����ļ��е�ƫ��
        $offset = ((ord($str{$i}) - 0xa1) * 94 + ord($str{$i + 1}) - 0xa1) * $offset_size;
        $i ++;
    }
    else
    {
        $offset = (ord($str{$i}) + 156 - 1) * $offset_size;        
    }
    
    // ��ȡ���������
    fseek($fp, $start_offset + $offset, SEEK_SET);
    $bindot = fread($fp, $offset_size);
    
    for ($j = 0; $j < $offset_size; $j ++)
    {
        // �������Ƶ�������ת��Ϊ�ַ���
        $dot_string .= sprintf("%08b", ord($bindot{$j}));
    }
}

fclose($fp);

//echo $dot_string;

$rows=str_split($dot_string,(12*12));

foreach ($rows as $key => $value) {
	$row=str_split($value,$font_width);
	foreach ($row as $key2 => $value2) {
		echo str_replace('1','@',$value2).' <br>';
	}
	echo '<br>';
}