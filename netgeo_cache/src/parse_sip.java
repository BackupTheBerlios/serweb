import com.stevesoft.pat.*;

/**
 * <p>Title: </p>
 * <p>Description: </p>
 * <p>Copyright: Copyright (c) 2002</p>
 * <p>Company: </p>
 * @author Karel Kozlik
 * @version 1.0
 */

public class parse_sip {
  String alphanum;
  String mark;
  String unreserved;
  String escaped;
  String user_unreserved;
  String user;

  String port;
  String hex4;
  String hexseq;
  String hexpart;
  String ipv4address;
  String ipv6address;
  String ipv6reference;

  String toplabel;
  String domainlabel;
  String hostname;
  String host;

  String token;
  String param_unreserved;
  String paramchar;
  String pname;
  String pvalue;
  String uri_parameter;
  String uri_parameters;

  String address;
  String sip_address;

  Regex r;

  public parse_sip() {
    alphanum="[a-zA-Z0-9]";
    mark="[-_.!~*'()]";
    unreserved="("+alphanum+"|"+mark+")";
    escaped="(%[0-9a-fA-F][0-9a-fA-F])";
    user_unreserved="[&=+$,;?/]";
    user="("+unreserved+"|"+escaped+"|"+user_unreserved+")+";

    port="[0-9]+";
    hex4="([0-9a-fA-F]{1,4})";
    hexseq="("+hex4+"(:"+hex4+")*)";
    hexpart="("+hexseq+"|("+hexseq+"::"+hexseq+"?)|(::"+hexseq+"?))";
    ipv4address="([0-9]{1,3}\\.[0-9]{1,3}\\.[0-9]{1,3}\\.[0-9]{1,3})";
    ipv6address="("+hexpart+"(:"+ipv4address+")?)";
    ipv6reference="(\\["+ipv6address+"])";

    toplabel="([a-zA-Z]|([a-zA-Z](-|"+alphanum+")*"+alphanum+"))";
    domainlabel="("+alphanum+"|("+alphanum+"(-|"+alphanum+")*"+alphanum+"))";
    hostname="(("+domainlabel+"\\.)*"+toplabel+"(\\.)?)";
    host="("+hostname+"|"+ipv4address+"|"+ipv6reference+")";

    token="(([-.!%*_+`'~]|"+alphanum+")+)";
    param_unreserved="[\\][/:&+$]";
    paramchar="("+param_unreserved+"|"+unreserved+"|"+escaped+")";
    pname="(("+paramchar+")+)";
    pvalue="(("+paramchar+")+)";
    uri_parameter="("+pname+"(="+pvalue+")?)";
    uri_parameters="((;"+uri_parameter+")*)";

    address="("+user+"@)?"+host+"(:"+port+")?"+uri_parameters;
    sip_address="[sS][iI][pP]:"+address;

    r=new Regex(sip_address+"$");
  }

  public String ul_get_domainname(String sip_uri){
    r.matchAt(sip_uri.trim(),0);
    return r.stringMatched(5);  //fifth set of parenthesis is domainname
  }
}