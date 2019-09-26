<?php
    
    echo'<img src= "contoh.jpg">';

    $asli = imagecreatefromjpeg("contoh.jpg");
    $lebar = imagesx($asli);
    $tinggi = imagesy($asli);

    $baru = imagecreatetruecolor($lebar,$tinggi);

    for ($x = 0; $x < $lebar;$x++)
    {
        for ($y=0 ; $y < $tinggi; $y++)
        {
            $pixelasli = imagecolorat($asli,$x,$y);
            $cols = imagecolorsforindex($asli, $pixelasli);

                $r = $cols['red'];
                $g = $cols['green'];
                $b = $cols['blue'];
				$warna = imagecolorallocate($baru, $r, $g , $b);
				if($x>$lebar/3){
					if($x<($lebar-($lebar/3))){
						if($y>$lebar/3){
							if($y<($lebar-($lebar/3))){
								$gs = ($r + $g + $b) / 3;
								$warna = imagecolorallocate($baru, $gs, $gs , $gs);
							}
						}
					}
				}
                imagesetpixel($baru,$x,$y,$warna);
                
        }
    }
    imagejpeg($baru,"gambar01cp.jpg",75);
    echo '<img src = "gambar01cp.jpg"> <br>';
    print "Modifikasi Kecemerlangan f'(x,y) = f(x,y)+C dengan C = $c ";
?>