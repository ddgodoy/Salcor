<?php
    /**
     * Test Script for the YahooFinanceAPI Library
     * @author MDBitz - Matthew John Denton
     */

    require_once(dirname(__FILE__) . '/YahooFinanceAPI.php');

    /* Register Auto Loader */
    spl_autoload_register(array('YahooFinanceAPI', 'autoload'));

    // instantiate YahooFinanceAPI
    $api = new YahooFinanceAPI( YahooFinanceAPI::$LOOSE );
    
	//$api->local = "France";
	
	$api->addOption( "TESTINTSG" );
    // set options
    $api->addOption( YahooFinance_Options::SYMBOL );
    $api->addOption( YahooFinance_Options::PREVIOUS_CLOSE );
    $api->addOption( YahooFinance_Options::OPEN );
    $api->addOption( YahooFinance_Options::LAST_TRADE );
    $api->addOption( YahooFinance_Options::LAST_TRADE_TIME );
    $api->addOption( YahooFinance_Options::CHANGE );
    $api->addOption( YahooFinance_Options::DAYS_LOW );
    $api->addOption( YahooFinance_Options::DAYS_HIGH );
    $api->addOption( YahooFinance_Options::VOLUME );
	$api->addOption( YahooFinance_Options::TRADE_DATE );
    
    // set symbols
    $api->addSymbol("DELL" );
    $api->addSymbol("RHT");
    
    // get quotes
    $result = $api->getQuotes();

    if( $result->isSuccess() ) {
	    $quotes = $result->data;
	    foreach( $quotes as $quote ) {
			echo '<h3>' . $quote->symbol . '</h3>';
			echo '<ul><li>Last Trade: ' . $quote->get( YahooFinance_Options::LAST_TRADE ) . '</li>';
			echo '<li>Days Low: ' . $quote->get( YahooFinance_Options::DAYS_LOW ) . '</li></ul>';
	    }
	}


    // echo JSON of result
    echo $result->toJSON();

    // echo XML of result
    echo "<br/>------<br/>";
    echo $result->toXML();

	echo "<br/>------<br/>";
	$api->local = YahooFinance_Location::FRANCE;
	$result = $api->getQuotes();
	echo $result->toJSON();