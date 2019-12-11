<?php
function OpenWord($word){
	//API Url
	$url = 'https://www.persamaankata.com/';
	//Initiate cURL.
	$ch = curl_init($url);
	//The JSON data.
	$jsonData = array(
		'q'=>$word,
		'search.x'=>'0',
		'search.y' => '0'
	);
	echo $word;
	//Encode the array into JSON.
	//$jsonDataEncoded = json_encode($jsonData);//kalau gk pake json dibawah
	$jsonDataEncoded = "q=usus&search.x=0&search.y=0";
	//Tell cURL that we want to send a POST request.
	curl_setopt($ch, CURLOPT_POST, 1);
	//Attach our encoded JSON string to the POST fields.
	curl_setopt($ch, CURLOPT_POSTFIELDS, $jsonDataEncoded);
	curl_setopt($ch, CURLOPT_COOKIE, 'PHPSESSID=5308bc3368f5cd831ea65c8125074148; __utmc=7124403; __utmz=7124403.1576044792.1.1.utmcsr=google|utmccn=(organic)|utmcmd=organic|utmctr=(not%20provided); __utma=7124403.890482341.1576044791.1576044791.1576044791.1; __utmt=1; __utmb=7124403.10.10.1576044792');
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	//Set the content type to application/json
	curl_setopt($ch, CURLOPT_HTTPHEADER, 
		array(
		'Connection: close',
		'Content-Length: 31',
		'Cache-Control: max-age=0',
		'Origin: https://www.persamaankata.com',
		'Upgrade-Insecure-Requests: 1',
		'Content-Type: application/x-www-form-urlencoded',
		'User-Agent: Mozilla/5.0 (Windows NT 10.0; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/76.0.3809.132 Safari/537.36',
		'Sec-Fetch-Mode: navigate',
		'Sec-Fetch-User: ?1',
		'Accept: text/html,application/xhtml+xml,application/xml;q=0.9,image/webp,image/apng,*/*;q=0.8,application/signed-exchange;v=b3',
		'Sec-Fetch-Site: same-origin',
		'Referer: https://www.persamaankata.com/',
		'Accept-Language: en-US,en;q=0.9'
		)
	);
	//Execute the request
	$result = curl_exec($ch);
	return $result;
}
if(isset($_GET['word'])){
	echo OpenWord($_GET['word']);
}
?>