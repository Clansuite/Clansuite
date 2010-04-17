<?php

/*
 *  csQuery is a fork of the deprecated gsQuery by Jeremias Reith. 
 *  It's also inspired by gameq, squery, phgstats
 *  and several other projectes like kquery and hlsw. 
 *
 *  csQuery - gameserver query class
 *  Copyright (c) 2005-2006 Jens-André Koch <jakoch@web.de>
 *  http://www.clansuite.com
 *
 *  gsQuery - Querys game servers
 *  Copyright (c) 2002-2004 Jeremias Reith <jr@terragate.net>
 *  http://www.gsQuery.org
 *
 *  This file is part of the e-sport CMS Clansuite.
 *  This file is part of the csQuery gameserver query library.
 *
 *  The csQuery library is free software; you can redistribute it and/or
 *  modify it under the terms of the GNU Lesser General Public
 *  License as published by the Free Software Foundation; either
 *  version 2.1 of the License, or (at your option) any later version.
 *
 *  The csQuery library is distributed in the hope that it will be useful,
 *  but WITHOUT ANY WARRANTY; without even the implied warranty of
 *  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the GNU
 *  Lesser General Public License for more details.
 *
 *  You should have received a copy of the GNU Lesser General Public
 *  License along with the csQuery library; if not, write to the
 *  Free Software Foundation, Inc.,
 *  59 Temple Place, Suite 330, Boston,
 *  MA  02111-1307  USA
 *
 *  SVN: $Id$
 */

/**
 * @brief Makes hexdumps out of given data
 * @author Jeremias Reith (jr@gsquery.org)
 * @version $Revision$
 *
 * Non ASCII bytes will be colored blue in html dumps
 */
class HexDumper
{
  /** 
   * @brief array of values to highlight
   *
   * Format: value => html color
   */
  var $highlightValues = array();

  /** @brief stores the offset of the header end */
  var $endOfHeader;

  /** @brief stores the offset where the terminator starts */
  var $startOfTerminator;

  /**
   * @brief The given value will be colored in html dumps
   * @param value the value that should be colored
   *
   * Use this to colorize a delimiter (red)
   */
  function setDelimiter($value)
  {
    $this->highlightValues[$value] = 'red';
  }

  /** 
   * @brief sets the offset of the header's end
   * @param offset offset of the header's end
   * 
   * Use this to colorize the header in html dumps (green)
   */
  function setEndOfHeader($offset)
  {
    $this->endOfHeader = $offset;
  }

  /**
   * @brief creates an ASCII hex dump out of the given data
   * @param data the data to use
   * @return the resultung dump
   */
  function createASCIIDump($data)
  {
    $dump = '';
    $len = strlen($data);
  
    $od = 0;

    // calculating number of offset digits required
    while(pow($od++, 16)<$len) {};
  
    // looping through data (2 bytes per iteration)
    for($i=0;$i<$len;$i+=16) {
      $xbyte1 = '';
      $abyte1 = '';

      // processing first byte
      $end = $i+8 >= $len ? $len : $i+8;
      for($j=$i;$j<$end;$j++) {
	$xbyte1 .= sprintf('%02x ', ord($data{$j}));
	$abyte1 .= ord($data{$j}) >= 32 ? $data{$j} : '.';
      }

      $xbyte2 = '';
      $abyte2 = '';

      // processing second byte
      $end = $i+16 >= $len ? $len : $i+16;
      for($j=$i+8;$j<$end;$j++) {
	$xbyte2 .= sprintf('%02x ', ord($data{$j}));
	$abyte2 .= ord($data{$j}) >= 32 ? $data{$j} : '.';
      }

      // adding line to hex dump
      $dump .= sprintf("%0{$od}x %-24s %-24s %s %s\n", $i, $xbyte1, $xbyte2, $abyte1, $abyte2);
    }

    return $dump;
  }

  /**
   * @brief creates an HTML hex dump out of the given data
   * @param data the data to use
   * @param css2 set to TRUE and the hex dump will be laid out using CSS 2
   * @return the resultung dump
   * @see http://www.w3.org/TR/REC-CSS2/text.html#white-space-prop
   * 
   * AFAIK the gecko engine (Mozilla, Firefox, Camino...) supports the CSS2
   * property white-space only. Set css2 to FALSE for all other browsers.
   */
  function createHTMLDump($data, $css2=FALSE)
  {
    // dirty hack to force correct padding if browser does not support CSS2
    $hexPadding = '<span style="color: white;">**</span>';
    $dump = '<div style="font-family: monospace; font-size: 10pt;">';

    if($css2) {
      $hexPadding = '  ';
      // yeah, doing it with CSS2 is so easy
      $dump = '<div style="white-space: pre; font-family: monospace; font-size: 10pt;">';
    }

    $od = 0;
    $len = strlen($data);

    // calculationg required offset digits
    while(pow($od++, 16)<$len) {};
  
    // looping through data (2 bytes per iteration)
    for($i=0;$i<$len;$i+=16) {
      $xbyte1 = '';
      $abyte1 = '';

      // processing first byte
      $end = $i+8 >= $len ? $len : $i+8;
      for($j=$i;$j<$end;$j++) {
	$xbyte1 .= $this->_makeHTMLHex(ord($data{$j}), $j);
	$abyte1 .= $this->_makeHTMLASCII($data{$j}, $j);
      }

      $xbyte2 = '';
      $abyte2 = '';

      // processing second byte
      $end = $i+16 >= $len ? $len : $i+16;
      for($j=$i+8;$j<$end;$j++) {
	$xbyte2 .= $this->_makeHTMLHex(ord($data{$j}), $j);
	$abyte2 .= $this->_makeHTMLASCII($data{$j}, $j);
      }

      // padding
      if($i+8 > $len) {
	$xbyte1 .= str_repeat($hexPadding .' ', ($i+8)-$len);
	$xbyte2 .= str_repeat($hexPadding .' ', 8);
      } elseif($i+16 > $len) {
	$xbyte2 .= str_repeat($hexPadding .' ', ($i+16)-$len);
      }

      // adding line to hex dump
      $line = sprintf("%0{$od}x %s %s %s %s %s\n", $i, $xbyte1, $hexPadding, $xbyte2, $abyte1, $abyte2);

      if($css2) {
	$dump .= $line;
      } else {
	$dump .= '<div style="font-family: monospace;">'. $line . '</div>';
      }
    }

    return $dump .'</div>';
  }

  /**
   * @brief formats the given number for a hex dump
   * @param value the number to use
   * @param offset the offset of the given number
   * @return a white space padded 2 digit possibly colorized hex number 
   */
  function _makeHTMLHex($value, $offset)
  {
    if(isset($this->endOfHeader) && $offset<=$this->endOfHeader) {
      return sprintf('<span style="color: %s;">%02x </span>', 
		     'green',
		     $value);
    }

    if(array_key_exists($value, $this->highlightValues)) {
      return sprintf('<span style="color: %s;">%02x </span>', 
		     $this->highlightValues[$value],
		     $value);
    }

    if($value<32) {
      return sprintf('<span style="color: blue;">%02x </span>', $value);
    }
    
    return sprintf('%02x ', $value);
  } 

  /**
   * @brief formats the given value for the ASCII part of an hex dump
   * @param char the character to format 
   * @param offset the offset of the given character
   * @return a possibly colorized, HTML encoded character
   * 
   * Non ASCII characters will be replaced with a dot
   */
  function _makeHTMLASCII($char, $offset) 
  {
    if(ord($char)<32) {
      $char = '.';
    }

    if(isset($this->endOfHeader) && $offset<=$this->endOfHeader) {
      return sprintf('<span style="color: %s;">%s</span>', 
		     'green',
		     htmlentities($char));
    }

    if(array_key_exists(ord($char), $this->highlightValues)) {
      return sprintf('<span style="color: %s;">%s</span>', 
		     $this->highlightValues[ord($char)],
		     htmlentities($char));
    }

    if(ord($char)<32) {
      return '<span style="color: blue;">.</span>';
    }
    
    return htmlentities($char);
  }

}

?>
