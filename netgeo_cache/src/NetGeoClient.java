
/*****************************************************************************
* NetGeoClient.java
*
* $Id: NetGeoClient.java,v 1.2 2003/02/13 13:09:40 kozlik Exp $
* $Author: kozlik $
* $Name:  $
* $Revision: 1.2 $
*
* Copyright 1999 The Regents of the University of California
* All Rights Reserved
*
* Permission to use, copy, modify and distribute any part of this
* NetGeoClient software package for educational, research and non-profit
* purposes, without fee, and without a written agreement is hereby
* granted, provided that the above copyright notice, this paragraph and
* the following paragraphs appear in all copies.
*
* Those desiring to incorporate this into commercial products or use
* for commercial purposes should contact the Technology Transfer
* Office, University of California, San Diego, 9500 Gilman Drive, La
* Jolla, CA 92093-0910, Ph: (858) 534-5815, FAX: (858) 534-7345.
*
* IN NO EVENT SHALL THE UNIVERSITY OF CALIFORNIA BE LIABLE TO ANY
* PARTY FOR DIRECT, INDIRECT, SPECIAL, INCIDENTAL, OR CONSEQUENTIAL
* DAMAGES, INCLUDING LOST PROFITS, ARISING OUT OF THE USE OF THIS
* SOFTWARE, EVEN IF THE UNIVERSITY OF CALIFORNIA HAS BEEN ADVISED OF
* THE POSSIBILITY OF SUCH DAMAGE.
*
* THE SOFTWARE PROVIDED HEREIN IS ON AN "AS IS" BASIS, AND THE
* UNIVERSITY OF CALIFORNIA HAS NO OBLIGATION TO PROVIDE MAINTENANCE,
* SUPPORT, UPDATES, ENHANCEMENTS, OR MODIFICATIONS. THE UNIVERSITY
* OF CALIFORNIA MAKES NO REPRESENTATIONS AND EXTENDS NO WARRANTIES
* OF ANY KIND, EITHER IMPLIED OR EXPRESS, INCLUDING, BUT NOT LIMITED
* TO, THE IMPLIED WARRANTIES OF MERCHANTABILITY OR FITNESS FOR A
* PARTICULAR PURPOSE, OR THAT THE USE OF THE SOFTWARE WILL NOT INFRINGE
* ANY PATENT, TRADEMARK OR OTHER RIGHTS.
*
* The NetGeoClient software package is developed by the NetGeo development
* team at the University of California, San Diego under the Cooperative
* Association for Internet Data Analysis (CAIDA) Program.  Support for
* this effort is provided by NSF grant ANI-9996248.  Additional sponsors
* include APNIC, ARIN, GETTY, NSI and RIPE NCC.
*
* Report bugs and suggestions to netgeo-bugs@caida.org.  For more
* information and documentation visit: http://www.caida.org/Tools/NetGeo/.
*
* @author Jim Donohoe, CAIDA
*****************************************************************************/

/*****************************************************************************
* Lookup targets
* --------------
* 1. AS Number
* An AS number can be given as a string of numerals, with or without a
* leading "AS".  For example, the following are all equivalent:
* getRecord( "1234" ), getRecord( "AS1234" ), getRecord( "AS 1234" )
* NOTE: The NetGeoClient.java interface differs slightly from the
* NetGeoClient.pm (Perl) interface; the Java interface doesn't accept
* integer AS number arguments.  NetGeoClient.java accepts only strings,
* so any integer arguments must first be converted to strings, e.g.,
* getRecord( Integer.toString( 1234 ) ).
*
* 2. IP Address
* An IP address must be given as a string in dotted decimal format, e.g.,
* "192.172.226.30".
*
* 3. Domain Name must be given as a string, e.g., "caida.org".
*****************************************************************************/

import java.net.*;
import java.io.*;
import java.util.*;
import java.lang.reflect.Array;

public class NetGeoClient
{
   public static final String VERSION = "1.1";

   public static String DEFAULT_SERVER_URL =
   "http://netgeo.caida.org/perl/netgeo.cgi";

   //------------------------------------------------------------------------
   //                         Status strings
   //------------------------------------------------------------------------

   // Status string returned when a lookup is successful and the results are
   // available in the returned data structure.
   public static final String OK = "OK";

   // Status string returned when a lookup is in progress on a separate
   // thread.  When an array query is performed and nonblocking is specified
   // the targets that are not found in the database will be looked up on
   // a separate thread and stored into the database, so they will be ready
   // for a subsequent query.
   public static final String LOOKUP_IN_PROGRESS = "Lookup In Progress";

   // Status string returned when the target is not found in any whois
   // database.
   public static final String NO_MATCH = "No Match";

   // Status string returned when a whois record is found and parsed but the
   // country name cannot be parsed or guessed from the record.  This may be
   // due to the record containing a non-standard spelling for the country
   // or perhaps the record doesn't contain any location information.  This
   // status string is only returned in getCountry, getCountryArray, and
   // updateCountryArray queries.
   public static final String NO_COUNTRY = "No Country";

   // Status string returned for a latitude/longitude query when there is
   // a failure to match the target in the whois databases or when the
   // address can't be parsed, or when the latitude/longitude for a parsed
   // address is unknown.
   public static final String UNKNOWN = "Unknown";

   // Status string returned when a whois server is unavailable.  The status
   // string will consist of WHOIS_ERROR followed by the name of the whois
   // server that couldn't be reached, e.g. "WHOIS_ERROR: whois.internic.net".
   // If a whois error occurs (usually server temporarily down) an error
   // string is returned in the STATUS field of hash returned by getRecord or
   // any array method, or as the string result of getCountry.
   public static final String WHOIS_ERROR = "WHOIS_ERROR";

   // Status string returned when the NetGeo server is unavailable or some
   // other error occurs during the http session between the NetGeo client
   // and server.  If there is an error connecting to the NetGeo server the
   // error string will consist of HTTP_ERROR followed by the HTTP error code
   // and description, e.g., "HTTP_ERROR: 404 File Not Found".
   public static final String HTTP_ERROR = "HTTP_ERROR";

   // Status string returned when the input target is not in a valid format.
   // An AS number target may be a string or number, with or without leading
   // "AS", e.g., 123, "123", "AS 123", or "AS123".  A domain name target
   // must be a string, e.g. "caida.org".  An IP address target must be a
   // string in dotted decimal format, e.g., "192.172.226.77".
   public static final String INPUT_ERROR = "INPUT_ERROR";

   // Status string returned when a whois lookup is stopped by the NetGeo
   // server because the lookup has exceeded a time limit.  The status
   // string will consist of WHOIS_TIMEOUT followed by the name of the whois
   // server that was being queried when the search timed out, e.g.
   // "WHOIS_TIMEOUT: whois.internic.net".  Note: the server that exceeds the
   // time limit is not always the cause of the delay.  Some targets require
   // lookups on different servers (e.g., whois.arin.net then whois.ripe.net)
   // and a slow lookup on the first server may consume nearly the whole time
   // limit, leading to a timeout during the second lookup.
   public static final String WHOIS_TIMEOUT = "WHOIS_TIMEOUT";

   // Status string returned when the user exceeds the rate limit set by the
   // NetGeo server.  All subsequent requests from the user will be rejected
   // for a short time period (e.g., 30s or 1 minute) starting from the most
   // recent rejected query--polling the NetGeo server will cause the user to
   // be locked out for a longer time.
   public static final String NETGEO_LIMIT_EXCEEDED = "NETGEO_LIMIT_EXCEEDED";

   // When polling the database using the updateXxxArray methods, the
   // error codes (WHOIS_ERROR, HTTP_ERROR, NETGEO_LIMIT_EXCEEDED, and
   // INPUT_ERROR) must be cleared from the status field before
   // attempting a new lookup.

   // Maximum number of entries allowed in an array argument.  This limit is
   // enforced in order to limit the length of the query string sent from the
   // client to the server and to limit the length of the response from the
   // server.  The array length will also be tested and enforced at the
   // server.
   public static final int ARRAY_LENGTH_LIMIT = 100;

   // Values which may be returned in the LAT_LONG_GRAN field.  LAT_LONG_GRAN
   // field may be empty if lat/long = (0,0), which indicates some kind of
   // error or timeout condition.  A value of $CITY_GRAN indicates the
   // lat/long was found from a lookup of the city parsed from the whois
   // address.  A value of $STATE_GRAN means the lat/long was found from a
   // lookup of the state or province, and a value of $COUNTRY_GRAN means the
   // lat/long was found from a lookup of the country.
   public static final String CITY_GRAN = "City";
   public static final String STATE_GRAN = "State/Prov";
   public static final String COUNTRY_GRAN = "Country";

   // Maximum length of time, in seconds, which will be allowed during
   // a whois lookup by the NetGeo server.  The actual default value
   // is maintained by the server.
   public static final int DEFAULT_TIMEOUT = 60;


   // Indices into the 2-element latitude/longitude array.  convertLatLong
   // can be applied to the result of getLatLong, to convert the lat and long
   // values in a hashtable into a 2-element float array.  The latitude will
   // be the array element at LAT_INDEX, the longitude will be the array
   // element at LONG_INDEX.
   public static final int LAT_INDEX = 0;
   public static final int LONG_INDEX = 1;

   public static boolean DEBUG = false;

   //------------------------------------------------------------------------
   //                         private data
   //------------------------------------------------------------------------

   // String formed in the constructor from an application name supplied by
   // the invoker (optional), NetGeoClient name/version, the Java version
   // and vendor, and the operating system this code is running on.  The
   // NetGeo server will use the user-agent name to track platform-specific
   // problems.  For example, the string might be:
   // "plot_AS_loc/1.2.3 NetGeoClient/1.0 Sun Micr/1.1.7 Linux".
   private String _userAgent;

   // URL of the NetGeo server, will almost always be the default value.
   private String _netGeoUrl;

   // A true value for _nonblocking will be sent to the server to indicate
   // that the client doesn't want to block on whois lookups.  The default
   // is for _nonblocking to be false, i.e., the client is willing to wait
   // for whois lookups to return an answer.
   private boolean _nonblocking;

   // The timeout value is the maximum number of seconds the client wants
   // to wait on a single whois lookup.
   private int _timeout;

   // In-memory storage for results, to make repeated queries faster. Default
   // is for NetGeoClient to use local caching.
   private Hashtable _localCache;

//===========================================================================
//                             PUBLIC METHODS
//===========================================================================

/**
  * Description:
  *
  * @param String[] argv
  * @return void
  * @author Jim Donohoe, CAIDA
  */
   public static void main( String[] argv )
   {
      //String testUrl = "http://cider.caida.org/cgi-bin/netgeo/netgeo.cgi";
      String testUrl = "http://www.caida.org/cgi-bin/netgeo/netgeo.cgi";
      NetGeoClient netgeo = new NetGeoClient( "TEST", testUrl );

      String target = "caida.org";
      System.out.println( "Testing getCountry( \"" + target + "\" )" );
      String country = netgeo.getCountry( target );
      System.out.println( "Result = " + country );
      System.out.println();
   }

//---------------------------------------------------------------------------
//                  constructors and utility methods
//---------------------------------------------------------------------------

/**
  * Description: NetGeoClient constructor
  *
  * @param String - application name and version, e.g., "plot_AS_loc/1.2.3".
  * @return void
  * @author Jim Donohoe, CAIDA
  */
   public NetGeoClient( String applicationName )
   {
      this( applicationName, DEFAULT_SERVER_URL );
   }


/**
  * Description: NetGeoClient constructor
  *
  * @param
  * @return void
  * @author Jim Donohoe, CAIDA
  */
   public NetGeoClient()
   {
      // The user chose to not supply an application name and version,
      // so we invoke the regular constructor with an empty string.
      this( "", DEFAULT_SERVER_URL );
   }


/**
  * Description: NetGeoClient constructor
  *
  * @param String - application name and version, e.g., "plot_AS_loc/1.2.3".
  * @param String - url of NetGeo server, e.g.,
  * http://www.caida.org/cgi-bin/netgeo/netgeo.cgi
  * @return void
  * @author Jim Donohoe, CAIDA
  */
   public NetGeoClient( String applicationName, String netGeoUrl )
   {
      // Build up the user-agent name from the application name and version,
      // the NetGeoClient name and version, the Java version and vendor, and
      // the operating system.  This name will be used to track any
      // platform-specific problems.
      StringBuffer buffer = new StringBuffer();
      if( applicationName != null && applicationName.length() > 0 )
      {
         buffer.append( applicationName ).append( " " );
      }
      buffer.append( "NetGeoClient/" ).append( VERSION );
      String vendor = System.getProperty( "java.vendor" );

      // Some of the vendor strings can be really long so we truncate the
      // string to a reasonable (and arbitrary) length.
      int maxVendorLength = 8;
      if( vendor != null && vendor.length() > maxVendorLength )
      {
         vendor = vendor.substring( 0, maxVendorLength );
      }
      buffer.append( " " ).append( vendor );
      buffer.append( "/" ).append( System.getProperty( "java.version" ) );
      buffer.append( " " ).append( System.getProperty( "os.name" ) );

      // Store the user agent name into this NetGeoClient object so
      // it can be sent along with HTTP queries to the NetGeo server.
      _userAgent = buffer.toString();

      _netGeoUrl = netGeoUrl;

      // Default _nonblocking is false, i.e., the client is willing to wait
      // for whois lookups to return an answer.
      _nonblocking = false;
      _timeout = DEFAULT_TIMEOUT;

      // Initialize a new hashtable for the local cache. This implements the
      // default policy: a local cache will be used unless caching gets
      // turned off by useLocalCache( false ).
      _localCache = new Hashtable();
   }


/**
  * Description: Set the time limit (in seconds) to be allowed for a whois
  * lookup.  The default value is DEFAULT_TIMEOUT.  For the client, this
  * timeout is advisory only and may be overridden by the server.
  *
  * @param int - seconds
  * @return  void
  * @author Jim Donohoe, CAIDA
  */
   public void setTimeout( int seconds )
   {
      // Check the nonblocking flag, the timeout and nonblocking are mutually
      // exclusive.
      if( _nonblocking == true )
      {
         printWarning( "Setting NONBLOCKING to false. NONBLOCKING and " +
                       "TIMEOUT are mutually exclusive.", "setTimeout" );
         _nonblocking = false;
      }

      if( seconds > 0 )
      {
         _timeout = seconds;
      }
      else
      {
         printError( "Argument to setTimeout must be an integer > 0",
                     "setTimeout" );
      }
   }


/**
  * Description: Specify whether or not the server should wait on whois
  * lookups.  A value of true for nonblocking means that the server will not
  * wait on whois lookups, if the target is not found in the NetGeo database
  * the server will return immediately.  The default is nonblocking = false,
  * i.e., the server will wait on whois lookups if needed.
  *
  * @param boolean - trueOrFalse
  * @return void
  * @author Jim Donohoe, CAIDA
  */
   public void setNonblocking( boolean trueOrFalse )
   {
      _nonblocking = trueOrFalse;

      // Check the timeout value to see if it's been changed from the default,
      // the timeout and nonblocking are mutually exclusive.
      if( _nonblocking == true && _timeout != DEFAULT_TIMEOUT )
      {
         printWarning( "NONBLOCKING and TIMEOUT are mutually exclusive." +
                       "Queries will now be NONBLOCKING.", "setNonblocking" );
      }
   }


//---------------------------------------------------------------------------
//                          Local cache methods
//---------------------------------------------------------------------------

/**
  * Description: Keep a cache of results in memory on the machine executing
  * this NetGeoClient code.  Default is true, i.e., by default a local cache
  * will be used.  useLocalCache( false ) turns off the local caching.
  *
  * @param boolean - trueOrFalse
  * @return void
  * @author Jim Donohoe, CAIDA
  */
   public void useLocalCache( boolean trueOrFalse )
   {
      if( trueOrFalse == true )
      {
         // The user has requested we use a local cache. Check for an
         // existing local cache, make sure we don't clobber an existing
         // cache.  If there already is a local cache then do nothing.
         if( _localCache == null )
         {
            // Initialize the local cache with an empty hashtable.
            _localCache = new Hashtable();
         }
      }
      else
      {
         // Remove the local cache.
         _localCache = null;
      }
   }


/**
  * Description: Remove all entries from the local cache.
  *
  * @return void
  * @author Jim Donohoe, CAIDA
  */
   public void clearLocalCache()
   {
      if( _localCache != null )
      {
         // Empty the local cache.  This will remove all key/value pairs
         // from the hashtable.
         _localCache.clear();
      }
      else
      {
         printWarning( "Local cache not in use", "clearLocalCache" );
      }
   }


/**
  * Description: Remove the entry in the local cache with a key matching
  * the standardized form of the input target.  This method does nothing
  * if the specified target is not found in the cache.
  *
  * @param String - target
  * @return
  * @author Jim Donohoe, CAIDA
  */
   public void clearLocalCacheEntry( String target )
   {
      if( _localCache != null )
      {
         // Standardize the target string.  To use the verifyInputFormat
         // code we need to put the target into a hashtable.
         Hashtable hashtable = new Hashtable();
         hashtable.put( "TARGET", target );
         verifyInputFormat( "clearLocalCacheEntry", hashtable );

         // Get the standardized string from the table.
         target = (String) hashtable.get( "STANDARDIZED_TARGET" );

         if( target != null && _localCache.get( target ) != null )
         {
            // Remove the key/value pair with key matching the target from
            // the local cache.
            _localCache.remove( target );
         }
      }
      else
      {
         printWarning( "Local cache not in use", "clearLocalCacheEntry" );
      }
   }


//---------------------------------------------------------------------------
//                             scalar methods
//---------------------------------------------------------------------------

/**
  * Description: Returns a full record for the input target string.
  *
  * @param String
  * @return Hashtable
  * @author Jim Donohoe, CAIDA
  */
   public Hashtable getRecord( String target )
   {
      return execute( "getRecord", target );
   }

/**
  * Description: Returns the country corresponding to the input target string,
  * or returns NO_MATCH or NO_COUNTRY.
  *
  * @param String
  * @return String
  * @author Jim Donohoe, CAIDA
  */
   public String getCountry( String target )
   {
      Hashtable partialRecord = execute( "getCountry", target );

      return (String) partialRecord.get( "COUNTRY" );
   }

/**
  * Description: Returns a hashtable containing ( String, String ) pairs,
  * with the keys LAT, LONG, LAT_LONG_GRAN, and STATUS.
  *
  * @param String
  * @return Hashtable
  * @author Jim Donohoe, CAIDA
  */
   public Hashtable getLatLong( String target )
   {
      return execute( "getLatLong", target );
   }

//---------------------------------------------------------------------------
//                          getXxxArray methods
//---------------------------------------------------------------------------

/**
  * Description:
  *
  * @param String[]
  * @return Hashtable[]
  * @author Jim Donohoe, CAIDA
  */
   public Hashtable[] getRecordArray( String[] stringArray )
   {
      Hashtable[] hashArray = makeHashArray( "getRecordArray", stringArray );
      updateArray( "getRecordArray", hashArray );
      return hashArray;
   }


/**
  * Description:
  *
  * @param String[]
  * @return Hashtable[]
  * @author Jim Donohoe, CAIDA
  */
   public Hashtable[] getCountryArray( String[] stringArray )
   {
      Hashtable[] hashArray = makeHashArray( "getCountryArray", stringArray );
      updateArray( "getCountryArray", hashArray );
      return hashArray;
   }


/**
  * Description:
  *
  * @param String[]
  * @return Hashtable[]
  * @author Jim Donohoe, CAIDA
  */
   public Hashtable[] getLatLongArray( String[] stringArray )
   {
      Hashtable[] hashArray = makeHashArray( "getLatLongArray", stringArray );
      updateArray( "getLatLongArray", hashArray );
      return hashArray;
   }


//---------------------------------------------------------------------------
//                         updateXxxArray methods
//---------------------------------------------------------------------------

/**
  * Description:
  *
  * @param Hashtable[]
  * @return String
  * @author Jim Donohoe, CAIDA
  */
   public String updateRecordArray( Hashtable[] recordArray )
   {
      return updateArray( "getRecordArray", recordArray );
   }


/**
  * Description:
  *
  * @param Hashtable[]
  * @return String
  * @author Jim Donohoe, CAIDA
  */
   public String updateCountryArray( Hashtable[] recordArray )
   {
      return updateArray( "getCountryArray", recordArray );
   }


/**
  * Description:
  *
  * @param Hashtable[]
  * @return String
  * @author Jim Donohoe, CAIDA
  */
   public String updateLatLongArray( Hashtable[] recordArray )
   {
      return updateArray( "getLatLongArray", recordArray );
   }


//---------------------------------------------------------------------------
//                    public static utility methods
//---------------------------------------------------------------------------

/**
  * Description: Convert an int array to an equivalent String array.  This
  * can be used to convert an array of AS numbers represented as ints to
  * the type required for input into getRecordArray, getCountryArray, etc.
  *
  * @param int[]
  * @return String[]
  * @author Jim Donohoe, CAIDA
  */
   public static String[] intArrayToStringArray( int[] intArray )
   {
      String[] stringArray = null;
      if( intArray != null )
      {
         stringArray = new String[ intArray.length ];

         for( int i = 0; i < intArray.length; i++ )
         {
            stringArray[i] = Integer.toString( intArray[i] );
         }
      }
      return stringArray;
   }


/**
  * Description: Extract the latitude string from the lat/long hashtable
  * and return it as a float primitive.
  *
  * @param Hashtable
  * @return float
  * @author Jim Donohoe, CAIDA
  */
   public static float getLat( Hashtable latLongHash )
   {
      return string2float( latLongHash, "LAT" );
   }


/**
  * Description: Extract the longitude string from the lat/long hashtable
  * and return it as a float primitive.
  *
  * @param Hashtable
  * @return float
  * @author Jim Donohoe, CAIDA
  */
   public static float getLong( Hashtable latLongHash )
   {
      return string2float( latLongHash, "LONG" );
   }


/**
  * Description: Convert the Hashtable returned by getLatLong into a
  * 2-element float array.
  *
  * @param Hashtable - latLongHash
  * @return float[]
  * @author Jim Donohoe, CAIDA
  */
   public static float[] convertLatLong( Hashtable latLongHash )
   {
      float[] floatArray = new float[2];

      floatArray[LAT_INDEX] = string2float( latLongHash, "LAT" );
      floatArray[LONG_INDEX] = string2float( latLongHash, "LONG" );

      return floatArray;
   }


//===========================================================================
//                             PRIVATE METHODS
//===========================================================================

/**
  * Description: Method invoked from getXxx scalar methods.
  *
  * @param String - methodName
  * @param String - target
  * @return Hashtable
  * @author Jim Donohoe, CAIDA
  */
   private Hashtable execute( String methodName, String target )
   {
      if( DEBUG )
      {
         System.out.println( methodName + "( '" + target + "' )" );
      }

      // Create a single-element array, so the scalar method can be executed
      // by the same code as used for the array methods.
      Hashtable[] hashArray = new Hashtable[1];
      hashArray[0] = new Hashtable();
      hashArray[0].put( "TARGET", target );

      updateArray( methodName, hashArray );

      // Return the result as a single hashtable.
      return hashArray[0];
   }


/**
  * Description: Helper method invoked from updateXxxArray methods.
  *
  * @param String - methodName
  * @param Hashtable[] - targetArray
  * @param boolean - nonblocking
  * @return String
  * @author Jim Donohoe, CAIDA
  */
   private String updateArray( String methodName, Hashtable[] inputArray )
   {
      // Assume everthing will work correctly.  This will be returned to
      // invoker if the limit is not exceeded and there are no HTTP errors.
      String returnString = OK;

      // Test the target strings in the input array.  Any targets not in
      // an acceptable format will have their STATUS field set to INPUT_ERROR.
      // If we're using a local cache this method will also store the
      // standardized target into the hashtable, for use as a key in the cache
      // table.
      returnString = verifyInputFormatArray( methodName, inputArray );

      // Check for the string returned by verifyInputFormat when the array
      // length exceeds ARRAY_LENGTH_LIMIT.
      if( returnString.equals( INPUT_ERROR ) )
      {
         return returnString;
      }

      // Check to see if we're using a local cache.
      if( _localCache != null )
      {
         // We're using a local cache, first look for the entries in the
         // cache and update the unknown elements of the array using results
         // from the local cache.
         lookupLocalCacheArray( methodName, inputArray );
      }

      // Build a list of the target strings from the input array.  The list
      // will contain only those targets for which we don't have results,
      // i.e., if the value was already known or was found in the local cache,
      // the target doesn't get put on the list.  makeList returns an empty
      // string if there is nothing to look up.
      String targetList = makeList( methodName, inputArray );

      Hashtable[] resultArray = new Hashtable[0];
      if( targetList.length() > 0 )
      {
         // Build an HTTP request and send it to the NetGeo server, then
         // return the HTML text sent by the server.
         String text = executeHttpRequest( methodName, targetList );

         if( text.startsWith( HTTP_ERROR ) )
         {
            // Return the full error message generated by executeHttpRequest.
            return text;
         }

         // Check for the NETGEO_LIMIT_EXCEEDED message in the text.  There
         // may be some valid results in the text along with one or more
         // entries marked with STATUS = NETGEO_LIMIT_EXCEEDED.
         if( text.indexOf( NETGEO_LIMIT_EXCEEDED ) >= 0 )
         {
            // Return the limit exceeded message to the invoker, so it can
            // be passed on to the user.  In addition to this string, each
            // of the entries which exceeded the limit have their status
            // fields set to this same value.
            returnString = NETGEO_LIMIT_EXCEEDED;
         }

         // Convert the HTML text sent by the NetGeo server into an array of
         // hashtables.
         resultArray = convertToHashArray( text );
      }

      // Merge the results in the result array into the input array.
      mergeArrays( inputArray, resultArray );

      if( _localCache != null )
      {
         // Store the results into the local cache.  This will also remove
         // the standardized target strings from the hashtables in the array.
         storeLocalCacheArray( resultArray );
      }

      // Return the value that describes the overall status of this invocation.
      // This will be either OK or NETGEO_LIMIT_EXCEEDED.
      return returnString;
   }


/**
  * Description:
  *
  * @param String - methodName
  * @param String - targetString
  * @return String
  * @author Jim Donohoe, CAIDA
  */
   private String executeHttpRequest( String methodName,
                                      String targetString )
   {
      if( targetString == null || targetString.length() == 0 )
      {
         // The target string is empty, no need to query the server.
         // Return an empty string, it will get converted into an empty hash
         // by convertToHashArray.
         if( DEBUG )
         {
            System.out.println( "executeHttpRequest: Empty target" );
         }
         return "";
      }

      // If the method is one of the array methods, convert the method name
      // to the corresponding scalar method name, as expected by the server.
      int startOfArray = methodName.indexOf( "Array" );
      if( startOfArray > 0 )
      {
         if( methodName.startsWith( "update" ) )
         {
            // Convert the updateXxxArray method name to getXxx
            // updateRecordArray
            // 01234567890123456
            methodName = "get" + methodName.substring( 6, startOfArray );
         }
         else
         {
            // Convert the getXxxArray method name to getXxx
            methodName = methodName.substring( 0, startOfArray );
         }
      }

      // Form a URL with a GET query string.  The target string should be
      // URL-encoded in case the user put in any embedded blanks or strange
      // characters.  For any correct target string the URL-encoded string
      // should be the same as the original, except that commas between
      // targets get mapped to %2 in the encoded string, and a blank gets
      // mapped to +, as in "AS 1" -> "AS+1".
      String urlString = _netGeoUrl + "?method=" + methodName +
         "&target=" + URLEncoder.encode( targetString );

      // Add the nonblocking parameter or the timeout parameter, if either
      // one is specified.
      if( _nonblocking == true )
      {
         urlString += "&nonblocking=true";
      }
      else if( _timeout != DEFAULT_TIMEOUT )
      {
         urlString += "&timeout=" + _timeout;
      }

      if( DEBUG )
      {
         System.out.println( urlString );
      }

      URL url = null;
      try
      {
         url = new URL( urlString );
      }
      catch( MalformedURLException e )
      {
         printError( "MalformedURLException: " + urlString,
                     "executeHttpRequest" );
         e.printStackTrace();
      }

      String contentString = null;
      try
      {
         // openConnection will return an object of HttpURLConnection when
         // the URL is http.
         HttpURLConnection netGeoConnection =
            (HttpURLConnection) url.openConnection();

         // Set the value of the user agent in the header.  RFC 2616
         // (HTTP/1.1) section 14.43 says key should be "User-Agent".  The
         // value of _userAgent was set in the constructor.
         netGeoConnection.setRequestProperty( "User-Agent", _userAgent );

         // Attempt to connect to the NetGeo server URL
         netGeoConnection.connect();

         // Test the response code to see if the connection attempt was
         // successful.
         int responseCode = netGeoConnection.getResponseCode();
         if( responseCode == HttpURLConnection.HTTP_OK )
         {
            // The connection attempt was successful.  Get an input stream
            // from the connection and read the HTML text sent by the
            // NetGeo server.
            Object content = netGeoConnection.getContent();
            if( content == null )
            {
               printError( "getContent returned null", "executeHttpRequest" );

               // Set the content string to the empty string, not much more
               // we can do here.
               contentString = "";
            }
            else if( InputStream.class.isAssignableFrom( content.getClass() ))
            {
               // The class of the content object is a subclass of
               // InputStream, e.g., BufferedInputStream.
               InputStream contentStream = (InputStream) content;

               int bytesAvailable = contentStream.available();
               byte[] byteArray = new byte[ bytesAvailable ];

               // Read the bytes from the input stream and convert them into
               // a string using the standard encoding, then store the string
               // into contentString for return to the invoker.
               contentStream.read( byteArray, 0, bytesAvailable );
               contentString = new String( byteArray, 0, bytesAvailable );
            }
            else if( content instanceof String )
            {
               contentString = (String) content;
            }
            else
            {
               // In some error situations the content object may be an object
               // of type sun.net.www.content.text.PlainTextInputStream, which
               // is a hidden type.
               printError( "getContent returned an object of type " +
                           content.getClass().getName(),
                           "executeHttpRequest" );

               // Set the content string to the empty string, not much more
               // we can do here.
               contentString = "";
            }

            // Close the connection to the NetGeo server.
            netGeoConnection.disconnect();
         }
         else
         {
            // The connection attempt failed.  Return the HTTP error code and
            // error message, e.g., "HTTP_ERROR: 404 Not Found"
            contentString = HTTP_ERROR + responseCode  + " " +
               netGeoConnection.getResponseMessage();
         }
      }
      catch( IOException e )
      {
         e.printStackTrace();
      }

      if( DEBUG )
      {
         System.out.println( "Text from NetGeo server:\n" + contentString +
                             "\n----END OF TEXT----" );
      }

      if( contentString != null )
      {
         // Trim the string.  This is only needed if the string is blank, so
         // that the length==0 test in convertToHashArray works properly.
         contentString = contentString.trim();
      }
      return contentString;
   }


/**
  * Description: Merge the result array into the input array.
  *
  * @param Hashtable[] - inputArray
  * @param Hashtable[] - resultArray
  * @return void
  * @author Jim Donohoe, CAIDA
  */
   private void mergeArrays( Hashtable[] inputArray, Hashtable[] resultArray )
   {
      // Merge the new results into the existing record array.  The number
      // of entries in the two arrays may be different, but the relative
      // order of elements will be the same.
      // Example: Assume that records A, D, and E have already been found
      // in an earlier lookup, so the identifer list would contain B, C,
      // and F.  After the current lookup the result array will contain
      // hashes for B, C, and F.
      //
      //                  $inputIndex
      //                       |
      //                       V
      //                 +---+---+---+---+---+---+
      //  inputArray ->  | A | B | C | D | E | F |
      //                 +---+---+---+---+---+---+
      //
      //              resultIndex
      //                   |
      //                   V
      //                 +---+---+---+
      //  resultArray -> | B | C | F |
      //                 +---+---+---+
      //
      // The strings in the TARGET field of the result array are really the
      // standardized targets that were sent to the server.

      int inputIndex = 0;

      for( int resultIndex = 0; resultIndex < resultArray.length;
           resultIndex++ )
      {
         // Extract the target string from the current result hash.  The
         // string stored here in the TARGET field is really the standardized
         // target that was sent to the server, not necessarily the same as
         // the original target in the TARGET field of the input array.
         String resultTarget =
            (String) resultArray[resultIndex].get( "TARGET" );

         // Make sure there really is a target to search for.
         if( resultTarget == null || resultTarget.equals( "" ) )
         {
            printWarning( "Null or empty target string in result array at " +
                          "index = " + resultIndex, "mergeArrays" );
            continue;
         }

	      // Find the matching target in the input array, starting from the
	      // current input index.  Note that we compare the resultTarget
         // (which is standardized) against the value from the STANDARDIZED
         // target field of the input array.
         String standardizedTarget =
            (String) inputArray[inputIndex].get( "STANDARDIZED_TARGET" );
         boolean matchedResultTarget = false;

         while( inputIndex < inputArray.length )
         {
            if( standardizedTarget != null &&
                resultTarget.equals( standardizedTarget ) )
            {
               // We found the standardizedInputTarget matching the result.
               matchedResultTarget = true;

               // Get the original target from the input array, so we can
               // copy it into the result array.  At this point the original
               // target might be "caida.org" and the standardized target
               // and result target would both be "CAIDA.ORG".
               String originalTarget =
                  (String) inputArray[inputIndex].get( "TARGET" );

               // Replace the hash in the input array with the hash returned
               // from the lookup.
               inputArray[inputIndex] = resultArray[resultIndex];

               // If the standardized target is different from the original
               // then we need to fix up the fields in the hashtable.
               if( ! originalTarget.equals( standardizedTarget ) )
               {
                  // Fix up the TARGET field in the result array.  The user
                  // is expecting to find the original target in the TARGET
                  // field.
                  inputArray[inputIndex].put( "TARGET", originalTarget );
               }

               if( _localCache != null )
               {
                  // Store the standardized value into the STANDARDIZED field
                  // in the result hashtable, so it can be stored into the
                  // local cache correctly.  This is adding a field to the
                  // hashtable at resultArray[resultIndex], which is also
                  // referenced by inputArray[inputIndex].
                  resultArray[resultIndex].put( "STANDARDIZED_TARGET",
                                                standardizedTarget );
               }

               // Advance to the next slot in the input array, no need to test
               // the current slot again.  The standardized target will be
               // extracted from the new slot prior to the start of the while
               // loop.
               inputIndex += 1;
               break;
            }
            else
            {
               // The current element in the input array didn't match the
               // result, so advance to the next element in the input array.
               inputIndex += 1;
               standardizedTarget =
                  (String)inputArray[inputIndex].get( "STANDARDIZED_TARGET" );
            }
         } // End while

         if( matchedResultTarget == false )
         {
            // Somehow we failed to find a matching target in the input array.
            printError( "Can't find '" + resultTarget + "' in input array",
                        "mergeArrays" );
         }
      } // End for
   }

//---------------------------------------------------------------------------
//                     local cache helper methods
//---------------------------------------------------------------------------

/**
  * Description: Method to perform the local cache lookups on all the targets
  * in an array which need to be looked up.  This method should only be
  * invoked AFTER the target strings have been verified and standardized.
  *
  * @param String - methodName
  * @param Hashtable[] - hashArray, must be non-null
  * @return void
  * @author Jim Donohoe, CAIDA
  */
   private void lookupLocalCacheArray( String methodName,
                                       Hashtable[] hashArray )
   {
      if( hashArray == null )
      {
         printError( "Null input array", methodName );
         return;
      }

      // For each element in the hash array, check the status then do a
      // cache lookup if needed.
      for( int i = 0; i < hashArray.length; i++ )
      {
         Hashtable hash = hashArray[i];
         if( hash != null )
         {
            // Get the standardized form of the target string from the hash.
            String standardizedTarget =
               (String) hash.get( "STANDARDIZED_TARGET" );

            if( standardizedTarget != null )
            {
               // Lookup the target in the local cache.  This will return null
               // if there is no match, otherwise returns a hash.
               Hashtable cachedValue = lookupLocalCache( methodName,
                                                         standardizedTarget );

               // If we found a match in the cache, store the cached value
               // into the current slot in the array.
               if( cachedValue != null )
               {
                  hashArray[i] = cachedValue;
               }
            }
            else
            {
               printError( "Bad element " + i + " in argument to " +
                           "_lookupLocalCacheArray, missing " +
                           "STANDARDIZED_TARGET field", methodName );
               continue;
            }
         } // End if( hash != null )
      }//  End for( int i = 0; ...
   }


/**
  * Description: The value stored with the LOCAL_CACHE key is a ref to a hash
  * with IP addrs, AS numbers, or domain names for keys, and with hashes as
  * values.
  *
  * Example:  Assume we've already done the lookups getRecord( "caida.org" ),
  * getLatLong( "192.172.226.0" ), and getCountry( "AS 1234" ).  Then the
  * cache would look like:
  * { "CAIDA.ORG"      => { full hash record },
  *   "192.172.226.0"  => { lat/long hash record },
  *   "1234"           => { country hash record } }
  * The lookup targets (in standard form) are used as keys in the local cache,
  * and the values are hash refs.  This method automatically converts the
  * cached hash record to the requested format, when possible, otherwise
  * returns null to force a new lookup.
  *
  * @param String - methodName
  * @param String - target
  * @return void
  * @author Jim Donohoe, CAIDA
  */
   private Hashtable lookupLocalCache( String methodName,
                                       String standardizedTarget )
   {
      Hashtable cachedValue = (Hashtable) _localCache.get( standardizedTarget );
      if( cachedValue != null )
      {
         if( methodName.startsWith( "getRecord" ) )
         {
            // Test the hash to see if it contains a LOOKUP_TYPE key/value.
            // Full records always contain a LOOKUP_TYPE key/value pair with
            // a defined value, lat/long hashes never do.
            if( cachedValue.get( "LOOKUP_TYPE" ) == null )
            {
               // The current request is getRecord but the cached value is NOT
               // a full record, so it doesn't satisfy the current request.
               // Set cachedValue to null to force a new lookup.
               cachedValue = null;
            }

            // Otherwise, cachedValue is a ref to a full record, so it
            // matches the type expected by the current request.
         }
         else if( methodName.startsWith( "getLatLong" ) )
         {
            // Test the hash to see if it contains a LOOKUP_TYPE key/value.
            // Full records always contain a LOOKUP_TYPE key/value pair with
            // a non-null value, lat/long hashes never do.
            if( cachedValue.get( "LOOKUP_TYPE" ) != null )
            {
               // The cached value is a full record, but the current request
               // is only for a lat/long hash.  Extract the needed values
               // from the full record and return a lat/long hash.  This
               // doesn't change the value stored in the cache, cache keeps
               // the full record.
               Hashtable latLongHash = new Hashtable();
               latLongHash.put( "TARGET", cachedValue.get( "TARGET" ) );
               latLongHash.put( "LAT", cachedValue.get( "LAT" ) );
               latLongHash.put( "LONG", cachedValue.get( "LONG" ) );
               latLongHash.put( "LAT_LONG_GRAN",
                                cachedValue.get( "LAT_LONG_GRAN" ) );
               latLongHash.put( "STATUS", cachedValue.get( "STATUS" ) );

               // Copy the ref into the cachedValue ref for return to invoker.
               cachedValue = latLongHash;
            }
            else if( cachedValue.get( "LAT" ) == null )
            {
               // The cached value must be a country hash, so set cachedValue
               // to null to force a new lookup.
               cachedValue = null;
            }

            // Otherwise, cachedValue is a ref to a lat/long hash, so it
            // matches the type expected by the current request.
         }
         else if( methodName.startsWith( "getCountry" ) )
         {
            // Test the hash to see if it contains a COUNTRY key/value, to see
            // if this is a lat/long record.
            if( cachedValue.get( "COUNTRY" ) == null )
            {
               // The country is null so the cached value must be a lat/long
               // hash, set $cachedValue to undef to force a new lookup.
               cachedValue = null;
            }
         }
      }
      // Return the value found in the cache.  This will be undef if no there
      // was no key matching the target or if the result type was wrong so we
      // need to force a new lookup on the server.
      return cachedValue;
   }


/**
  * Description:
  *
  * @param
  * @return void
  * @author Jim Donohoe, CAIDA
  */
   private void storeLocalCacheArray( Hashtable[] hashArray )
   {
      if( hashArray == null )
      {
         printError( "Null input array", "storeLocalCacheArray" );
         return;
      }

      // Store each element in the input array into the local cache.
      for( int i = 0; i < hashArray.length; i++ )
      {
         storeLocalCache( hashArray[i] );
      }
   }


/**
  * Description:
  *
  * @param
  * @return void
  * @author Jim Donohoe, CAIDA
  */
   private void storeLocalCache( Hashtable result )
   {
      if( result != null )
      {
         String standardizedTarget =
            (String) result.get( "STANDARDIZED_TARGET" );
         String status = (String) result.get( "STATUS" );
         if( standardizedTarget != null && status != null &&
             ( status.equals( OK ) ||
               status.equals( NO_MATCH ) ||
               status.equals( NO_COUNTRY ) ) )
         {
            _localCache.put( standardizedTarget, result );
         }

         // We're done using the standardized name for now, so remove it
         // from the hashtable.  The hashtable will still contain the
         // original target in the TARGET field.
         result.remove( "STANDARDIZED_TARGET" );
      }
   }



//---------------------------------------------------------------------------
//                      input verification methods
//---------------------------------------------------------------------------

/**
  * Description: Verify the target strings in an array of hashtables.  If
  * any null or invalid format strings are found the STATUS field in the
  * hashtable will be set to INPUT_ERROR.  If we're using local caching
  * the standardized target string will be stored into the hashtable in
  * the CACHE_TARGET field.
  *
  * @param String - methodName
  * @param Hashtable[] - inputArray
  * @return String
  * @author Jim Donohoe, CAIDA
  */
   private String verifyInputFormatArray( String methodName,
                                          Hashtable[] inputArray )
   {
      if( inputArray == null )
      {
         printError( "Null input array", methodName );
         return INPUT_ERROR;
      }
      else if( inputArray.length > ARRAY_LENGTH_LIMIT )
      {
         printError( "Input array exceeds length limit", methodName );
         return INPUT_ERROR;
      }
      for( int i = 0; i < inputArray.length; i++ )
      {
         Hashtable hash = inputArray[i];
         if( hash == null )
         {
            continue;
         }
         // Check the status, to see if this element has already been found
         // in a previous lookup.  We don't waste time verifying the target
         // string if we don't need to look it up.
         String status = (String) hash.get( "STATUS" );

         // Status is null for hashtables created in makeHashArray, from
         // calls to getXxxArray.  Otherwise, there could be some existing
         // status value from a previous lookup.  If the status is not one
         // of the 'defintitive' values, we need to look up the target.
         if( status == null || ( status != OK && status != NO_MATCH &&
             status != NO_COUNTRY ) )
         {
            // Test the target string and store the standardized target
            // string into the hashtable.
            verifyInputFormat( methodName, hash );
         }
      } // End for( int i = 0; ...
      return OK;
   }


/**
  * Description: Test the target string from the input hashtable and make
  * sure it's in an acceptable format.  The input can be an AS number string
  * (with or without a leading "AS"), an IP address in dotted decimal format,
  * or a domain name.  Stores the standardized target into the hashtable if
  * input is in an acceptable format, otherwise stores null into table.
  *
  * @param String - methodName
  * @param Hashtable - hashtable
  * @return void
  * @author Jim Donohoe, CAIDA
  */
   public void verifyInputFormat( String methodName, Hashtable hashtable )
   {
      String target = (String) hashtable.get( "TARGET" );
      String standardizedTarget = null;
      if( target != null )
      {
         target = target.trim();
         int lastDotIndex = target.lastIndexOf( '.' );

         // Check for dots separating IP address octets or domain name parts.
         if( lastDotIndex > 0 )
         {
            // The target string contains at least one dot, so it might be a
            // domain name or an IP address.
            if( verifyDomainNameFormat( target, lastDotIndex ) )
            {
               // The target is in a valid domain name format, standardize the
               // target string to all uppercase.
               standardizedTarget = target.toUpperCase();
            }
            else if( verifyIpAddressFormat( target, lastDotIndex ) )
            {
               // The target is in a valid IP address format.  This is a
               // dotted decimal number so no standardization is needed.
               standardizedTarget = target;
            }
            else
            {
               // Add the INPUT_ERROR key to the hashtable.
               hashtable.put( INPUT_ERROR, INPUT_ERROR );
               printError( "Bad target '" + target + "'", methodName );
            }
         }
         else
         {
            // The target doesn't contain any dots so it must be an AS number
            // string or invalid.  The string returned by this method will
            // have the leading "AS" removed, i.e., it will be in standard
            // form for an AS number.
            standardizedTarget = verifyAsNumberFormat( methodName, target );

            if( standardizedTarget == null )
            {
               // Add the INPUT_ERROR key to the hashtable.
               hashtable.put( INPUT_ERROR, INPUT_ERROR );
               printError( "Bad target '" + target + "'", methodName );
            }
         }
      }
      // Store the standardized target string into the hash table. It will
      // be used for comparison to the results returned from the server and
      // as the key to the cache table.  This string will be null if the input
      // target was not in a valid format.

      if (standardizedTarget!=null)
        hashtable.put( "STANDARDIZED_TARGET", standardizedTarget );
   }


/**
  * Description:
  *
  * @param String - methodName
  * @param String - input, already trimmed and not null
  * @return String
  * @author Jim Donohoe, CAIDA
  */
   public String verifyAsNumberFormat( String methodName, String input )
   {
      String target = null;
      int index = 0;
      if( input.startsWith( "AS" ) || input.startsWith( "as" ) )
      {
         // Advance the index to the 'S' position
         index = 2;

         // Advance past any blanks between the "AS" and the numerals.
         while( index < input.length() && input.charAt( index ) == ' ' )
         {
            index++;
         }
      }

      // Chop off the leading "AS"
      if( index > 0 )
      {
         input = input.substring( index );
      }

      // Try converting to an int, to make sure this is a valid numeral.
      try
      {
         int targetInt = Integer.parseInt( input );

         // Make sure the number is in the range of possible values for an
         // AS number, 1 to 64K.
         if( targetInt >= 1 && targetInt < 65536 )
         {
            // The number is valid, copy the reference to the input string
            // to the return variable.
            target = input;
         }
         else
         {
            printError( "Bad input '" + input + "', AS number must be " +
                        "between 1 and 65536", methodName );
         }
      }
      catch( NumberFormatException e )
      {
         printError( "Bad input '" + input + "' for AS number", methodName );
      }
      return target;
   }


/**
  * Description:
  *
  * @param String - input, already trimmed and not null
  * @param int - lastDotIndex
  * @return String
  * @author Jim Donohoe, CAIDA
  */
   public boolean verifyDomainNameFormat( String input, int lastDotIndex )
   {
      boolean isValidFormat = false;

      // The input contains at least one dot so it could be a domain name or
      // an IP address.  Next, extract the substring following the last dot
      // so it can be tested.
      String tldString = "";
      int tldStartIndex = lastDotIndex + 1;
      if( tldStartIndex < input.length() )
      {
         tldString = input.substring( tldStartIndex );
      }

      tldString = tldString.toLowerCase();
      boolean tldIsValidFormat = false;
      int tldLength = tldString.length();

      if( tldLength == 2 && Character.isLetter( tldString.charAt( 0 ) ) &&
          Character.isLetter( tldString.charAt( 1 ) ) )
      {
         // tld string appears to be the correct format for a 2-letter TLD
         tldIsValidFormat = true;
      }
      else if( tldLength == 3 &&
               ( tldString.equals( "com" ) || tldString.equals( "net" ) ||
                 tldString.equals( "org" ) || tldString.equals( "edu" ) ||
                 tldString.equals( "gov" ) || tldString.equals( "mil" ) ||
                 tldString.equals( "int" ) ) )
      {
         tldIsValidFormat = true;
      }

      if( tldIsValidFormat )
      {
         // Make sure all other characters up to the last dot are acceptable.
         // The first letter cannot be a dot.
         isValidFormat = true;
         char c = input.charAt( 0 );
         if( ! ( Character.isLetterOrDigit( c ) || c == '_' || c == '-' ) )
         {
            isValidFormat = false;
         }
         for( int i = 1; i < lastDotIndex; i++ )
         {
            // A domain name can consist of letters, digits, underscores,
            // dashes, or dots.
            c = input.charAt( i );
            if( ! ( Character.isLetterOrDigit( c ) ||
                    c == '.' || c == '_' || c == '-' ) )
            {
               isValidFormat = false;
            }
         }
      }
      return isValidFormat;
   }


/**
  * Description:
  *
  * @param String - input, already trimmed and not null
  * @param int - lastDotIndex
  * @param String - lastOctet, the part of the input string following
  * the last dot.  In an IP address this is the 4th octet.
  * @return String
  * @author Jim Donohoe, CAIDA
  */
   public boolean verifyIpAddressFormat( String input, int lastDotIndex )
   {
      boolean isValidFormat = true;
      int octetCount = 0;

      // This method gets invoked AFTER the test for a domain name, so we
      // can just look for digits and dots in a valid name.  Start from the
      // last octet since we already know the index of the last dot.

      String octetString = input.substring( lastDotIndex+1, input.length() );

      // Keep testing octets until we have tested all four or until we run
      // out of octets.
      while( octetCount < 4 )
      {
         octetCount += 1;

         // Try converting to an int, to make sure this is a valid octet.
         try
         {
            int octetInt = Integer.parseInt( octetString );

            // Make sure the number is in the range of possible values for
            // an octet, 0 to 255 (inclusive).
            if( octetInt < 0 || octetInt > 255 )
            {
               // The octet value is out of range.
               isValidFormat = false;
               break;
            }
         }
         catch( NumberFormatException e )
         {
            // The input string has a bad octet, so it is not in a valid
            // format for an IP address, break out of the while loop.
            isValidFormat = false;
            break;
         }

         if( octetCount < 3 )
         {
            // Get the next previous octet.
            int prevDotIndex = input.lastIndexOf( '.', lastDotIndex-1 );
            if( prevDotIndex > 0 )
            {
               octetString = input.substring( prevDotIndex+1, lastDotIndex );
               lastDotIndex = prevDotIndex;
            }
            else
            {
               // We expected to find another dot but didn't find one.
               // This is not a valid format for an IP address, so break
               // out of the while loop.
               isValidFormat = false;
               break;
            }
         }
         else if( octetCount == 3 )
         {
            // The octet count is 3, so we don't expect to find another
            // dot, we should have a single octet remaining.  Assign all
            // unused characters at the front of the string to the first
            // octet.
            octetString = input.substring( 0, lastDotIndex );
         }
      }
      return isValidFormat;
   }

//---------------------------------------------------------------------------
//                     other private helper methods
//---------------------------------------------------------------------------

/**
  * Description:
  *
  * @param String - methodName
  * @param String[] - stringArray
  * @return Hashtable[]
  * @author Jim Donohoe, CAIDA
  */
   private Hashtable[] makeHashArray( String methodName, String[] stringArray )
   {
      Vector hashVector = new Vector();

      if( stringArray != null )
      {
         for( int i = 0; i < stringArray.length; i++ )
         {
            String target = stringArray[i];
            if( target != null )
            {
               // Create a new hashtable and store it into the vector.
               Hashtable hash = new Hashtable();

               // Store the original target into the hashtable.
               hash.put( "TARGET", target );

               hashVector.addElement( hash );
            }
            else
            {
               printWarning( "Null input array element " + i, methodName );
            }
         }
      }
      else
      {
         printWarning( "Null input array", methodName );
      }

      // Convert the vector of hashtables into an array of hashtables.
      Hashtable[] hashArray = new Hashtable[ hashVector.size() ];
      hashVector.copyInto( hashArray );

      return hashArray;
   }


/**
  * Description: Parse the block of text output from netgeo.cgi, such as:
  * <!DOCTYPE HTML PUBLIC "-//IETF//DTD HTML//EN">
  * <HTML><HEAD><TITLE>NetGeo Results</TITLE>
  * </HEAD><BODY>
  * TARGET:        caida.org<br>
  * COUNTRY:       US<br>
  * STATUS:        OK<br>
  * </BODY></HTML>
  *
  * @param String
  * @return Hashtable[]
  * @author Jim Donohoe, CAIDA
  */
   private Hashtable[] convertToHashArray( String text )
   {
      // First, check for an empty string.
      if( text == null || text.length() == 0 )
      {
         // Make a 1-element array for returning the error message to the
         // original invoker.
         Hashtable[] errorMessageArray = new Hashtable[1];
         errorMessageArray[0] = new Hashtable();

         // Use the HTTP_ERROR constant as the key so it can be easily tested.
         errorMessageArray[0].put( HTTP_ERROR, "Empty content string" );

         return errorMessageArray;
      }

      // Process the non-error text, storing each block of key/value pairs
      // into a hashtable and storing the hashtables into a vector.

      Vector hashVector = new Vector();
      Hashtable hashtable = null;

      StringTokenizer tokenizer = new StringTokenizer( text, "\n" );
      while( tokenizer.hasMoreTokens() )
      {
         String line = tokenizer.nextToken();

         // All key/value lines will be like: "TARGET:        caida.org<br>"
         // with a key in all uppercase followed by :, then at least one
         // space, then the value and the <br>.
         String key = null;
         String value = null;
         int colonIndex = line.indexOf( ':' );
         if( colonIndex > 0 )
         {
            // Get the key string from the start of the line.
            key = line.substring( 0, colonIndex );

            // Find the index of the HTML tag at the end of the line.  The
            // tag might be missing if the text didn't all get delivered to
            // the client before this method was invoked.
            int tagIndex = line.indexOf( "<br>" );
            if( tagIndex > 0 )
            {
               value = line.substring( colonIndex+1, tagIndex ).trim();
            }
         }

         // Check to see if we found a key/value pair.  The value string might
         // be the empty string but shouldn't be null if key is not null,
         // except when the text gets broken in the middle of a line as
         // happens occasionally for unknown reasons.  NOTE: a record gets
         // stored into the vector only when we find a complete STATUS line,
         // so if only part of a record gets delivered by the server we will
         // skip it.
         if( key != null && value != null )
         {
            if( key.equals( "TARGET" ) )
            {
               // This is the start of a new block, start a new hashtable.
               // NOTE: the server sends back the STANDARDIZED target in the
               // TARGET field.  In updateArray the original target will be
               // used in the TARGET field.
               hashtable = new Hashtable();
               hashtable.put( "TARGET", value );
            }
            else if( key.equals( "STATUS" ) )
            {
               // This is the end of a block, add the STATUS key/value pair
               // then store the current hashtable into the vector.
               if( hashtable != null )
               {
                  hashtable.put( "STATUS", value );
                  hashVector.addElement( hashtable );
                  hashtable = null;
               }
            }
            else
            {
               // Add the key/value pair to the current hashtable.
               if( hashtable != null )
               {
                  hashtable.put( key, value );
               }
            }
         }
      }

      // Convert the vector of hashtables into an array of hashtables.
      Hashtable[] hashArray = new Hashtable[ hashVector.size() ];
      hashVector.copyInto( hashArray );

      return hashArray;
   }


/**
  * Description: Make a comma-separated list of the STANDARDIZED targets for
  * sending to the NetGeo server.  NOTE: This method must be called after
  * verifyInputFormat.
  *
  * @param Hashtable[]
  * @return String
  * @author Jim Donohoe, CAIDA
  */
   private String makeList(  String methodName, Hashtable[] hashArray )
   {
      StringBuffer list = new StringBuffer();

      if( hashArray == null || hashArray.length == 0 )
      {
         // methodName is the name of the scalar method.  Convert it to the
         // name of an updateXxxArray method to report the error, e.g.,
         // "ERROR empty argument array for updateRecordArray".
         printError( "Empty target array for " + "update" +
                     methodName.substring( 3 ) + "Array", "makeList" );
      }
      else
      {
         // The input target array is not empty.  Loop through the array,
         // adding target strings to the string buffer.
         for( int i = 0; i < hashArray.length; i++ )
         {
            if( hashArray[i] != null )
            {
               // Check the status to see if this record has already been
               // successfully looked up.  The user may be polling the server
               // asking for updates of the records which haven't been found
               // yet.
               String status = (String) hashArray[i].get( "STATUS" );

               // status will be null when it hasn't been set yet, e.g., as
               // when an updateXxxArray method gets called for the first time
               // with hashes containing only target strings.  status will be
               // LOOKUP_IN_PROGRESS if a previous lookup failed to find the
               // target in the database and the whois lookup is proceeding.
               // If the status string is something else it means the target
               // has already been found or the whois lookups failed, so there
               // is no need to look it up again.
               if( status == null ||
                   status.equals( LOOKUP_IN_PROGRESS ) ||
                   status.equals( WHOIS_TIMEOUT ) ||
                   status.equals( NETGEO_LIMIT_EXCEEDED ) )
               {
                  // Check for an INPUT_ERROR entry in the hashtable. There
                  // will be a non-null entry if the target failed the
                  // verification tests.
                  String error = (String) hashArray[i].get( INPUT_ERROR );
                  if( error == null )
                  {
                     // Get the STANDARDIZED target string from the hashtable.
                     String standardizedTarget =
                        (String) hashArray[i].get( "STANDARDIZED_TARGET" );

                     if( standardizedTarget == null ||
                         standardizedTarget.length() == 0 )
                     {
                        printError( "Empty target at index = " + i,
                                    methodName );
                        continue;
                     }

                     // Add the standardized target to the list.
                     if( i > 0 )
                     {
                        list.append( "," );
                     }
                     list.append( standardizedTarget );
                  } // End if( error == null )
               }
            }
            else
            {
               printError( "Empty hashtable at index = " + i, methodName );
            }
         }
      }
      return list.toString();
   }


/**
  * Description: Helper method for getLat, getLong, convertLatLong. Extract
  * the latitude string from the hashtable and return it as a float.
  *
  * @param Hashtable - latLongHash
  * @param String - key
  * @return float
  * @author Jim Donohoe, CAIDA
  */
   private static float string2float( Hashtable latLongHash, String key )
   {
      float floatValue = (float) 0.0;
      if( latLongHash != null )
      {
         String stringValue = (String) latLongHash.get( key );
         try
         {
            floatValue = new Float( stringValue ).floatValue();
         }
         catch( NumberFormatException e )
         {
            printError( "NumberFormatException: string = '" +
                        stringValue + "'", "string2float" );
            e.printStackTrace();
         }
      }
      else
      {
         printError( "Null hashtable input", "string2float" );
      }
      return floatValue;
   }


//---------------------------------------------------------------------------
//                      debug and error methods
//---------------------------------------------------------------------------

/**
  * Description:
  *
  * @param String - message
  * @param String - methodName
  * @return void
  * @author Jim Donohoe, CAIDA
  */
   private static void printError( String message, String methodName )
   {
      System.err.println( "ERROR in NetGeoClient:" + methodName + ", " +
                          message + ".\n" );
   }


/**
  * Description:
  *
  * @param String - message
  * @param String - methodName
  * @return void
  * @author Jim Donohoe, CAIDA
  */
   private static void printWarning( String message, String methodName )
   {
      System.err.println( "WARNING in NetGeoClient:" + methodName + ", " +
                          message + ".\n" );
   }
}
