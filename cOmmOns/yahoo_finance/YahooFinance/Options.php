<?php
/*
 * Copyright (C) 2009 MDBitz - Matthew John Denton - mdbitz.com
 *
 * This file is part of YahooFinanceAPI.
 *
 * YahooFinanceAPI is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * YahooFinanceAPI is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with YahooFinanceAPI.  If not, see <http://www.gnu.org/licenses/>.
 */

/**
 * Options
 *
 * This file contains the class YahooFinance_Options
 *
 * @author Matthew Denton <matt@mdbitz.com>
 * @package com.mdbitz.YahooFinance
 */

/**
 * YahooFinance_Options defines the options requested via the API, as well
 * as handlers to parse resulting data to Quote option.
 *
 * @package com.mdbitz.YahooFinance
 */
class YahooFinance_Options
{

    /**
     * @var array option variables
     */
    public static $_SpecialTags = array(
                              "ask" => "a",
                              "averageDailyVolume" => "a2",
                              "askSize" => "a5",
                              "bid" => "b",
                              "askRealTime" => "b2",
                              "bidRealTime" => "b3",
                              "bookValue" => "b4",
                              "bidSize" => "b6",
                              "changeAndPercentChange" => "c",
                              "change" => "c1",
                              "commision" => "c3",
                              "changeRealTime" => "c6",
                              "afterHoursChangeRealTime" => "c8",
                              "dividendPerShare" => "d",
                              "lastTradeDate" => "d1",
                              "tradeDate" => "d2",
                              "earningsPerShare" => "e",
                              "errorIndication" => "e1",
                              "EPSEstimateCurrentYear" => "e7",
                              "EPSEstimateNextYear" => "e8",
                              "EPSEstimateNextQuarter" => "e9",
                              "floatShares" => "f6",
                              "daysLow" => "g",
                              "daysHigh" => "h",
                              "52WeekLow" => "j",
                              "52WeekHigh" => "k",
                              "holdingsGainPercent" => "g1",
                              "annualizedGain" => "g3",
                              "holdingsGain" => "g4",
                              "holdingsGainPercentRealTime" => "g5",
                              "holdingsGainRealTime" => "g6",
                              "moreInfo" => "i",
                              "orderBookRealTime" => "i5",
                              "marketCapitalization" => "j1",
                              "marketCapitalizationRealTime" => "j3",
                              "EBITDA" => "j4",
                              "changeFrom52WeekLow" => "j5",
                              "percentChangeFrom52WeekLow" => "j6",
                              "lastTradeWithTimeRealTime" => "k1",
                              "changePercentRealTime" => "k2",
                              "lastTradeSize" => "k3",
                              "changeFrom52WeekHigh" => "k4",
                              "percentChangeFrom52WeekHigh" => "k5",
                              "lastTradeWithTime" => "l",
                              "lastTrade" => "l1",
                              "highLimit" => "l2",
                              "lowLimit" => "l3",
                              "daysRange" => "m",
                              "daysRangeRealTime" => "m2",
                              "50DayMovingAverage" => "m3",
                              "200DayMovingAverage" => "m4",
                              "changeFrom200DayMovingAverage" => "m5",
                              "percentChangeFrom200DayMovingAverage" => "m6",
                              "changeFrom50DayMovingAverage" => "m7",
                              "percentChangeFrom50DayMovingAverage" => "m8",
                              "name" => "n",
                              "notes" => "n4",
                              "open" => "o",
                              "previousClose" => "p",
                              "pricePaid" => "p1",
                              "changeInPercent" => "c2",
                              "pricePerSales" => "p5",
                              "pricePerBook" => "p6",
                              "exDividendDate" => "q",
                              "priceEarningsRatio" => "r",
                              "dividendPayDate" => "r1",
                              "priceEarningsRatioRealTime" => "r2",
                              "PEGRatio" => "r5",
                              "pricePerEPSEstimateCurrentYear" => "r6",
                              "pricePerEPSEstimateNextYear" => "r7",
                              "symbol" => "s",
                              "sharesOwned" => "s1",
                              "shortRatio" => "s7",
                              "lastTradeTime" => "t1",
                              "tradeLinks" => "t6",
                              "tickerTrend" => "t7",
                              "1YrTargetPrice" => "t8",
                              "volume" => "v",
                              "holdingsValue" => "v1",
                              "holdingsValueRealTime" => "v7",
                              "52WeekRange" => "w",
                              "daysValueChange" => "w1",
                              "daysValueChangeRealTime" => "w4",
                              "stockExchange" => "x",
                              "dividendYeild" => "y"
                              );

    /**
     *  ASK
     */
    const ASK = "ask";

    /**
     *  AVERAGE DAILY VOLUME
     */
    const AVERAGE_DAILY_VOLUME = "averageDailyVolume";

    /**
     *  ASK SIZE
     */
    const ASK_SIZE = "askSize";

    /**
     *  BID
     */
    const BID = "bid";

    /**
     *  ASK REAL TIME
     */
    const ASK_REAL_TIME = "askRealTime";

    /**
     *  BID REAL TIME
     */
    const BID_REAL_TIME = "bidRealTime";

    /**
     *  BOOK VALUE
     */
    const BOOK_VALUE = "bookValue";

    /**
     *  BID SIZE
     */
    const BID_SIZE = "bidSize";

    /**
     *  CHANGE AND PERCENT CHANGE
     */
    const CHANGE_AND_PERCENT_CHANGE = "changeAndPercentChange";

    /**
     *  CHANGE
     */
    const CHANGE = "change";

    /**
     *  COMMISION
     */
    const COMMISION = "commision";

    /**
     *  CHANGE REAL TIME
     */
    const CHANGE_REAL_TIME = "changeRealTime";

    /**
     *  AFTER HOURS CHANGE REAL TIME
     */
    const AFTER_HOURS_CHANGE_REAL_TIME = "afterHoursChangeRealTime";

    /**
     *  DIVIDEND PER SHARE
     */
    const DIVIDEND_PER_SHARE = "dividendPerShare";

    /**
     *  LAST TRADE DATE
     */
    const LAST_TRADE_DATE = "lastTradeDate";

    /**
     *  TRADE DATE
     */
    const TRADE_DATE = "tradeDate";

    /**
     *  EARNINGS PER SHARE
     */
    const EARNINGS_PER_SHARE = "earningsPerShare";

    /**
     *  ERROR INDICATION
     */
    const ERROR_INDICATION = "errorIndication";

    /**
     *  EPS ESTIMATE CURRENT YEAR
     */
    const EPS_ESTIMATE_CURRENT_YEAR = "EPSEstimateCurrentYear";

    /**
     *  EPS ESTIMATE NEXT YEAR
     */
    const EPS_ESTIMATE_NEXT_YEAR = "EPSEstimateNextYear";

    /**
     *  EPS ESTIMATE NEXT QUARTER
     */
    const EPS_ESTIMATE_NEXT_QUARTER = "EPSEstimateNextQuarter";

    /**
     *  FLOAT SHARES
     */
    const FLOAT_SHARES = "floatShares";

    /**
     *  DAYS LOW
     */
    const DAYS_LOW = "daysLow";

    /**
     *  DAYS HIGH
     */
    const DAYS_HIGH = "daysHigh";

    /**
     *  52 WEEK LOW
     */
    const FIFTY_TWO_WEEK_LOW = "52WeekLow";

    /**
     *  52 WEEK HIGH
     */
    const FIFTY_TWO_WEEK_HIGH = "52WeekHigh";

    /**
     *  HOLDINGS GAIN PERCENT
     */
    const HOLDINGS_GAIN_PERCENT = "holdingsGainPercent";

    /**
     *  ANNUALIZED GAIN
     */
    const ANNUALIZED_GAIN = "annualizedGain";

    /**
     *  HOLDINGS GAIN
     */
    const HOLDINGS_GAIN = "holdingsGain";

    /**
     *  HOLDINGS GAIN PERCENT REAL TIME
     */
    const HOLDINGS_GAIN_PERCENT_REAL_TIME = "holdingsGainPercentRealTime";

    /**
     *  HOLDINGS GAIN REAL TIME
     */
    const HOLDINGS_GAIN_REAL_TIME = "holdingsGainRealTime";

    /**
     *  MORE INFO
     */
    const MORE_INFO = "moreInfo";

    /**
     *  ORDER BOOK REAL TIME
     */
    const ORDER_BOOK_REAL_TIME = "orderBookRealTime";

    /**
     *  MARKET CAPITALIZATION
     */
    const MARKET_CAPITALIZATION = "marketCapitalization";

    /**
     *  MARKET CAPITALIZATION REAL TIME
     */
    const MARKET_CAPITALIZATION_REAL_TIME = "marketCapitalizationRealTime";

    /**
     *  EBITDA
     */
    const EBITDA = "EBITDA";

    /**
     *  CHANGE FROM FIFTY TWO WEEK LOW
     */
    const CHANGE_FROM_FIFTY_TWO_WEEK_LOW = "changeFrom52WeekLow";

    /**
     *  PERCENT CHANGE FROM FIFTY TWO WEEK LOW
     */
    const PERCENT_CHANGE_FROM_FIFTY_TWO_WEEK_LOW = "percentChangeFrom52WeekLow";

    /**
     *  LAST TRADE WITH REAL TIME
     */
    const LAST_TRADE_WITH_REAL_TIME = "lastTradeWithTimeRealTime";

    /**
     *  CHANGE PERCENT REAL TIME
     */
    const CHANGE_PERCENT_REAL_TIME = "changePercentRealTime";

    /**
     *  LAST TRADE SIZE
     */
    const LAST_TRADE_SIZE = "lastTradeSize";

    /**
     *  CHANGE FROM FIFTY TWO WEEK HIGH
     */
    const CHANGE_FROM_FIFTY_TWO_WEEK_HIGH = "changeFrom52WeekHigh";

    /**
     *  PERCENT CHANGE FROM FIFTY TWO WEEK HIGH
     */
    const PERCENT_CHANGE_FROM_FIFTY_TWO_WEEK_HIGH = "percentChangeFrom52WeekHigh";

    /**
     *  LAST TRADE WITH TIME
     */
    const LAST_TRADE_WITH_TIME = "lastTradeWithTime";

    /**
     *  LAST TRADE
     */
    const LAST_TRADE = "lastTrade";

    /**
     *  HIGH LIMIT
     */
    const HIGH_LIMIT = "highLimit";

    /**
     *  LOW LIMIT
     */
    const LOW_LIMIT = "lowLimit";

    /**
     *  DAYS RANGE
     */
    const DAYS_RANGE = "daysRange";

    /**
     *  DAYS RANGE REAL TIME
     */
    const DAYS_RANGE_REAL_TIME = "daysRangeRealTime";

    /**
     *  FIFTY DAY MOVING AVERAGE
     */
    const FIFTY_DAY_MOVING_AVERAGE = "50DayMovingAverage";

    /**
     *  CHANGE FROM FIFTY DAY MOVING AVERAGE
     */
    const CHANGE_FROM_FIFTY_DAY_MOVING_AVERAGE = "changeFrom50DayMovingAverage";

    /**
     *  PERCENT CHANGE FROM FIFTY DAY MOVING AVERAGE
     */
    const PERCENT_CHANGE_FROM_FIFTY_DAY_MOVING_AVERAGE = "percentChangeFrom50DayMovingAverage";

    /**
     *  TWO HUNDRED DAY MOVING AVERAGE
     */
    const TWO_HUNDRED_DAY_MOVING_AVERAGE = "200DayMovingAverage";

    /**
     *  CHANGE FROM TWO HUNDRED DAY MOVING AVERAGE
     */
    const CHANGE_FROM_TWO_HUNDRED_DAY_MOVING_AVERAGE = "changeFrom200DayMovingAverage";

    /**
     *  PERCENT CHANGE FROM TWO HUNDRED DAY MOVING AVERAGE
     */
    const PERCENT_CHANGE_FROM_TWO_HUNDRED_DAY_MOVING_AVERAGE = "percentChangeFrom200DayMovingAverage";

    /**
     *  NAME
     */
    const NAME = "name";

    /**
     *  NOTES
     */
    const NOTES = "notes";

    /**
     *  OPEN
     */
    const OPEN = "open";

    /**
     *  PREVIOUS CLOSE
     */
    const PREVIOUS_CLOSE = "previousClose";

    /**
     *  PRICE PAID
     */
    const PRICE_PAID = "pricePaid";

    /**
     *  CHANGE IN PERCENT
     */
    const CHANGE_IN_PERCENT = "changeInPercent";

    /**
     *  PRICE PER SALES
     */
    const PRICE_PER_SALES = "pricePerSales";

    /**
     *  PRICE PER BOOK
     */
    const PRICE_PER_BOOK = "pricePerBook";

    /**
     *  EX DIVIDEND DATE
     */
    const EX_DIVIDEND_DATE = "exDividendDate";

    /**
     *  PRICE EARNINGS RATIO
     */
    const PRICE_EARNINGS_RATIO = "priceEarningsRatio";

    /**
     *  DIVIDEND PAY DATE
     */
    const DIVIDEND_PAY_DATE = "dividendPayDate";

    /**
     *  PRICE EARNINGS RATIO REAL TIME
     */
    const PRICE_EARNINGS_RATIO_REAL_TIME = "priceEarningsRatioRealTime";

    /**
     *  PEG RATIO
     */
    const PEG_RATIO = "PEGRatio";

    /**
     *  PRICE PER EPS ESTIMATE CURRENT YEAR
     */
    const PRICE_PER_EPS_ESTIMATE_CURRENT_YEAR = "pricePerEPSEstimateCurrentYear";

    /**
     *  PRICE PER EPS ESTIMATE NEXT YEAR
     */
    const PRICE_PER_EPS_ESTIMATE_NEXT_YEAR = "pricePerEPSEstimateNextYear";

    /**
     *  SYMBOL
     */
    const SYMBOL = "symbol";

    /**
     *  SHARES OWNED
     */
    const SHARES_OWNED = "sharesOwned";

    /**
     *  SHORT RATIO
     */
    const SHORT_RATIO = "shortRatio";

    /**
     *  LAST TRADE TIME
     */
    const LAST_TRADE_TIME = "lastTradeTime";

    /**
     *  TRADE LINKS
     */
    const TRADE_LINKS = "tradeLinks";

    /**
     *  TICKER TREND
     */
    const TICKER_TREND = "tickerTrend";

    /**
     *  ONE YEAR TARGET PRICE
     */
    const ONE_YEAR_TARGET_PRICE = "1YrTargetPrice";

    /**
     *  VOLUME
     */
    const VOLUME = "volume";

    /**
     *  HOLDINGS VALUE
     */
    const HOLDINGS_VALUE = "holdingsValue";

    /**
     *  HOLDINGS VALUE REAL TIME
     */
    const HOLDINGS_VALUE_REAL_TIME = "holdingsValueRealTime";

    /**
     *  FIFTY TWO WEEK RANGE
     */
    const FIFTY_TWO_WEEK_RANGE = "52WeekRange";

    /**
     *  DAYS VALUE CHANGE
     */
    const DAYS_VALUE_CHANGE = "daysValueChange";

    /**
     *  DAYS VALUE CHANGE REAL TIME
     */
    const DAYS_VALUE_CHANGE_REAL_TIME = "daysValueChangeRealTime";

    /**
     *  STOCK EXCHANGE
     */
    const STOCK_EXCHANGE = "stockExchange";

    /**
     *  DIVIDEND YEILD
     */
    const DIVIDEND_YEILD = "dividendYeild";

    /**
     * @var array Quote Options
     */    
    protected $_options = array();

    /**
     * Constructor initializes options
     *
     * @param array $options quote options
     */
    public function __construct( Array $options = null)
    {   
        if( ! is_null( $options ) ) {
            foreach( $options as $key => $value ) {
                $this->set( $key, $value );
            }
        }
    }

    /**
     * magic method to return non public properties
     *
     * @see     get
     * @param   mixed $property
     * @return  mixed
     */
    public function __get( $property )
    {
        return $this->get( $property );
    }

    /**
     * Return the specified property
     *
     * @param mixed $property     The property to return
     * @return mixed
     */
    public function get( $property )
    {
        if( array_key_exists( $property, YahooFinance_Options::$_SpecialTags ) ) {
            if( array_key_exists( $property, $this->_options ) ){
                return $this->_values[$property];
            } else {
                return null;
            }
        } else {
            throw new YahooFinance_Exception(sprintf('Unknown property %s::%s', get_class($this), $property));
        }
    }

    /**
     * magic method to set non public properties
     *
     * @see    set
     * @param  mixed $property
     * @param  mixed $value
     * @return void
     */
    public function __set( $property, $value )
    {
        $this->set( $property, $value );
    }

    /**
     * sets the specified property
     *
     * @param mixed $property     The property to set
     * @param mixed $value        value of property
     * @return void
     */
    public function set( $property, $value )
    {
        if( array_key_exists( $property, YahooFinance_Options::$_SpecialTags ) ){
            if( is_bool( $value ) ){
                $this->_options[$property] = $value;
            } else {
                throw new YahooFinance_Exception(sprintf("Property needs to be set to a boolean [true,false] %s::%s", get_class($this), $property));
            }
        } else {
            throw new YahooFinance_Exception(sprintf('Unknown property %s::%s', get_class($this), $property));
        }
    }

    /**
     * generated paramater string of user requested options.
     *
     * @return string
     */
    public function toParamString()
    {
        $paramStr = "";
        foreach( $this->_options as $key => $value ){
            if( $value ) {
                $paramStr .= YahooFinance_Options::$_SpecialTags[$key];
            }
        }
        return $paramStr;
    }

    /**
     * converts returned data into quote objects
     *
     * @param string $csvStrInput response data
     * @return array Quote Objects
     */
    public function parseResult( $csvStrInput, $delimiter )
    {
        $results = array();
//        $csvStrs = explode("[\n|\r]", $csvStrInput);
        $csvStrs = explode("\n", $csvStrInput);

        foreach( $csvStrs as $csvStr ){
            if( !empty( $csvStr ) ) {
                $csvArray = explode($delimiter, $csvStr );
                $propPos = 0;
                $quote = new YahooFinance_Quote();
                foreach( $this->_options as $key => $value ) {
                    $propVal = $csvArray[$propPos];
                    $propVal = trim( $propVal );
                    if( 0 === strpos($propVal, "\"") ) {
                        $propVal = substr( $propVal, 1, strlen( $propVal ) - 2 );
                    }
					// values different with ; delimiter
					if( $delimiter == ";" ) {
						if( $key == "lastTradeTime" ) {
							$propPos ++;
							$propVal .= " ". $csvArray[$propPos];
						} else if( $key == "lastTradeDate" ) {
							$propPos --;
							$propVal = "";
						}
					}
					
                    $quote->set( $key, $propVal );
                    $propPos ++;
                }
                $results[] = $quote;
            }
        }
        return $results;
    }

}