<?php
	include_once 'hQuery\hquery.php';
	use duzun\hQuery;
	echo "<br>";
	$allstr = ["test.txt","test2.txt","test3.txt"];//All Text wich get compared
	//membandingkan kata yang sama menggunakan persamaankata.com
	function GetDiffValue($arrai){//array is dokumen url 
		$matrixDoc = array();//matrix dari n-dokumen
		for($i=0;$i<count($arrai);$i++){//get matrix n-dokumen
			$myfile = fopen($arrai[$i], "r") or die("Unable to open file!");//open every file in dokumen wich get compared
			$str = fgets($myfile);
			echo $str."<br><br>";
			fclose($myfile);
			$splitStr = explode(" ",$str);
			foreach($splitStr as $pos) {
				if(count($matrixDoc)==0){
					array_push($matrixDoc,array("Term"=>$pos,("Doc".$i)=>1));
				}
				for($o=0;$o<count($matrixDoc);$o++){
					if($matrixDoc[$o]["Term"]==$pos){
						$matrixDoc[$o][("Doc".$i)] = 1;
						break;
					}
					if($o+1==count($matrixDoc)){
						array_push($matrixDoc,array("Term"=>$pos,("Doc".$i)=>1));
						break;
					}
				}
			}
		}
		$value = array();//value for differenate? document
		echo count($matrixDoc);
		for($o=0;$o<count($matrixDoc);$o++){
			$banner = GetFormPKcom("contoh.html");//get every term with o or $matrixDoc[$o]["Term"]
			for($i=$o;$i<count($matrixDoc);$i++){//get every term with i
				//print_r($banner);
				/*if(CariSama($banner,$matrixDoc[$i]["Term"])!=null){
					echo "shit";//get binary equation and add value
				}*/
			}
		}
		return null;
	}
	print_r(GetDiffValue($allstr));
	function CariSama($banners,$str){
		if ( $banners ) {
			$arrai = array();
			foreach($banners as $pos => $a) {
				if($a->attr('class') == "word_thesaurus"){
					$banners2 = $a->find('a[href]');
					foreach($banners2 as $pos2 => $b) {
						if($str==$b){
							return $banners2;
						}
					}
				}
			}
			return $arrai;
		}
		return null;
	}
	function GetFormPKcom($str){
		//$doc = hQuery::fromFile($url);
		$doc = hQuery::fromHTML(GetHtmlFormUrl('https://m.persamaankata.com/2118/bicara/'));
		$banners = $doc->find('div[class]');
		/*if ( $banners ) {
			$arrai = array();
			foreach($banners as $pos => $a) {
				if($a->attr('class') == "word_thesaurus"){
					$banners2 = $a->find('a[href]');
					foreach($banners2 as $pos2 => $b) {
						if($str==$b){
							return $banners2;
						}
					}
				}
			}
			return $arrai;
		}*/
		return $banners;
	}
	function GetHtmlFormUrl($url){
		$ch = curl_init($url);
		curl_setopt($ch, CURLOPT_POST, 1);
		//Attach our encoded JSON string to the POST fields.
		//curl_setopt($ch, CURLOPT_POSTFIELDS, $jsonDataEncoded);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		//Set the content type to application/json
		curl_setopt($ch, CURLOPT_HTTPHEADER, 
			array(
			'Connection: close',
			'Cache-Control: max-age=0',
			'Upgrade-Insecure-Requests: 1',
			'Content-Type: application/x-www-form-urlencoded',
			'User-Agent: Mozilla/5.0 (Windows NT 10.0; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/76.0.3809.132 Safari/537.36',
			'Accept: text/html,application/xhtml+xml,application/xml;q=0.9,image/webp,image/apng,*/*;q=0.8,application/signed-exchange;v=b3',
			'Sec-Fetch-Site: same-origin',
			'Accept-Language: en-US,en;q=0.9'
			)
		);
		//Execute the request
		$result = curl_exec($ch);
		/*** load the html into the object ***/
		//$result = "<html><body><ul class='clearfix'>hello</ul></body></html>";
		return $result;
	}
	
	echo "<textarea>";
	//var_dump(GetFormPKcom("omong"));
	echo "</textarea>";
?>