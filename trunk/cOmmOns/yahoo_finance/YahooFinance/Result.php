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
 * Result
 *
 * This file contains the class YahooFinance_Result
 *
 * @author Matthew Denton <matt@mdbitz.com>
 * @package com.mdbitz.YahooFinance
 */

/**
 * YahooFinance_Result defines the result object returned by a request
 * made by the YahooFinanceAPI.
 * 
 * <b>Properties</b>
 * <ul>
 *   <li>code</li>
 *   <li>data</li>
 * </ul>
 *
 * @package com.mdbitz.YahooFinance
 */
class YahooFinance_Result
{

    /**
     * @var string response code
     */
    protected $_code = null;

    /**
     * @var array response data
     */
    protected $_data = null;

    /**
     * Constructor initializes {@link $_code} {@link $_data}
     *
     * @param string $code response code
     * @param array $data array of Quote Objects
     */
    public function __construct( $code = null, $data = null)
    {
        $this->_code = $code;
        $this->_data = $data;
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
        return $this->get( $property);
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
            case 'code':
                return $this->_code;
            break;
            case 'data':
                return $this->_data;
            break;
            default:
                throw new YahooFinance_Exception(sprintf('Unknown property %s::%s', get_class($this), $property));
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
            case 'code':
                $this->_code = $value;
            break;
            case 'data':
                $this->_data = $value;
            break;
            default:
                throw new YahooFinance_Exception(sprintf('Unknown property %s::%s', get_class($this), $property));
            break;
        }
    }

    /**
     * returns a JSON representation of this Result
     *
     * @return string
     */
    public function toJSON( )
    {
    
        $json = "{ \"code\" : \"$this->code\", \"data\" : [ ";
        foreach( $this->_data as $quote ) {
            $json .= $quote->toJSON() . ", ";
        }
        $json = substr( $json, 0, strrpos($json, ",") );
        $json .= " ] }";
        return $json;
    }

    /**
     * returns an XML representation of this Result
     *
     * @return string
     */
    public function toXML( )
    {
    
        $xml = "<result><code>$this->code</code><data>";
        foreach( $this->_data as $quote ) {
            $xml .= $quote->toXML();
        }
        $xml .= "</data></result>";
        return $xml;
    }

	/**
     * is request successfull
     * @return boolean
     */
    public function isSuccess() 
    {
        if( "2" == substr( $this->_code, 0, 1 ) ) {
            return true;
        } else {
            return false;
        }
    }

}