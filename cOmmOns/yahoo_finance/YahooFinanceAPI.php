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
 * YahooFinanceAPI
 *
 * This file contains the class YahooFinanceAPI
 *
 * @author Matthew Denton <matt@mdbitz.com>
 * @package com.mdbitz.YahooFinance
 */

/**
 * main class of the YahooFinance API
 * 
 * <code> 
 * // require the YahooFinance API core class 
 * require_once( PATH_TO_LIB . '/YahooFinanceAPI.php' );
 *	
 * // Register Auto Loader
 * spl_autoload_register(array('YahooFinanceAPI', 'autoload'));
 *
 * 	// instantiate YahooFinanceAPI
 * $api = new YahooFinanceAPI();
 *
 * // set options
 * $api->addOption("symbol");
 * $api->addOption("previousClose");
 * $api->addOption("open");
 * $api->addOption("lastTrade");
 * $api->addOption("lastTradeTime");
 * $api->addOption("change" );
 * $api->addOption("daysLow" );
 * $api->addOption("daysHigh" );
 * $api->addOption("volume" );
 *
 * // set symbols
 * $api->addSymbol("GOOG");
 * $api->addSymbol("DELL");
 *
 * // get quotes
 * $result = $api->getQuotes();
 *
 * if( $result->isSuccess() ) {
 *     $quotes = $result->data;
 *     foreach( $quotes as $quote ) {
 *         echo $quote->symbol;
 *         echo $quote->lastTrade;
 *     }
 * }
 * </code> 
 *
 * @package com.mdbitz.YahooFinance
 */
final class YahooFinanceAPI
{

    /**
     * @var string STRICT MODE
     */
    public static $STRICT = "STRICT";

    /**
     * @var string LOOSE MODE
     */
    public static $LOOSE = "LOOSE";

    /**
     * @var string Error Handling Mode
     */
    protected $_mode;

    /**
     * @var array symbols of quotes requested for
     */
    protected $_symbols = array();
 
    /**
     * @var YahooFinance_Options quote options
     */
    protected $_options = null;

    /**
     * @var string $path YahooFinance root directory
     */
    private static $_path;

    /**
     * @var string $local YahooFinance Localization
     */
    private $_local = "default";

    /**
     * Constructor
     * @param string $mode Error handling mode
     */
    public function __construct( $mode = "STRICT" )
    {
        $this->_options = new YahooFinance_Options();
        $this->_mode = $mode;
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
        switch( $property ){
            case 'symbols':
                return $this->_symbols;
            break;
            case 'options':
                return $this->_options;
            break;
			case 'local':
				return $this->_local;
			break;
            default:
                if( $this->_mode == $STRICT ) {
                    throw new YahooFinance_Exception(sprintf('Unknown property %s::%s', get_class($this), $property));
                } else {
                    return null;
                }
            break;
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
        switch( $property ){
            case 'symbols':
                if( ! is_null( $value ) && is_array( $value ) ) {
                    $this->_symbols = $value;
                } else {
                    if( $this->_mode == YahooFinanceAPI::$STRICT ) {
                        throw new YahooFinance_Exception(sprintf('symbols property expects an array %s', get_class($this) ) );
                    }
                }
            break;
            case 'options':
                if( $value instanceof YahooFinance_Options ) {
                    $this->_options = $value;
                } else {
                    if( $this->_mode == YahooFinanceAPI::$STRICT ) {
                        throw new YahooFinance_Exception(sprintf('options property expects an object of type YahooFinance_Options %s', get_class($this) ) );
                    }
                }
            break;
			case 'local':
				 if( array_key_exists( $value, YahooFinance_Location::$_locals ) ){
			            $this->_local = $value;
			     } else {
			     	if( $this->_mode == YahooFinanceAPI::$STRICT ) {
			         	throw new YahooFinance_Exception(sprintf('Unknown local %s::%s', get_class($this), $value));
			        }
			     }
			break;
            default:
                if( $this->_mode == YahooFinanceAPI::$STRICT ) {
                    throw new YahooFinance_Exception(sprintf('Unknown property %s::%s', get_class($this), $property));
                }
            break;
        }
    }

    /**
     * add Symbol to list of quotes to be obtained
     *
     * @param string $symbol Symbol to be added
     * @return void
     */
    public function addSymbol( $symbol )
    {
        if( ! is_null( $symbol ) && ! in_array( $symbol, $this->_symbols ) ){
            $this->_symbols[] = $symbol;
        }
    }

    /**
     * remove symbol from list to obtain quotes for
     *
     * @param string $symbol Symbol to be removed
     * @return void
     */
    public function removeSymbol( $symbol )
    {
        if( ! is_null( $symbol ) ) {
            $key = array_search( $symbol, $this->_symbols );
            if( $key != NULL || $key !== FALSE ) {
                unset( $this->_symbols );
            }
        }
    }

    /**
     * add Option to information to be returned
     *
     * @param string $option Quote property
     * @return void
     */
    public function addOption( $option )
    {
        if( ! is_null( $option ) && array_key_exists( $option, YahooFinance_Options::$_SpecialTags ) ){
            $this->_options->set( $option, true );
        } else {
            if( $this->_mode == YahooFinanceAPI::$STRICT ) {
                throw new YahooFinance_Exception(sprintf('Unknown option %s::%s', get_class($this), $option));
            }
        }
    }

    /**
     * remove Option from information to be returned
     *
     * @param string $option Quote property
     * @return void
     */
    public function removeOption( $option )
    {
        if( ! is_null( $option ) && array_key_exists( $option, YahooFinance_Options::$_SpecialTags ) ) {
            if( $this->_options->get( $option ) === true ) {
                $this->_options->set( $option, false );
            }
        }
    }

    /**
     * resets all symbols and options
     *
     * @return void
     */
    public function clear()
    {
        $this->_options = new YahooFinance_Options();
        $this->_symbols = array();
    }

    /**
     * gets Quotes for symbols with set options
     *
     * @return void
     */
    public function getQuotes()
    {
        $url = $this->generateURL();

        if(function_exists('curl_exec')) {
            return $this->curlRequest( $url );
        } else {
            return $this->fopenRequest( $url );
        }
    }

	/**
	 * generate the url to be requested
	 *
	 * @return String
	 */
	private function generateURL() {
		$url = "http://";
		if( $this->_local == "default" ) {
			$url .= "download.finance.yahoo.com/d/quotes.csv";
		} else {
			$url .= YahooFinance_Location::$_locals[$this->_local];
		}
		$url .= "?s=" . implode( "+", $this->_symbols ) . "&f=" .$this->_options->toParamString() . "&e=.csv";

		return $url;
	}

    /**
     * perform curl request of specified url
     *
     * @param url Request Server Location
     * @return YahooFinance_Result
     */
    private function curlRequest( $url)
    {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $resp = curl_exec( $ch );
        $code = curl_getinfo($ch,CURLINFO_HTTP_CODE);
        curl_close($ch);
        $data = $this->processResponse( $resp );
        return new YahooFinance_Result( $code, $data );
    }

    /**
     * perform fopen request of specified url
     *
     * @param url Request Server Location
     * @return YahooFinance_Result
     */
    private function fopenRequest( $url )
    {
        $http_options = array('method'=>'GET','timeout'=>3);
        $context = stream_context_create(array('http'=>$http_options));
        $resp = @file_get_contents($url, null, $context);
        $data = null;
        $code = 400;
        if( $resp !== false ) {
            $code = 200;
            $data = $this->processResponse( $resp );
        }
        return new YahooFinance_Result( $code, $data );
    }

    /**
     * process response
     *
     * @param response Response Data
     * @return array
     */
    private function processResponse( $response )
    {
		if( $this->_local == "default" ) {
        	return $this->_options->parseResult( $response, "," );
		} else {
        	return $this->_options->parseResult( $response, YahooFinance_Location::getDelimiter($this->_local) );
		}
    }

    /**
     * simple autoload function
     * returns true if the class was loaded, otherwise false
     *
     * @param string $classname
     * @return boolean
     */
    public static function autoload($className)
    {
        if (class_exists($className, false) || interface_exists($className, false)) {
           return false;
        }

        $class = self::getPath() . DIRECTORY_SEPARATOR . str_replace('_', DIRECTORY_SEPARATOR, $className) . '.php';

        if (file_exists($class)) {
            require $class;
            return true;
        }

        return false;
    }

    /**
     * Get the root path to Yahoo Finance API
     *
     * @return string
     */
    public static function getPath()
    {
        if ( ! self::$_path) {
            self::$_path = dirname(__FILE__);
        }

        return self::$_path;
    }

}