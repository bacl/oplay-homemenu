<?

	// script built based on posts at: 
	// https://forum.transmissionbt.com/viewtopic.php?f=8&t=6810

	$HOST="192.168.1.92";
	$PORT="8081";
	$USER="transmission_username";
	$PASSWORD="password";

	$fields = array ( "id", "name", "percentDone","doneDate","status" );
	$arguments = array ( "fields" => $fields );
	$rpccall = array( "arguments" => $arguments, "method" => "torrent-get" ) ;
	$rpccallencoded = json_encode ($rpccall);

	$ch = curl_init();
	curl_setopt($ch, CURLOPT_HEADER, 1);
	curl_setopt($ch, CURLOPT_URL, "http://$HOST:$PORT/transmission/rpc");
	curl_setopt($ch, CURLOPT_POST, 1);
	curl_setopt($ch, CURLOPT_POSTFIELDS, $rpccallencoded);
	curl_setopt($ch, CURLOPT_HTTPAUTH, 1);
	curl_setopt($ch, CURLOPT_USERPWD, "$USER:$PASSWORD");
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	$r = curl_exec($ch);
	 
	$ret = preg_match  ( "%.*\r\n(X-Transmission-Session-Id: .*?)(\r\n.*)%", $r, $result) ;
	$X_Transmission_Session_Id  = $result[1] ;


	curl_setopt($ch, CURLOPT_HEADER, 0);
	curl_setopt($ch, CURLOPT_HTTPHEADER, array ($X_Transmission_Session_Id)) ;
	$r = curl_exec($ch);

	curl_close($ch);
	 
	$stats = json_decode($r, true) ;
	 
	/*
	echo "<pre>";
	print_r ($stats) ;
	echo "</pre>";
	*/
	if($stats ["result"]=='success'){
		$stats = $stats ["arguments"];
			foreach($stats['torrents'] as $tor){
			
					$name=$tor['name'];
					$per="";
					if($tor['percentDone'] == '1'){// quickfix
						$per="100%  -"; 
					}else{
						$per=number_format(floatval ($tor['percentDone'])*100 ,1,',','')."%  -";
					}
					
					// trim a name bigger them 58 chars so they don't overflow the text item on the menu
					if(strlen($name)>58){ 
					   $name=substr($name,0,56).'...';
					}
					echo $per." ".$name."\n";
			}
	}else{
		echo "ERRROR:\n".$stats ["result"];
	}
?>

 