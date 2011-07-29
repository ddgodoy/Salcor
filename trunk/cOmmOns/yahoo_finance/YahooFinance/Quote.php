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
 * Quote
 *
 * This file contains the class YahooFinance_Quote
 *
 * @author Matthew Denton <matt@mdbitz.com>
 * @package com.mdbitz.YahooFinance
 */

/**
 * YahooFinance_Quote defines the resulting Quote returned for an
 * individual symbol.
 *
 * @package com.mdbitz.YahooFinance
 */
class YahooFinance_Quote
{

    /**
     * @var array quote values
     */
    protected $_values = array();

    /**
     * Constructor
     *
     */
    public function __construct( )
    {

    }

    /**
     * magic method to return non public properties
     *
     * @see     get
     * @param   mixed $property
     * @return  mixed
     */
    public function __get( $property)
    {
        return $this->get( $property);
    }

    /**
     * Return the specified property
     *
     * @param mixed $property     The property to return
     * @return mixed
     */
    public function get( $property)
    {
        if( array_key_exists( $property, YahooFinance_Options::$_SpecialTags ) ){
            if( array_key_exists( $property, $this->_values ) ){
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
        if( array_key_exists( $property, YahooFinance_Options::$_SpecialTags ) ) {
            $this->_values[$property] = $value;
		} else {
            throw new YahooFinance_Exception(sprintf('Unknown property %s::%s', get_class($this), $property));
        }
    }

    /**
     * returns a JSON representation of this Quote
     *
     * @return String
     */
    public function toJSON( )
    {
        $json = "{ ";
        foreach( $this->_values as $key => $value ) {
            $json .= "\"$key\" : \"$value\", ";
        }
        $json = substr( $json, 0, strrpos($json, ",") );
        $json .= " }";
        return $json;
    }

    /**
     * returns an XML representation of this Quote
     *
     * @return String
     */
    public function toXML( )
    {
        $xml = "<quote>";
        foreach( $this->_values as $key => $value ) {
            $xml .= "<$key>$value</$key>";
        }
        $xml .= "</quote>";
        return $xml;
    }

}