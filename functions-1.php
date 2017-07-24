<?php
// add yahoo_stock_data function as a shortcode
add_shortcode( 'stock-data' , 'yahoo_stock_data' ); 
//fetch data from yahoo finance and store in database
function yahoo_stock_data(){
	
	$cur_time= current_time('timestamp'); 
	$past_time=0;
       	global $wpdb;
	$query = "SELECT * FROM `wpxp_stockdata` WHERE stock_id=1";
	$result = $wpdb->get_results($query);
	if ($result) {
		foreach($result as $row){
			$last=$row->last;
			$high=$row->high;
			$y_high=$row->year_high;
			$bid=$row->bid;
			$volume=$row->volume;
			$open=$row->open;
			$low=$row->low;
			$y_low=$row->year_low;
			$ask=$row->ask;
			$change=$row->value_change;
			$past_time=$row->lastupdate;
		
	}
}
	$diff= $cur_time-$past_time;
	
	// if time difference is greater than 15 minutes
	if($diff>900){
		
	$data = file_get_contents("http://quote.yahoo.com/d/quotes.csv?s=PREV.CN&f=l1hkbvogjac1");
	$rows = explode("\n",$data);
	$s = array();
	foreach($rows as $row) {
	    $s[] = str_getcsv($row);
	}

	$last=$s[0][0];
	$high=$s[0][1];
	$y_high=$s[0][2];
	$bid=$s[0][3];
	$volume=$s[0][4];
	$open=$s[0][5];
	$low=$s[0][6];
	$y_low=$s[0][7];
	$ask=$s[0][8];
	$change=$s[0][9];

	global $wpdb;
	
	//wpdb->update( string $table, array $data, array $where)
	$wpdb->update('wpxp_stockdata', array( 
			'last' => $last,	
			'high' => $high,
			'year_high' =>$y_high,
			'bid' => $bid,
			'volume' => $volume,
			'open' => $open,
			'low' => $low,
			'year_low' => $y_low,
			'ask' => $ask,
			'value_change' => $change,
			'lastupdate' => $cur_time	
		), array( 'stock_id' => 1 ) );
	} // end if
	// Two tables to show the stock data on investors page
	$content= "<div class='stock-data share-price'><table class='nz-table alignleft'>
	<tbody>
	<tr><td> Last</td><td>".$last."</td></tr><tr><td> High</td><td> ".$high."</td></tr>
	<tr><td> Year High</td><td> ".$y_high."</td></tr><tr><td> Bid</td><td> ".$bid."</td></tr>
	<tr><td> Volume</td><td> ".$volume."</td></tr>
	</tbody>
	</table>
	<table class='nz-table alignright'>
	<tbody>
	<tr><td> Open</td><td>".$open."</td></tr><tr><td> Low</td><td> ".$low."</td></tr>
	<tr><td> Year Low</td><td> ".$y_low."</td></tr><tr><td> Ask</td><td>".$ask."</td></tr>
	<tr><td> Change</td><td> ".$change."</td></tr>
	</tbody>
	</table>
	</div>";
	return $content;
	
}// end function 

?>