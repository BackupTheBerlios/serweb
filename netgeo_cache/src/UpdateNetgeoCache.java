import java.sql.*;
import java.util.*;
import java.io.*;

/**
 * <p>Title: </p>
 * <p>Description: </p>
 * <p>Copyright: Copyright (c) 2002</p>
 * <p>Company: </p>
 * @author Karel Kozlik
 * @version 1.0
 */

public class UpdateNetgeoCache {
  // config parameters
  String db_url;
  String netgeo_cache_table;
  String usrloc_table;
  String contact_col;
  int unknown_domains_max_age;
  int known_domains_max_age;
  File station_list;

  public static boolean DEBUG = false;

  //--------------------------
  Connection conn;  /**connection to db*/
  parse_sip sip_parser=new parse_sip();

  void read_config(String config_file){
    Properties properties=new Properties();
    String station_list_file;

    //read config file
    try{
      properties.load(new FileInputStream(config_file));
    }
    catch (FileNotFoundException e){
      System.out.println("Can't open config file "+config_file);
      System.exit(-1);
    }
    catch (IOException e){
      System.out.println("Error in config file "+config_file);
      System.exit(-1);
    }

    //read properties
    db_url=properties.getProperty("db_url");
    if (db_url==null || db_url.compareTo("")==0){
      System.err.println("ERROR: Not specified db_url in config file");
      System.exit(-1);
    }

    station_list_file=properties.getProperty("station_list_file");
    if (station_list_file==null || station_list_file.compareTo("")==0){
      System.err.println("ERROR: Not specified station_list_file in config file");
      System.exit(-1);
    }

    station_list=new File(station_list_file);
    if (!station_list.exists()){
      System.err.println("ERROR: station_list_file does not exists");
      System.exit(-1);
    }

    netgeo_cache_table = properties.getProperty("cache_table", "netgeo_cache");
    usrloc_table = properties.getProperty("usrloc_table", "location");
    contact_col = properties.getProperty("contact_col", "contact");

    String server_url = properties.getProperty("server_url","http://netgeo.caida.org/perl/netgeo.cgi");
    NetGeoClient.DEFAULT_SERVER_URL=server_url;

    try{
      unknown_domains_max_age=Integer.parseInt(properties.getProperty("unknown_domains_max_age", "24"));
    }
    catch (NumberFormatException e){
      System.err.println("ERROR: in config file - unknown_domains_max_age hasn't correct integer value - using default");
      unknown_domains_max_age=24;
    }

    try{
      known_domains_max_age=Integer.parseInt(properties.getProperty("known_domains_max_age", "168"));
    }
    catch (NumberFormatException e){
      System.err.println("ERROR: in config file - known_domains_max_age hasn't correct integer value - using default");
      known_domains_max_age=168;
    }

    DEBUG=Boolean.valueOf(properties.getProperty("debug", "false")).booleanValue();
  }//read_config

  void connect2db(){
    //load MySQL jdbc driver
    try{
      Class.forName("com.mysql.jdbc.Driver").newInstance();
    }
    catch (Exception e){
      System.out.println(e);
      System.exit(-1);
    }

    try{
      //connect to database
      conn = DriverManager.getConnection(db_url);
      //"jdbc:mysql://localhost/iptel?user=root&password=qwer"
    }
    catch (SQLException e){
      System.out.println("Can't connect to database");
      System.out.println(e);
      System.exit(1);
    }

  }

  void go(){
    String q=null;
    String contact;
    String domainname;
    ResultSet res;
    ResultSet res_1;
    NetGeo netgeo;
    String icao_str;
    String location, country, city;

    float lon, lat;
    Icao icao=new Icao(station_list);

    try{
      Statement stmt = conn.createStatement();
      Statement stmt_1 = conn.createStatement();

      q="create table if not exists "+netgeo_cache_table+" (domainname varchar(255) primary key, lon float, lat float, icao char(4), location varchar(128), modified timestamp)";
      stmt.executeUpdate(q);

      if (unknown_domains_max_age!=0){
        q="delete from "+netgeo_cache_table+" where icao is null and modified<subdate(now(), Interval "+unknown_domains_max_age+" hour)";
        stmt.executeUpdate(q);
      }

      if (known_domains_max_age!=0){
        q="delete from "+netgeo_cache_table+" where icao is not null and modified<subdate(now(), Interval "+known_domains_max_age+" hour)";
        stmt.executeUpdate(q);
      }


      res = stmt.executeQuery("select distinct "+contact_col+" from "+usrloc_table);

      while (res.next()) {
        contact=res.getString(contact_col);
        domainname=sip_parser.ul_get_domainname(contact);

        if (domainname==null) continue;

        if (DEBUG){
          System.out.print("*** Domainname: "+domainname);
        }

        //check if domainname is in cache

        q="select count(*) from "+netgeo_cache_table+" where domainname='"+domainname+"'";
        res_1 = stmt_1.executeQuery(q);
        res_1.first();

        if (DEBUG){
          if (res_1.getInt(1)!=0) System.out.println(" - is in cache");
          else System.out.println(" - not in cache - finding");
        }

        if (res_1.getInt(1)!=0) continue;    //this domain name allready is in cache

        netgeo=new NetGeo(domainname);

        if (netgeo.get_lon()==0 && netgeo.get_lat()==0){
          icao_str="NULL";
        }
        else{
          try {
            icao_str="'"+icao.get_icao(netgeo.get_lon(), netgeo.get_lat())+"'";
          }
          catch (IcaoNotFoundException e){
            if (DEBUG){
              System.out.println("can't find icao code for "+domainname);
            }
            icao_str="NULL";
          }
        }

        country=netgeo.get_country();
        if (country==null) country="";

        city=netgeo.get_city();
        if (city==null) city="";

        location=city;

        if (!location.equals("") && !country.equals("")){
          location+=" / ";
        }

        location+=country;

        q="insert into "+netgeo_cache_table+" (domainname, lon, lat, icao, location) "+
          "values ('"+domainname+"', "+netgeo.get_lon()+", "+netgeo.get_lat()+", "+icao_str+", '"+location+"')";
        stmt.executeUpdate(q);

      }

    }
    catch (SQLException e){
      if (q!=null) System.out.println(q);
      System.out.println(e);
      System.exit(1);
    }
  }

  String empty2zero(String str){
    if (str==null || str.compareTo("")==0) return "0";
    else return str;
   }

  public static void main(String[] args) {
    String config_file="netgeo_cache.cfg";
    UpdateNetgeoCache my_NetgeoCache=new UpdateNetgeoCache();

    //get name of config file
    if (args.length>0) {
      config_file=args[0];
    }

    my_NetgeoCache.read_config(config_file);

    NetGeo.DEBUG=DEBUG;
    Icao.DEBUG=DEBUG;

    my_NetgeoCache.connect2db();
    my_NetgeoCache.go();

  }
}