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
 * Location
 *
 * This file contains the class YahooFinance_Location
 *
 * @author Matthew Denton <matt@mdbitz.com>
 * @package com.mdbitz.YahooFinance
 */

/**
 * YahooFinance_Location defines the various localizations available for
 * the Yahoo Finance Stock Quote API
 * @package com.mdbitz.YahooFinance
 */
class YahooFinance_Location 
{

	/**
     * @var array option variables
     */
    public static $_locals = array(
                              "Argentina" => "ar.finance.yahoo.com/d/quotes.csv",
                              "Australia" => "au.finance.yahoo.com/d/quotes.csv",
                              "Brazil" => "br.finance.yahoo.com/d/quotes.csv",
                              "Canada" => "ca.finance.yahoo.com/d/quotes.csv",
                              "France" => "fr.finance.yahoo.com/d/quotes.csv",
                              "Germany" => "de.finance.yahoo.com/d/quotes.csv",
                              "Hong Kong" => "hk.finance.yahoo.com/d/quotes.csv",
                              "India" => "in.finance.yahoo.com/d/quotes.csv",
                              "Ireland" => "uk.finance.yahoo.com/d/quotes.csv",
                              "Italy" => "it.finance.yahoo.com/d/quotes.csv",
                              "Korea" => "kr.finance.yahoo.com/d/quotes.csv",
                              "New Zealand" => "au.finance.yahoo.com/d/quotes.csv",
                              "Singapore" => "sg.finance.yahoo.com/d/quotes.csv",
                              "Spain" => "es.finance.yahoo.com/d/quotes.csv",
                              "United Kingdom" => "uk.finance.yahoo.com/d/quotes.csv",
							  "United States" => "download.finance.yahoo.com/d/quotes.csv"
							  );

    /**
     *  Argentina
     */
    const ARGENTINA = "Argentina";

    /**
     *  Australia
     */
    const AUSTRALIA = "Australia";

    /**
     *  Brazil
     */
    const BRAZIL = "Brazil";

    /**
     *  Canada
     */
    const CANADA = "Canada";

    /**
     *  France
     */
    const FRANCE = "France";

    /**
     *  Germany
     */
    const GERMANY = "Germany";

    /**
     *  Hong Kong
     */
    const HONG_KONG = "Hong Kong";

    /**
     *  India
     */
    const INDIA = "India";

    /**
     *  Ireland
     */
    const IRELAND = "Ireland";

    /**
     *  Italy
     */
    const ITALY = "Italy";

    /**
     *  Korea
     */
    const KOREA = "Korea";

    /**
     *  New Zealand
     */
    const NEW_ZEALAND = "New Zealand";

    /**
     *  Singapore
     */
    const SINGAPORE = "Singapore";

    /**
     *  Spain
     */
    const SPAIN = "Spain";

    /**
     *  United Kingdom
     */
    const UNITED_KINGDOM = "United Kingdom";

    /**
     *  United States
     */
    const UNITED_STATES = "United States";

	/**
     * get delimiter used by localization
     *
     * @param local localization
     * @return String delimiter
     */
	public static function getDelimiter($local) {
		switch ($local) {
			case self::ARGENTINA :
			case self::BRAZIL :
			case self::FRANCE :
			case self::GERMANY :
			case self::ITALY :
			case self::SPAIN :
				return ";";
			break;
			case self::AUSTRALIA :
			case self::CANADA :
			case self::HONG_KONG :
			case self::INDIA :
			case self::IRELAND :
			case self::KOREA :
			case self::NEW_ZEALAND :
			case self::SINGAPORE :
			case self::UNITED_KINGDOM :
			case self::UNITED_STATES :
			default:
				return ",";
			break;
		}
	}

}