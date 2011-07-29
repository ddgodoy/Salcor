<?php
    /**
     * JSONP Implemation of the YahooFinanceAPI
     * usage:
     *    ?symbol=XXXXX&callback=YYYYY
     *
     * @author MDBitz - Matthew John Denton
     */

    // obtain YahooFinanceAPI class
    require_once(dirname(__FILE__) . '/YahooFinanceAPI.php');

    /* Register Auto Loader */
    spl_autoload_register(array('YahooFinanceAPI', 'autoload'));

    // instantiate YahooFinanceAPI
    $api = new YahooFinanceAPI();

    // set options
    $api->addOption("symbol");
    $api->addOption("previousClose");
    $api->addOption("open");
    $api->addOption("lastTrade");
    $api->addOption("lastTradeTime");
    $api->addOption("change" );
    $api->addOption("daysLow" );
    $api->addOption("daysHigh" );
    $api->addOption("volume" );

    // set symbols
    $symbol = $_GET['symbol'];
    $api->addSymbol("$symbol" );

    // get quotes
    $result = $api->getQuotes();
    
    //get callback
    if( $_GET['callback'] != null ) {
        //return jsonp
        echo $_GET['callback'] . '(' . $result->data[0]->toJSON() . ')';
    } else {
        // return json
        echo $result->data[0]->toJSON();
    }