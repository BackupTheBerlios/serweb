
/**
 * <p>Title: </p>
 * <p>Description: </p>
 * <p>Copyright: Copyright (c) 2002</p>
 * <p>Company: </p>
 * @author Karel Kozlik
 * @version 1.0
 */

import java.io.*;
import java.util.*;

public class Icao {
  File stationListFile;
  public static boolean DEBUG = false;

  public Icao(File stationListFile) {
    this.stationListFile=stationListFile;
  }

  /** find icao code of nearest station for given long/lat*/
  public String get_icao (float g_lon, float g_lat) throws IcaoNotFoundException{
    BufferedReader fr;
    StringTokenizer tokenizer;
    String icao, lon, lat;
    String line;
    String [] lli;
    String ret_icao="";
    float dist, min_dist;

    min_dist=Float.MAX_VALUE;

    try{
      fr=new BufferedReader(new FileReader (stationListFile));
    }
    catch (FileNotFoundException e){
      System.err.println("ERROR: Icao:get_icao - file not found");
      throw new IcaoNotFoundException();
    }

    try{
      while ((line=fr.readLine())!=null){

        try{
          lli=parse_icao_long_lat_from_line(line);

          icao=lli[0];
          lat=lli[1];
          lon=lli[2];

          dist=distance_2D(g_lon, g_lat, long_to_float(lon), lat_to_float(lat));

          if (dist < min_dist) {
            min_dist=dist;
            ret_icao=icao;
          }

        }
        catch (NoSuchElementException e){
          if (DEBUG) {
            System.out.println("not enought fields at line "+line+" in station list file");
          }
          continue;
        }
        catch (NumberFormatException e){
          continue;
        }

      }//while

      fr.close();
    }
    catch (IOException e){
      System.err.println("ERROR: Icao:get_icao - can't read from station list file");
      throw new IcaoNotFoundException();
    }

    if (ret_icao.compareTo("")==0) throw new IcaoNotFoundException();

    return ret_icao;

  }//end of get_icao



  /* convert longitude string to float
   *
   * DDD-MM-SSH where DDD is degrees, MM is minutes, SS is seconds and H is E
   * for eastern hemisphere or W for western hemisphere. The seconds value is
   * omitted for those stations where The seconds value is unknown
   */

  private float long_to_float(String lon) throws NumberFormatException{
    float flon;
    String pom;
    int divider=60;
    int multiply=1;

    try{
      StringTokenizer token=new StringTokenizer(lon, "-");

      flon=Float.parseFloat(token.nextToken()); //get degrees
      pom=token.nextToken(); //get minutes

      if (token.hasMoreTokens()){ //is there seconds?
                                //yes
        flon+=Float.parseFloat(pom)/divider;
        divider*=60;
        pom=token.nextToken(); //get seconds
      }

      if (pom.length()>2)
        if (pom.charAt(2)=='W' || pom.charAt(2)=='w') multiply=-1;

      flon+=Float.parseFloat(pom.substring(0,2))/divider;

      flon*=multiply;             //east or west
    }
    catch (NoSuchElementException e){
      if (DEBUG) {
        System.out.println("wrong longitude wormat '"+lon+"'");
      }
      throw new NumberFormatException();
    }
    catch (NumberFormatException e){
      if (DEBUG) {
        System.out.println("wrong longitude wormat '"+lon+"'");
      }
      throw new NumberFormatException();
    }

    return flon;
  }

  /* convert latitude string to float
   *
   * DD-MM-SSH where DD is degrees, MM is minutes, SS is seconds and H is N
   * for norThern hemisphere or S for souThern hemisphere. The seconds value
   * is omitted for those stations where The seconds value is unknown
   */

  private float lat_to_float(String lat) throws NumberFormatException{
    float flat;
    String pom;
    int divider=60;
    int multiply=1;

    try{
      StringTokenizer token=new StringTokenizer(lat, "-");

      flat=Float.parseFloat(token.nextToken()); //get degrees
      pom=token.nextToken(); //get minutes

      if (token.hasMoreTokens()){ //is there seconds?
                                //yes
        flat+=Float.parseFloat(pom)/divider;
        divider*=60;
        pom=token.nextToken(); //get seconds
      }

      if (pom.length()>2)
        if (pom.charAt(2)=='S' || pom.charAt(2)=='s') multiply=-1;

      flat+=Float.parseFloat(pom.substring(0,2))/divider;

      flat*=multiply;             //east or west
    }
    catch (NoSuchElementException e){
      if (DEBUG) {
        System.out.println("wrong latitude wormat '"+lat+"'");
      }
      throw new NumberFormatException();
    }
    catch (NumberFormatException e){
      if (DEBUG) {
        System.out.println("wrong latitude wormat '"+lat+"'");
      }
      throw new NumberFormatException();
    }

    return flat;
  }

  private float distance_2D (float lon1, float lat1, float lon2, float lat2){

    /* dist = sqrt(lon_dist^2 + lat_dist^2)
     * but I'm looking for minimum distance, not for exact distance
     * so I can omit sqrt and ^2
     */

    return distance_1D(lon1, lon2)+distance_1D(lat1, lat2);
  }

  private float distance_1D (float x1, float x2){
    float dist;

    dist=Math.abs(x1-x2);
    if (dist>180) dist = 360-dist;
    return dist;
  }

  private String [] parse_icao_long_lat_from_line (String line) throws NoSuchElementException{
    int i=0;
    String [] out = new String[3];

    out[0]="";
    out[1]="";
    out[2]="";

    while (i<line.length()){
      if (line.charAt(i)==';') break;
      out[0]+=line.charAt(i);
      i++;
    }

    int j=0;
    while (i<line.length()){
      i++;
      if (line.charAt(i)==';'){
        j++;
        if (j==6) break;
      }
    }

    while (i<line.length()){
      i++;
      if (line.charAt(i)==';') break;
      out[1]+=line.charAt(i);
    }

    while (i<line.length()){
      i++;
      if (line.charAt(i)==';') break;
      out[2]+=line.charAt(i);
    }

    if (i>=line.length()) throw new NoSuchElementException();

    return out;
  }
}