/*
 *$Id: STUNClientApplet.java,v 1.1 2002/11/18 22:03:25 kozlik Exp $
 */

/**
 * <p>Title: </p>
 * <p>Description: </p>
 * <p>Copyright: Copyright (c) 2002</p>
 * <p>Company: </p>
 * @author kozlik
 * @version 1.0
 */

import java.awt.*;
import java.awt.event.*;
import java.applet.*;

public class STUNClientApplet extends Applet {
  static boolean DEBUG = false;
  private boolean isStandalone = false;

  STUNClientDetectFW detectFW;

  String server_param;
  String port_param;
  int retransmit_param; /* Number of times to restransmit a STUN message.
                         * Default is 9 based on the STUN proposal
                         * but it can be dynamically configured by
                         * the client.
                         */
  int sourceport_param; // User specified source port for the UDP packet

  Label label1 = new Label();
  Label label2 = new Label();
  Label label3 = new Label();

  private GridLayout gridLayout1 = new GridLayout(5,1);

  //Get a parameter value
  public String getParameter(String key, String def) {
    return isStandalone ? System.getProperty(key, def) :
      (getParameter(key) != null ? getParameter(key) : def);
  }

  //Construct the applet
  public STUNClientApplet() {
  }

  //Initialize the applet
  public void init() {
    try {
      server_param = this.getParameter("server", "");
    }
    catch(Exception e) {
      e.printStackTrace();
    }
    try {
      port_param = this.getParameter("port", "1221");
    }
    catch(Exception e) {
      e.printStackTrace();
    }
    try {
      retransmit_param = Integer.parseInt(this.getParameter("retransmit", "9"));
    }
    catch(Exception e) {
      e.printStackTrace();
    }
    try {
      sourceport_param = Integer.parseInt(this.getParameter("sourceport", "5000"));
    }
    catch(Exception e) {
      e.printStackTrace();
    }

    try {
      jbInit();
    }
    catch(Exception e) {
      e.printStackTrace();
    }

    if (this.server_param!="") {
      detectFW = new STUNClientDetectFW(this);
      detectFW.start();
    };
  }

  //Component initialization
  private void jbInit() throws Exception {
    label1.setAlignment(label1.CENTER);
    label2.setAlignment(label2.CENTER);
    label3.setAlignment(label3.CENTER);

    if (this.server_param!="")
      label2.setText("finding firewall or NAT, please wait");
    else
      label2.setText("ERROR: not specified STUN server address");

    this.setBackground(new Color(177, 201, 220));
    this.setLayout(gridLayout1);

    this.add(new Label(), null);  //space on top
    this.add(label1, null);
    this.add(label2, null);
    this.add(label3, null);
    this.add(new Label(), null);  //space on bottom
  }

  //Get Applet information
  public String getAppletInfo() {
    return "Firewall/NAT detection applet - using STUN client from http://www.columbia.edu/~pt81/cs6901-08/cs6901-08.html";
  }

  //Get parameter info
  public String[][] getParameterInfo() {
    String[][] pinfo =
      {
      {"server", "String", "STUN server address"},
      {"port", "int", "optional - STUN server port. The Default value is 1221"},
      {"retransmit", "int", "optional - Number of times to resend a STUN message to a STUN server. The Default is 9 times"},
      {"sourceport", "int", "optional - Specify source port of UDP packet to be sent from. The Default value is 5000"},
      };
    return pinfo;
  }
}