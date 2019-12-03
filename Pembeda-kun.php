<?php
	include_once 'hQuery\hquery.php';
	use duzun\hQuery;
	//Jenis Nama Doc = 1000 0100 0010 0001
	//SaveArrayToTxt(array("term"=>"contoh","boolean"=>"1111"));
	ChanceRowObjInTxt(array("term"=>"shory","boolean"=>"0001"));
	MakeMatrixTerm('1000 Run-chan_GDD.docx3');
	MakeMatrixTerm('0100 Run-chan_GDD.docx3');
	$allstr = ["test.txt","test2.txt","test3.txt"];//All Text wich get compared
	
	/* arraiRow(RowObj) = array('term' = > "contoh", 'boolean' = "10010");*/
	function SaveArrayToTxt($arraiRow){//Add new RowTerm in Database(Matrix Bool)
		$strRow = $arraiRow["term"]." ".$arraiRow["boolean"]."\n";
		$myfile = fopen('VocabularyList.txt', "a") or die("Unable to open file!");
		fwrite($myfile, $strRow);fclose($myfile);
	}
	function MakeMatrixTerm($urlFile){//open file and make boolean matrik
		$myfile = fopen($urlFile, "r") or die("Unable to open file!");
		$str = fread($myfile,filesize($urlFile));
		fclose($myfile);
		
		$splitStr = explode(" ",$str);
		for($i=0;$i<count($splitStr);$i++){
			if(strlen($splitStr[$i])>0){
				if(ChanceRowObjInTxt(array("term"=>$splitStr[$i],"boolean"=>explode(" ",$urlFile)[0]))==false){
					SaveArrayToTxt(array("term"=>$splitStr[$i],"boolean"=>explode(" ",$urlFile)[0]));
				}
			}
		}
		echo "<textarea>".$str."</textarea>";
	}
	function ChanceRowObjInTxt($arrayRow){
		$myfile = fopen('VocabularyList.txt', "r+") or die("Unable to open file!");
		$str = fread($myfile,filesize('VocabularyList.txt'));
		fclose($myfile);
		$splitStr = explode("\n",$str);
		$lenTerm = 0;
		for($i=0;$i<count($splitStr);$i++){
			$Term = explode(" ",$splitStr[$i]);
			if(count($Term)==2){
				if($Term[0]==$arrayRow["term"]){
					$bit = "";
					for($i=0;$i<strlen($arrayRow["boolean"]);$i++){
						if(($arrayRow["boolean"][$i]=='1')||($Term[1][$i]=='1')){
							$bit=$bit.'1';
						}else{
							$bit=$bit.'0';
						}
					}
					$str = substr($str,0,$lenTerm+strlen($arrayRow["term"])+1).$bit.substr($str,$lenTerm+strlen($arrayRow["term"])+1+strlen($arrayRow["boolean"]));
					ChanceText($str);
					return true;
				}
				$lenTerm+=strlen($splitStr[$i])+1;
			}
		}
		return false;
	}
	function ChanceText($str){
		file_put_contents("VocabularyList.txt", $str);
	}
	function CariSama($banners,$str){//get array of the same word
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
	function GetFormPKcom($str){//get list same word
		$sameDoc = 'https://m.persamaankata.com/2118/bicara/';
		$doc = hQuery::fromHTML(GetHtmlFormUrl($sameDoc));
		$banners = $doc->find('div[class]');
		return $banners;
	}
	function GetTextForm($urlText){//get Text or docx In Server
		$getType = substr($urlText,strrpos($urlText,'.')+1);
		$str = "";
		if($getType=="txt"){//jika content file tidak sesuai dengan akhir nama(.xxx) maka ini error
			$myfile = fopen($urlText, "r+") or die("Unable to open file!");//open every file in dokumen wich get compared
			$str = fgets($myfile);
			fclose($myfile);
		}else if($getType=="docx"){
			$str = kv_read_word($urlText);
		}else if($getType=="doc"){
			$str = kv_read_word($urlText);
		}
		return $str;
	}
	function GetHtmlFormUrl($url){//get content form url
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
		$result = curl_exec($ch);
		return $result;
	}
	function kv_read_word($input_file){//read docx
		$kv_strip_texts = ''; 
		$kv_texts = ''; 	
		if(!$input_file || !file_exists($input_file)) return false;	
		$zip = zip_open($input_file);
		if (!$zip || is_numeric($zip)) return false;
		while ($zip_entry = zip_read($zip)) {
			if (zip_entry_open($zip, $zip_entry) == FALSE) continue;
			if (zip_entry_name($zip_entry) != "word/document.xml") continue;
			$kv_texts .= zip_entry_read($zip_entry, zip_entry_filesize($zip_entry));
			zip_entry_close($zip_entry);
		}
		zip_close($zip);
		$kv_texts = str_replace('</w:r></w:p></w:tc><w:tc>', " ", $kv_texts);
		$kv_texts = str_replace('</w:r></w:p>', "\r\n", $kv_texts);
		//$kv_strip_texts = nl2br(strip_tags($kv_texts,''));
		$kv_strip_texts = strip_tags($kv_texts,'');
		return $kv_strip_texts;
	}
	function ArrayToSTr($arrai){
		$str = "";
		foreach($arrai as $pos) {
			$str=$str." ".$pos;
		}
		return $str;
	}
	function Tokenisation($str){
		$arraiTerm = array();
		$splitStr = explode(" ",$str);
		foreach($splitStr as $pos) {
			if(array_search($pos,$arraiTerm,true)==false){
				array_push($arraiTerm,$pos);
			}
		}
		return $arraiTerm;
	}
	//DocNum is Doc1 = 1000 in 4 doc
	function SplitText($name,$DocNum,$str,$lenMax){//split txt in server
		$str = preg_replace("/[^a-zA-Z0-9\s]/", " ", $str);
		$str = preg_replace("/[\n\r]/"," ",$str);
		$str = strtolower($str);
		$arraiTerm = Tokenisation($str);
		$str = ArrayToSTr($arraiTerm);
		echo ($str);
		$lenStr = strlen($str);
		$lenSisa = $lenStr%$lenMax;
		$len = ($lenStr - $lenSisa)/$lenMax;
		$Inow = 0;
		for($i=0;$i<$len;$i++){
			if(($str[($i+1)*$lenMax-1]!=' ')||($str[($i+1)*$lenMax]!=' ')){
				$akhirSubStr = strpos($str,' ',($i+1)*$lenMax);
				$splitStr = substr($str,$Inow,$akhirSubStr-$Inow);
				$Inow = $akhirSubStr;
			}else{
				$splitStr = substr($str,$Inow,$akhirSubStr-$Inow);
				$Inow = ($i+1)*$lenMax;
			}
			$myfile = fopen($DocNum." ".$name.$i, "w") or die("Unable to open file!");
			fwrite($myfile, $splitStr);
			fclose($myfile);
		}
		$myfile = fopen($DocNum." ".$name.$len, "w") or die("Unable to open file!");
		fwrite($myfile, substr($str,$Inow));
		fclose($myfile);
	}
	$kv_texts = GetTextForm('Run-chan_GDD.docx');
	echo "<textarea>";
	if($kv_texts !== false) {
		SplitText('Run-chan_GDD.docx','1000',$kv_texts,300);
	}
	else {
		echo "Can't Read that file.";
	}
	echo "</textarea>";
?>