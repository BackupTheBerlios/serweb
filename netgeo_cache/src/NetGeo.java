
/**
 * <p>Title: </p>
 * <p>Description: </p>
 * <p>Copyright: Copyright (c) 2002</p>
 * <p>Company: </p>
 * @author unascribed
 * @version 1.0
 */

import java.util.*;
import java.net.*;

public class NetGeo {

  private float lat=0, lon=0;
  private String country, city;

  private NetGeoClient netgeo = new NetGeoClient();

  public static boolean DEBUG = false;

  public NetGeo(String domainname) {
    Hashtable record;

    record = netgeo.getRecord(domainname);

    if( record.get("HTTP_ERROR") != null ) {
      System.out.println( record.get("HTTP_ERROR") );
      return;
    }

    country=(String)record.get("COUNTRY");
    city=(String)record.get("CITY");

    lon=get_float_from_hash(record, "LONG");
    lat=get_float_from_hash(record, "LAT");


    if (lon!=0 || lat!=0) { //it's OK we have long and lat
      if (DEBUG){
        System.out.println("   lat/long: "+lat+"/"+lon);
      }
      return;
    }

    //lon==0 and lat==0 - try request again with IP address instead of domain name

    if (DEBUG){
      System.out.println("   not found for domainname, trying for IP");
    }

    InetAddress [] inaddr;
    try{
      inaddr=InetAddress.getAllByName(domainname);
    }
    catch (UnknownHostException e){
      System.err.println("ERROR in LatLong, can't find IP address for host '"+domainname+"'");
      return;
    }

    for (int i=0; i < inaddr.length; i++){
      record = netgeo.getRecord(inaddr[i].getHostAddress());

      if( record.get("HTTP_ERROR") != null ) {
        System.out.println( record.get("HTTP_ERROR") );
        return;
      }

      country=(String)record.get("COUNTRY");
      city=(String)record.get("CITY");

      lon=get_float_from_hash(record, "LONG");
      lat=get_float_from_hash(record, "LAT");

      if (DEBUG){
        System.out.println("   IP: "+inaddr[i].getHostAddress()+" lat/long: "+lat+"/"+lon);
      }

      if (lon!=0 || lat!=0) return; //it's OK we have long and lat
    }
  }

  public float get_lon(){
    return lon;
  }

  public float get_lat(){
    return lat;
  }

  public String get_city(){
    return city;
  }

  public String get_country(){
    return country;
  }

  private float get_float_from_hash (Hashtable hash, String what){
    float x;

    try{
      x=Float.parseFloat((String)hash.get(what));
    }
    catch(NumberFormatException e){
      x=0;
    }
    catch(NullPointerException e){
      x=0;
    }
    return x;
  }

}