<?php
	/* data definition */
	define("merchantID", "CA0679");//contact the official support staff to obtain test value
	define("merchantPW", "e998f14ab6a30cf2b6c4d763bf78077306c9a1e109e11f2c8310d8f1f3eb263e");//contact the official support staff to obtain test value

	define("warehouses", "https://open-api-tra.flashexpress.com/open/v1/warehouses");
	
	define("conUrl", "https://open-api-tra.flashexpress.com/open/v1/orders");
//	define("consumer", "https://open-api-tra.flashexpress.com/open/v1/orders/consumer"); //need merchant has opened agency services, the api is unavailable now.
	define("queryUrl", "https://open-api-tra.flashexpress.com/open/v1/orders/{pno}/routes");//the {pno} is from api:orders
	define("preprintUrl", "https://open-api-tra.flashexpress.com/open/v1/orders/{pno}/pre_print"); //the {pno} is from api:orders
	define("cancelOrder", "https://open-api-tra.flashexpress.com/open/v1/orders/{pno}/cancel"); //the {pno} is from api:orders
	define("downloadDir", "/tmp/");

	define("notify", "https://open-api-tra.flashexpress.com/open/v1/notify");	

	
	/* sign function */
	function signParam($str)
	{
		return strtoupper(hash("sha256", $str));
	}
	
	/* build post param str */
	function buildRequestParam($data_arr)
	{
		$sign = '';
		ksort($data_arr);
	        foreach($data_arr as $k => $v)
		{
			if((($v != null) || $v === 0) && ($k != 'sign'))
			{
				$sign .= $k.'='.$v.'&';
			}else{
				unset($data_arr[$k]);
			}
        	}
		$sign .= "key=" . merchantPW;
		
		$data_arr['sign'] = signParam($sign);
		
		$requestStr = '';
		foreach($data_arr as $k => $v)
		{
			$requestStr .= $k . "=" . urlencode($v) . '&';
		}
		return substr($requestStr, 0, -1);
	}
	
	/* common post funciton used for all web requests */
	function postRequest($url, $postData)
	{
		$curl = curl_init ();
		$header[] = "Content-type: application/x-www-form-urlencoded";
		$header[] = "Accept: application/json";
		$header[] = "Accept-Language: th";
		curl_setopt ( $curl, CURLOPT_URL, $url );
		curl_setopt ( $curl, CURLOPT_SSL_VERIFYPEER, false ); // SSL certificate
		curl_setopt ( $curl, CURLOPT_SSL_VERIFYHOST, false );
		curl_setopt ( $curl, CURLOPT_HEADER, 0 );
		curl_setopt ( $curl, CURLOPT_HTTPHEADER, $header );
		curl_setopt ( $curl, CURLOPT_RETURNTRANSFER, 1 );
		curl_setopt ( $curl, CURLOPT_POST, true ); // post
		curl_setopt ( $curl, CURLOPT_POSTFIELDS, $postData ); // post data
		curl_setopt ( $curl, CURLOPT_TIMEOUT, 10 );
		
		$responseText = curl_exec ( $curl );
		if (curl_errno ( $curl )) {
				echo 'Errno' . curl_error ( $curl );
		}
		curl_close ( $curl );
		return $responseText;
	}

	/* common post funciton used for download attachments requests */
	function postRequestAndDownload($url, $postData, $saveDir)
	{
		$curl = curl_init ();
		$header[] = "Content-type: application/x-www-form-urlencoded";
		$header[] = "Accept: application/json";
		$header[] = "Accept-Language: th";
		curl_setopt ( $curl, CURLOPT_URL, $url );
		curl_setopt ( $curl, CURLOPT_SSL_VERIFYPEER, false ); // SSL certificate
		curl_setopt ( $curl, CURLOPT_SSL_VERIFYHOST, false );
		curl_setopt ( $curl, CURLOPT_HEADER, 1 );
		curl_setopt ( $curl, CURLOPT_HTTPHEADER, $header );
		curl_setopt ( $curl, CURLOPT_RETURNTRANSFER, 1 );
		curl_setopt ( $curl, CURLOPT_POST, true ); // post
		curl_setopt ( $curl, CURLOPT_POSTFIELDS, $postData ); // post data
		curl_setopt ( $curl, CURLOPT_TIMEOUT, 10 );
		curl_setopt ( $curl, CURLINFO_HEADER_OUT, true);
		
		$responseText = curl_exec ( $curl );
		if (curl_errno ( $curl )) 
		{
			echo 'Errno' . curl_error ( $curl );
		}
		curl_close ( $curl );


		list($headers, $body) = explode("\r\n\r\n", $responseText, 2);
		//1) process header
		$header_arr = array();
		$header_tmp = explode("\n", $headers);
		foreach($header_tmp as $header_value) 
		{
			$pos = strpos($header_value, ":");
			$k = trim(substr($header_value, 0, $pos));
			$v = trim(substr($header_value, $pos+1));
			if(!empty($k))
				$header_arr[$k] = $v;
		}
		$file_name = $header_arr['Content-Disposition'];
		$file_type = $header_arr['Content-Type'];
		$file_save_name = substr($file_name, strrpos($file_name, "=")+1);

		//2) process body
		$file_content = $body;
		$filename  = $saveDir . $file_save_name;
		if(is_writable($saveDir)) 
		{
			if(!$handle  =  fopen($filename, 'w')) 
			{
         			echo  "cannot open  $filename \n" ;
         			exit;
			}
     			if(fwrite($handle,  $file_content) ===  FALSE)
			{
				echo  "cannot write file  $filename\n" ;
        			exit;
			}
    			echo  "write $filename success\n" ;
			fclose($handle);
			return true;
		} 
		else 
		{
			echo  "file $filename not writable\n" ;
		}
		return false;
	}	

/*
		testcase 1:
		query warehouse
*/
	function testWarehouse($data_arr)
	{
		$post_str = buildRequestParam($data_arr);
		$responseStr = postRequest(warehouses, $post_str);
		return json_decode($responseStr, true);
	}
	
	$warehouse_arr = array(
        "mchId" => merchantID,
        "nonceStr" => time()
	);	
	var_dump(testWarehouse($warehouse_arr));

/*
		testcase 2:
		Create Order
*/
	function testCreateOrder($data_arr)
	{
		$post_str = buildRequestParam($data_arr);
		$responseStr = postRequest(conUrl, $post_str);
		return json_decode($responseStr, true);
	}
	
	$order_arr = array(
        "mchId" => merchantID,
        "nonceStr" => time(),
        "outTradeNo" => time(),
        "expressCategory" => "1", 
        "srcName" => "sender901",
        "srcPhone" => "1111110901",
        "srcProvinceName" => "เพชรบูรณ์",
        "srcCityName" => "หล่มสัก",
        "srcPostalCode" => "67110",
        "srcDetailAddress" => "SenderAddress901",
        "dstName" => "receiver901",
        "dstPhone" => "2222220901",
        "dstProvinceName" => "สมุทรสาคร", 
        "dstCityName" => "บ้านแพ้ว",
        "dstPostalCode" => "74120",
        "dstDetailAddress" => "ReceiverAddress901",
        "articleCategory" => "1",
        "weight" => "16194",
        "insured" => "1",
        "insureDeclareValue" => "1894",
        "codEnabled" => "0",
        "codAmount" => "0",
        "remark" => "remark901"
	);	
//	var_dump(testCreateOrder($order_arr));
	
	
/*
		testcase 3:
		Query Parcel
*/
	function queryParcel($parcelNo)
	{
		$parcelNo = trim($parcelNo);
		$paramArr = array(
			"mchId" => merchantID,
			"nonceStr" => time(),
		);
		$post_str = buildRequestParam($paramArr);
		$url = str_replace("{pno}", $parcelNo, queryUrl);
		$responseStr = postRequest($url, $post_str);
		return json_decode($responseStr, true);
	}
	$parcelNo = "TH1603324U6A";//"TH16030000000394";
	$jsonObj = queryParcel($parcelNo);
	var_dump($jsonObj);

/*
		testcase 4:
		Parcel preprint
*/

	
	function queryPrePrint($parcelNo)
	{
		$parcelNo = trim($parcelNo);
		$paramArr = array(
			"mchId" => merchantID,
			"nonceStr" => time(),
		);
		$post_str = buildRequestParam($paramArr);
		$url = str_replace("{pno}", $parcelNo, preprintUrl);
		$responseStr = postRequestAndDownload($url, $post_str, downloadDir);
	}
//	$parcelNo = "TH1603324U6A";
//	queryPrePrint($parcelNo);

/*
		test case 5;
		cancel order 
*/
	function cancelOrder($parcelNo)
	{
		$parcelNo = trim($parcelNo);
		$paramArr = array(
			"mchId" => merchantID,
			"nonceStr" => time(),
		);
		$post_str = buildRequestParam($paramArr);
		$url = str_replace("{pno}", $parcelNo, cancelOrder);
		$responseStr = postRequest($url, $post_str);
		return json_decode($responseStr, true);
	}
	$parcelNo = "TH1603324U6A"; //"TH16030000000394";  from the return value of api orders
	$jsonObj = cancelOrder($parcelNo);
	var_dump($jsonObj);


/*
		testcase 6:
		consumer
		//need merchant has opened agency services, the api is unavailable now.
*/
	function testConsumer($data_arr)
	{
		$post_str = buildRequestParam($data_arr);
		$responseStr = postRequest(consumer, $post_str);
		return json_decode($responseStr, true);
	}
	
	$order_arr = array(
        "mchId" => merchantID,
        "nonceStr" => time(),
		"customerPhone" => "2222220903",
        "outTradeNo" => time(),
        "expressCategory" => "1", 
        "srcName" => "sender903",
        "srcPhone" => "1111110903",
        "srcProvinceName" => "เพชรบูรณ์",
        "srcCityName" => "หล่มสัก",
        "srcPostalCode" => "67110",
        "srcDetailAddress" => "SenderAddress901",
        "dstName" => "receiver901",
        "dstPhone" => "2222220901",
        "dstProvinceName" => "สมุทรสาคร", 
        "dstCityName" => "บ้านแพ้ว",
        "dstPostalCode" => "74120",
        "dstDetailAddress" => "ReceiverAddress901",
        "articleCategory" => "1",
        "weight" => "16195",
        "insured" => "1",
        "insureDeclareValue" => "1894",
        "remark" => "remark903"
	);	
//	var_dump(testConsumer($order_arr));


/*
		testcase 7:
		notify
*/
	function testNotify($data_arr)
	{
		$post_str = buildRequestParam($data_arr);
		$responseStr = postRequest(notify, $post_str);
		return json_decode($responseStr, true);
	}
	
	$notify_arr = array(
        "mchId" => merchantID,
        "nonceStr" => time(),
        "srcName" => "sender902",
        "srcPhone" => "1111110902",
        "srcProvinceName" => "เพชรบูรณ์",
        "srcCityName" => "หล่มสัก",
        "srcPostalCode" => "67110",
        "srcDetailAddress" => "SenderAddress901",
		"estimateParcelNumber" => "5",
        "remark" => "remark902"
	);	
//	var_dump(testNotify($notify_arr));
	

?>

