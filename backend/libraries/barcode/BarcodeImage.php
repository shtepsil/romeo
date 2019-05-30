<?

namespace backend\libraries\barcode;

use backend\controllers\MainController as d;
use Yii;

class BarcodeImage{

    private static function barcode_encode_bars($ean, $encoding = "EAN-13"){

        $digits=array(3211,2221,2122,1411,1132,1231,1114,1312,1213,3112);
        $mirror=array("000000","001011","001101","001110","010011","011001","011100","010101","010110","011010");
        $guards=array("9a1a","1a1a1","a1a");

        $line=$guards[0];
        for ($i=1;$i<13;$i++){
            $str=$digits[$ean[$i]];
            if ($i<7 && $mirror[$ean[0]][$i-1]==1) $line.=strrev($str); else $line.=$str;
            if ($i==6) $line.=$guards[1];
        }
        $line.=$guards[2];

        /* create text */
        $pos=0;
        $text="";
        for ($a=0;$a<13;$a++){
            if ($a>0) $text.=" ";
            $text.="$pos:12:{$ean[$a]}";
            if ($a==0) $pos+=12;
            else if ($a==6) $pos+=12;
            else $pos+=7;
        }

        $arr = array(
            "encoding" => $encoding,
            "bars" => $line,
            "text" => $text
        );

        return $arr;
    }

    private static function barcode_outimage($text, $bars, $scale = 1, $mode = "png",
                              $total_y = 0, $space = ''){

        $bar_color=array(0,0,0);// цвет штрихов
        $bg_color=array(255,255,255);// цвет фона
        $text_color=array(0,0,0);// цвет шрифта цифр

        $font_loc = Yii::getAlias('@webroot').'/fonts/FreeSansBold.ttf';

        /* set defaults */
        if ($scale<1) $scale=2;
        $total_y=(int)($total_y);
        if ($total_y<1) $total_y=(int)$scale * 60;
        if (!$space)
            $space=array('top'=>2*$scale,'bottom'=>2*$scale,'left'=>2*$scale,'right'=>2*$scale);

        /* count total width */
        $xpos=0;
        $width=true;
        for ($i=0;$i<strlen($bars);$i++){
            $val=strtolower($bars[$i]);
            if ($width){
                $xpos+=$val*$scale;
                $width=false;
                continue;
            }
            if (preg_match("#[a-z]#", $val)){
                /* tall bar */
                $val=ord($val)-ord('a')+1;
            }
            $xpos+=$val*$scale;
            $width=true;
        }

        /* allocate the image */
        $total_x=( $xpos )+$space['right']+$space['right'];
        $xpos=$space['left'];
        if (!function_exists("imagecreate")){
            print "You don't have the gd2 extension enabled<BR>\n";
            print "<BR>\n";
            print "<BR>\n";
            print "Short HOWTO<BR>\n";
            print "<BR>\n";
            print "Debian: # apt-get install php4-gd2<BR>\n";
            print "<BR>\n";
            print "SuSE: ask YaST<BR>\n";
            print "<BR>\n";
            print "OpenBSD: # pkg_add /path/php4-gd-4.X.X.tgz (read output, you have to enable it)<BR>\n";
            print "<BR>\n";
            print "Windows: Download the PHP zip package from <A href=\"http://www.php.net/downloads.php\">php.net</A>, NOT the windows-installer, unzip the php_gd2.dll to C:\PHP (this is the default install dir) and uncomment 'extension=php_gd2.dll' in C:\WINNT\php.ini (or where ever your os is installed)<BR>\n";
            print "<BR>\n";
            print "<BR>\n";
            print "The author of php-barcode will give not support on this topic!<BR>\n";
            print "<BR>\n";
            print "<BR>\n";
            print "<A HREF=\"http://www.ashberg.de/php-barcode/\">Folke Ashberg's OpenSource PHP-Barcode</A><BR>\n";
            return "";
        }

//        d::td($total_x.' - '.$total_y);

        $im=imagecreate($total_x, $total_y);
//        $im=imagecreatetruecolor($total_x, $total_y);

        /* create two images */
        $col_bg=ImageColorAllocate($im,$bg_color[0],$bg_color[1],$bg_color[2]);
        $col_bar=ImageColorAllocate($im,$bar_color[0],$bar_color[1],$bar_color[2]);
        $col_text=ImageColorAllocate($im,$text_color[0],$text_color[1],$text_color[2]);
        $height=round($total_y-($scale*10));
        $height2=round($total_y-$space['bottom']);


        /* paint the bars */
        $width=true;
        for ($i=0;$i<strlen($bars);$i++){
            $val=strtolower($bars[$i]);
            if ($width){
                $xpos+=$val*$scale;
                $width=false;
                continue;
            }
            if (preg_match("#[a-z]#", $val)){
                /* tall bar */
                $val=ord($val)-ord('a')+1;
                $h=$height2;
            } else $h=$height;
            imagefilledrectangle($im, $xpos, $space['top'], $xpos+($val*$scale)-1, $h, $col_bar);
            $xpos+=$val*$scale;
            $width=true;
        }
        /* write out the text */
//        global $_SERVER;
        $chars=explode(" ", $text);
        $chars=explode(" ", $text);
        reset($chars);
        while (list($n, $v)=each($chars)){
            if (trim($v)){
                $inf=explode(":", $v);
                $fontsize=$scale*($inf[1]/1.8);
                $fontheight=$total_y-($fontsize/2.7)+2;
                @imagettftext($im, $fontsize, 0, $space['left']+($scale*$inf[0])+2,
                    $fontheight, $col_text, $font_loc, $inf[2]);
            }
        }

        /* output the image */
        $mode=strtolower($mode);
        if ($mode=='jpg' || $mode=='jpeg'){
            header('Content-Type: image/jpeg; name="barcode.jpg"');
//            imagejpeg($im);

            ob_start();
//            imagejpeg($im);
            imagejpeg($im, NULL, 90);
            $return = ob_get_contents();
            ob_end_clean();

        } else if ($mode=='gif'){
            header('Content-Type: image/gif; name="barcode.gif"');
//            imagegif($im);

            ob_start();
            imagegif($im);
            $return = ob_get_contents();
            ob_end_clean();

        } else {
            header('Content-Type: image/png; name="barcode.png"');
//            imagepng($im);

            ob_start();
            imagepng($im, NULL, 9);
            $return = ob_get_contents();
            ob_end_clean();

        }

        $return = $im;

        // Удаление изображения из памяти
//        imagedestroy($im);

        return $return;

    }

    public static function barcode_print($code, $scale = 2 ,$mode, $total_y){
        $bars=self::barcode_encode_bars($code);
        return self::barcode_outimage($bars['text'],$bars['bars'],$scale, $mode, $total_y);
    }



}



