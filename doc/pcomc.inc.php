<?php
/*

   phpComponent Architecture
   Copyright (c) 2003 New York PHP.  All rights reserved.
   This file is subject to the license found at http://nyphp.org/license.txt
   or upon request by contacting contact@nyphp.org

*/


/***

   phpComponent Client (pcomc)  PHP Source
   v0.5


   NOTE: Under moderate load, there are contingency issues with the local cache files.  We'll need
   to implement locking in some fashion.  So far, we saw issues at:
      -- unlink() failed due to non-existent file
      -- filemtime() failed due to non-existent file
      -- fopen(),fwrite(),require() fail for same reason
      -- resulting in occasional call to undefined function errors
   We should consider lockfiles (flock/dio_fcntl) or using temp. files.  While there wasn't any
   sign of corruption during initial testing, this is obviously something that needs to be
   addressed.

   We also need to look into the case where the same PCOM is pinclude()ed multiple times per request.
   It seems that now it tries to fetch it multiple times, depending on expire and max age.  Perhaps
   we'll have to check the output from included_files()?  This is probably must implemented by
   having the end-user use wrapper functions, pinclude_once() and pinclude().  Thus, renaming
   the current pinclude()  (below)  to __pinclude()

***/


define('PCOMC_VERSION', '0.5');



function pinclude( $pcoms ) {

   static $NOWTS = 0;

   static $eohposi = 0;

   static $pcomd_host = NULL;
   static $pcom_name = NULL;
   static $pcom_fqbp = NULL;
   static $pcom_meta = array(array());

   static $http_header = NULL;
   static $http_header_len = 0;

   static $pcomd_header = NULL;
   static $pcomd_pcom = NULL;
   static $pcomd_buf = NULL;
   static $pcomd_httpstatus = NULL;
   static $pcomd_expires = 0;
   static $pcomd_digest = NULL;

   static $pcomd_fp = array();
   static $pcomc_fp;


      /*** This is purposely minimalistic; and harsh ***/
   if( !$_SERVER['PCOMC_CACHE_DIR'] || !isset($_SERVER['PCOMC_CACHE_MAX_AGE']) ||
       !isset($_SERVER['PCOMC_HONOR_EXPIRED']) || !isset($_SERVER['PCOMC_CONNECT_TIMEOUT']) ||
       !$_SERVER['PCOMC_CONNECT_PORT'] || !isset($_SERVER['PCOMC_READ_TIMEOUT']) )
      trigger_error('Not all configuration directives present - check Apache configuration', E_USER_ERROR);

   $NOWTS = time();

   if( !is_array($pcoms) )
      settype($pcoms,'array');


  foreach( $pcoms as $pcom ) {

   if( !($eohposi = strpos($pcom,'/')) ) {
      trigger_error("pinclude($pcom): Invalid FQPN");
      continue;
   }

   $pcomd_host = substr($pcom,0,$eohposi);
   $pcom_name = substr($pcom,$eohposi+1);
   $pcom_fqbp = "{$_SERVER['PCOMC_CACHE_DIR']}{$pcomd_host}({$pcom_name})";


      /***
         Is this PCOM already local and is it fresh enough for us?
         The pragmatics here might be a bit odd:
            -- if our cached copy is older than PCOMC_CACHE_MAX_AGE, refetch the file
            -- otherwise, if we honor remote expirations and it's expired, delete the file so the
               next request will get a fresh copy
            -- we don't do "just-in-time" refetches for expired PCOMs
            -- we do do "just-in-time" refetches for max_aged PCOMs

         We might want to add another directive, PCOMC_ONE_SHOT
      ***/


   if( is_readable($pcom_fqbp) ) {

      if( $_SERVER['PCOMC_CACHE_MAX_AGE'] &&
          (($NOWTS - filemtime($pcom_fqbp)) > $_SERVER['PCOMC_CACHE_MAX_AGE']) )
         ;
      else {
         $pcom_meta[$pcom] = require_once($pcom_fqbp);

         if( $_SERVER['PCOMC_HONOR_EXPIRED'] && ($NOWTS > $pcom_meta[$pcom][0]) )
            unlink($pcom_fqbp);

         continue;
      }
   }


      /*** OK, so we'll need to fetch the PCOM from it's PCOMd ***/

   if( !($pcomd_fp[$pcom] = fsockopen($pcomd_host,$_SERVER['PCOMC_CONNECT_PORT'],$errno,$errstr,$_SERVER['PCOMC_CONNECT_TIMEOUT'])) ) {
      trigger_error("pinclude($pcom): Connection to PCOMd $pcomd_host Failed: $errno => $errstr");
      continue;
   }

   stream_set_timeout($pcomd_fp[$pcom],$_SERVER['PCOMC_READ_TIMEOUT']);

      /***
         The header formed here is:

            GET /pcom_name HTTP/1.0
            Host: pcomd_host
            User-Agent: phpComponent Client
            X-PCOMc-Version: PCOMC_VERSION
            X-PCOMc-ID: 12345678901234567890123456789012
            X-PCOMc-Action: Fetch
      ***/

   $http_header = "GET /{$pcom_name} HTTP/1.0\r\nHost: {$pcomd_host}\r\nUser-Agent: phpComponent Client\r\nX-PCOMc-Version: ".PCOMC_VERSION."\r\nX-PCOMc-ID: 12345678901234567890123456789012\r\nX-PCOMc-Action: Fetch\r\n\r\n";
   $http_header_len = strlen($http_header);

   if( fwrite($pcomd_fp[$pcom],$http_header,$http_header_len) !== $http_header_len ) {
      trigger_error("pinclude($pcom): Unable to write all $http_header_len header bytes to PCOMd $pcomd_host");
      continue;
   }

   $pcomd_buf = NULL;
   $pcomd_header = NULL;
   $pcomd_httpstatus = NULL;
   $pcomd_expires = 0;
   $pcomd_digest = NULL;
   while( !feof($pcomd_fp[$pcom]) ) {

      $pcomd_buf = fgets($pcomd_fp[$pcom]);
      if( ($pcomd_buf{0} === "\r") || ($pcomd_buf{0} === "\n") )
         break;

      $pcomd_header .= $pcomd_buf;

         /*** Some of this may be fragile ***/
      if( strpos($pcomd_buf,'HTTP/1.') === 0 )
         $pcomd_httpstatus = substr($pcomd_buf,0,12);
      else if( (strpos($pcomd_buf,'X-PCOM-Expires: ') === 0) && (strlen($pcomd_buf) === 28) )
         $pcomd_expires = substr($pcomd_buf,16,10);
      else if( (strpos($pcomd_buf,'X-PCOM-Digest: ') === 0) && (strlen($pcomd_buf) === 57) )
         $pcomd_digest = substr($pcomd_buf,15,40);
   }
   // DEBUG:  trigger_error($pcomd_header);


   if( !$pcomd_header || !$pcomd_httpstatus || !strpos($pcomd_httpstatus,'200') ||
       !$pcomd_expires || !$pcomd_digest )
     {
      trigger_error("pinclude($pcom): Malformed PCOMd response or PCOM not found");
      continue;
   }


   $pcomd_pcom = NULL;
   while( !feof($pcomd_fp[$pcom]) )
      $pcomd_pcom .= fgets($pcomd_fp[$pcom]);

   if( $pcomd_digest !== sha1($pcomd_pcom) ) {
      trigger_error("pinclude($pcom): Integrity check failed");
      continue;
   }

   $pcomd_pcom .= "\n\nreturn array($pcomd_expires,'$pcomd_digest');\n\n";


      /*** Finally write it to the local cache and pull it in ***/

   if( !($pcomc_fp = fopen($pcom_fqbp,'w+')) ) {
      trigger_error("pinclude($pcom): Unable to open local cache file for truncate: $pcom_fqbp");
      continue;
   }

   if( !fwrite($pcomc_fp,$pcomd_pcom) ) {
      trigger_error("pinclude($pcom): Unable to write to local cache file: $pcom_fqbp");
      continue;
   }

   fclose($pcomc_fp);

   require_once($pcom_fqbp);

  } /*** foreach( $pcoms as $pcom ) ***/

   return TRUE;
}


