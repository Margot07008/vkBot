<?php


function createImagePrediction($text)
{
    $t_r = mt_rand(1,255);
    $t_g = mt_rand(1,255);
    $t_b = mt_rand(1,255);

    $bg_r = mt_rand(1,255);
    $bg_g = mt_rand(1,255);
    $bg_b = mt_rand(1,255);

    $canvasWidth = 2500;
    $canvasHeight = 1800;

    $num_rand = mt_rand(1,5);
    $img = "../../static/".$num_rand.".png";
    $CENTER = $canvasWidth/2;

    $font_size = 70;
    $font = '../../static/font.ttf';

    $width = $canvasWidth;
    $margin = 1;
    $word4 = "1234";

    $canvas = imageCreate($canvasWidth, $canvasHeight);

    $color_Bg = imageColorAllocate($canvas, $bg_r, $bg_g, $bg_b);

    imageFilledRectangle($canvas, 0, 0, $canvasWidth - 1, $canvasHeight - 1, $color_Bg);

    $name = base_convert(mt_rand(), 10, 36);
    $name2 = base_convert(mt_rand(), 10, 36);


    $tmpImgSave = "../../static/tmp_img/" . strtolower($name . $name2) . ".png";
	$save = "../../static/img_save/" . strtolower($name . $name2) . ".png";


    imageFilledRectangle($canvas, 0, 0, $canvasWidth - 1, $canvasHeight - 1, $color_Bg);

    imagepng($canvas, $tmpImgSave);
    imagedestroy($canvas);

    $im = imagecreatefrompng($img);
    imagesavealpha($im, true);
    $color_T = imageColorAllocate($im, $t_r, $t_g, $t_b);

    $imW = imagesx($im);
    $imH = imagesy($im);

    $bg = imagecreatefrompng($tmpImgSave);

    $tmp = imageCreateTrueColor($canvasWidth, $canvasHeight);
    imagecopy($tmp, $bg, 0,0,0,0, $canvasWidth, $canvasHeight);
    imagedestroy($bg);

    imagecopy($tmp, $im, 100,($canvasHeight - $imH)/2,0,0,$imW,$imH);



//______________________________________________________________
    for ($i = 0; $i <= 35; $i++) {
        $box = imagettfbbox($font_size, 0, $font, $text);
        if((($box[2] > $width - $margin*150))){
            $font_size	= $font_size - 1;
        }
    }

    $text_a = explode(' ', $text);
    $text_new2 = '';
    foreach($text_a as $word)
    {
        if(($text_a < 5)){
            $box = imagettfbbox($font_size, 0, $font, $text_new2.' '.$word);
            if(($box[2] > $width - $margin*900))
            {
                $text_new2 .= "\n".$word;
            }
            else
            {
                $text_new2 .= " ".$word;
            }
        }
        else
        {
            $box = imagettfbbox($font_size, 0, $font, $text_new2.' '.$word4.$word4.$word4.$word4);
            if(($box[2] > $width - $margin*1000))
            {
                $text_new2 .= "\n".$word;
            }
            else
            {
                $text_new2 .= " ".$word;
            }
        }
    }


    $text_new3 = trim($text_new2);
    $text_f = explode("\n", $text_new3);

    $box = imagettfbbox($font_size, 0, $font, $text_f[0]);
    $left0 = $CENTER-round(($box[2]-$box[0])/2);
    $box = imagettfbbox($font_size, 0, $font, $text_f[1]);
    $left1 = $CENTER-round(($box[2]-$box[0])/2);
    $box = imagettfbbox($font_size, 0, $font, $text_f[2]);
    $left2 = $CENTER-round(($box[2]-$box[0])/2);
    $box = imagettfbbox($font_size, 0, $font, $text_f[3]);
    $left3 = $CENTER-round(($box[2]-$box[0])/2);



    switch ($num_rand)
    {
        case 1:
        case 2:
            if ($text_f[3]) {
                $font_size -= 17;
                $mainHeight = $canvasHeight / 2  - 2 * ($font_size);
            }
            else if ($text_f[2])
                $mainHeight = $canvasHeight/2  - ($font_size);
            else if ($text_f[1])
                $mainHeight = $canvasHeight/2  - ($font_size/2);
            else
                $mainHeight = $canvasHeight/2 ;
            break;

        case 3:
            if ($text_f[3]) {
                $font_size -= 17;
                $mainHeight = $canvasHeight / 2 - 15 - 2 * ($font_size);
            }
            else if ($text_f[2])
                $mainHeight = $canvasHeight/2 - 15 - ($font_size);
            else if ($text_f[1])
                $mainHeight = $canvasHeight/2- 15 - ($font_size/2);
            else
                $mainHeight = $canvasHeight/2 - 15;
            break;

        case 4:
        case 5:
            if ($text_f[3]) {
                $font_size -= 17;
                $mainHeight = $canvasHeight / 2 - 70 - 2 * ($font_size);
            }
            else if ($text_f[2])
                $mainHeight = $canvasHeight/2 - 70 - ($font_size);
            else if ($text_f[1])
                $mainHeight = $canvasHeight/2 - 70 - ($font_size/2);
            else
                $mainHeight = $canvasHeight/2 - 70;
            break;

    }



    $color_Outline = imageColorAllocate($im, 0,0,0);

    doOutline($tmp, $font_size, $left0, $mainHeight, $color_Outline, $font, $text_f[0]);
    imagettftext ( $tmp, $font_size, 0 , $left0, $mainHeight, $color_T , $font , $text_f[0]);

    doOutline($tmp, $font_size, $left1, $mainHeight+$font_size+12, $color_Outline, $font, $text_f[1]);
    imagettftext ( $tmp, $font_size, 0 , $left1, $mainHeight+$font_size+12, $color_T , $font , $text_f[1]);

    doOutline($tmp, $font_size, $left2, $mainHeight+2*($font_size + 14), $color_Outline, $font, $text_f[2]);
    imagettftext ( $tmp, $font_size, 0 , $left2, $mainHeight+2*($font_size + 14), $color_T , $font , $text_f[2]);

    doOutline($tmp, $font_size, $left3, $mainHeight+3*($font_size + 16), $color_Outline, $font, $text_f[3]);
    imagettftext ( $tmp, $font_size, 0 , $left3, $mainHeight+3*($font_size + 16), $color_T , $font , $text_f[3]);

//_______________________________________________________________





    header("Content-type: image/png");
    imagepng($tmp);
    imagepng($tmp, $save);

    imagedestroy($tmp);

    imagedestroy($im);
    unlink($tmpImgSave);

    return $save;
}


function doOutline($im, $font_size, $x, $y, $color, $font, $text)
{

    for($i=2; $i > 0; $i--)
    {
        imagettftext ( $im, $font_size, 0 , $x+$i, $y, $color , $font , $text);
        imagettftext ( $im, $font_size, 0 , $x-$i, $y, $color , $font , $text);
        imagettftext ( $im, $font_size, 0 , $x, $y+$i, $color , $font , $text);
        imagettftext ( $im, $font_size, 0 , $x, $y-$i, $color , $font , $text);
//        imagettftext ( $im, $font_size, 0 , $x+$i, $y+$i, $color , $font , $text);
//        imagettftext ( $im, $font_size, 0 , $x-$i, $y-$i, $color , $font , $text);
//        imagettftext ( $im, $font_size, 0 , $x+$i, $y-$i, $color , $font , $text);
//        imagettftext ( $im, $font_size, 0 , $x-$i, $y+$i, $color , $font , $text);
    }
}