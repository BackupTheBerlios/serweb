/*
 *$Id: STUNClientDetectFW.java,v 1.1 2002/11/18 22:03:25 kozlik Exp $
 */

/**
 * <p>Title: </p>
 * <p>Description: </p>
 * <p>Copyright: Copyright (c) 2002</p>
 * <p>Company: </p>
 * @author Kozlik
 * @version 1.0
 */

import java.net.*;
import java.util.*;

public class STUNClientDetectFW extends Thread {
  static boolean DEBUG = false;
  STUNClientApplet pappy;
STUNClient sPacket__;

  public STUNClientDetectFW(STUNClientApplet pappy) {
    this.pappy=pappy;
  }

  public void run(){
    int i_attributeFlagsInfo = 0;  // Used to indicate which flag is used
    DatagramPacket udpPacket = null;

    boolean REPORT_MODE = false; /* Report mode is used to print to the
                                  * standard output NAT information that
                                  * have been discovered.
                                  * E.g. <IP address> <port> <NAT type>
                                  * If the mode is set to true, then
                                  * additional messages are printed to
                                  * the standard output.
                                  */


    STUNPacket sPacket = new STUNPacket();
    sPacket.DEBUG = DEBUG; /* Pass the DEBUG flag to the packet in order to
                            * activate the debug messages during parsing.
                            */
    SizeOf s = new SizeOf();

    STUNClient SClient = new STUNClient();
    SClient.DEBUG = DEBUG; // Set the default debugging flag.

    /***********************************/
    /* Format the client STUN request. */
    /***********************************/

    short mt = 1;			// Message type is request
    int p_id = SClient.generateID();  	// Get a random transaction ID
    byte []attr_val = new byte[100];	// Allocate attribute value buffer


    // Byte array holds attribute information to be passed to the STUNpacket.

    byte [] b_Attributes = null;

    STUNPacket SPacket = new STUNPacket(mt,                              // Message type is 1 (request)
                                        p_id,                            // Packet ID
                                        (short) (s.SizeOf(b_Attributes)) // Calculate packet size
                                       );

    /* If an attribute is  requested format and populate the attributes
     * fields and recalculate the message size.
     */

    /* If the attribute is a FLAG then populate the value field with the
     * corresponding value.
     */

    b_Attributes = null;

    SPacket.setMessageLength((short)s.SizeOf(SPacket.messageAttributes));

    SPacket.DEBUG = DEBUG; 	// Set DEBUGGING flag if requested by user.
    SPacket.parse(SPacket.getPacket());

    /* Display what we sent to the server */
    if (DEBUG) {
        System.out.println("Client STUN Request Header Information");
        SPacket.printHeader();
        System.out.println("Client STUN Request Body Information");
        SPacket.printPayload(SPacket.messageAttributes);
        System.out.println("Client Contents in messageAttributes byte "
                           +"array = "
                           +SPacket.messageAttributes);
    }

    UDPClient UDPC = new UDPClient(pappy.server_param, pappy.port_param,
                                   SPacket.getPacket());
    UDPC.DEBUG = DEBUG; 	// Set DEBUGGING flag if requested by user.

    /* The i_attributeFlagsInfo at the moment is used to halt further
     * processing in the sendPacket routine in case the mesage request
     * was marked as DISCARD.
     */

    try{
      udpPacket = UDPC.sendPacket(i_attributeFlagsInfo,
                                  pappy.retransmit_param,
                                  pappy.sourceport_param);
    }
    catch (Exception e){
      e.printStackTrace();
      pappy.label2.setText("ERROR: can't send UDP packet");
      return;
    }

    /* NO UDP REACHABILITY -NU-
     * If we haven't received anything from the server it may be because
     * we are not allowed to route UDP packets.
     */
    if ( udpPacket == null ) {
      System.out.println(UDPC.getLocalIP()+" "+UDPC.getLocalPort()+" NU");
      pappy.label2.setText("No UDP accesibility");
      return;
      //      System.exit(0);
    }


    STUNPacket SRPacket = new STUNPacket();
    SRPacket.DEBUG = DEBUG; // Set DEBUGGING flag if requested by user.

    if ( DEBUG == true ) {
      System.out.println("Parsing packet received by server "
                         +udpPacket.getAddress().getHostAddress()
                         +":"+udpPacket.getPort());
    }

    SRPacket.parse(udpPacket.getData());

    if ((DEBUG == true)) {
      System.out.println("DONE Parsing packet received by server");
    }

    /* Print the fields in human redable format
     * from the packet that was sent by the STUN server.
     */
    if ( DEBUG == true ) {
      SRPacket.printHeader();
      SRPacket.printPayload(SRPacket.messageAttributes);
    }

    /* Check to see if we are behind a NAT.
     * If the local IP is different from the IP contained
     * in the STUN response then we are behind a NAT and
     * we go on processing the request further.
     * The reporting format is as follows:
     * 		<IP address>[space]<port>[space]<NAT Type>
     *
     * Where NAT type is substitued by:
     *       FC = full cone
     *       RC = Restricted Cone
     *       PR = Port restricted cone
     *       SN = Symmetric NAT
     *       NN = Not behind NAT
     *       NU = No UDP accesibility
     */

    if ( DEBUG == true ) {
      System.out.println("Local IP ["
                         +UDPC.getLocalIP()
                         +":"
                         +UDPC.getLocalPort()
                         +"]");

      System.out.println("STUN IP ["
                         +SClient.getMappedAddress(SRPacket)
                         +"]");
    }

    /* NOT BEHIND A NAT -NN-
     * Inspection for NN = Not behind NAT
     */
    String r_response = SClient.getMappedAddress(SRPacket);
    StringTokenizer st_response = new StringTokenizer(r_response,":");
    String mappedIP  = st_response.nextToken();

    if ((UDPC.getLocalIP()).compareTo(mappedIP) == 0) {
      if ( REPORT_MODE == true ) {
        System.out.println("This Client is not behind a NAT");
        System.out.println("Local IP ["
                           +UDPC.getLocalIP()
                           +":"
                           +UDPC.getLocalPort()
                           +"]");
        System.out.println("NAT IP ["
                           +SClient.getMappedAddress(SRPacket)
                           +"]");

        pappy.label1.setText("This Client is not behind a NAT");
        pappy.label2.setText("Local IP ["
                           +UDPC.getLocalIP()
                           +":"
                           +UDPC.getLocalPort()
                           +"]");
        pappy.label3.setText("NAT IP ["
                           +SClient.getMappedAddress(SRPacket)
                           +"]");
      }
      else {
        String s_response = SClient.getMappedAddress(SRPacket);
        StringTokenizer st_firstResponse = new StringTokenizer(s_response,":");

        String s_NATIP_Rsp = st_firstResponse.nextToken();
        String s_NATPort_Rsp = st_firstResponse.nextToken();
        System.out.println(s_NATIP_Rsp +" "+s_NATPort_Rsp+" NN");
        pappy.label2.setText("This Client is not behind a NAT");

      }
    } // if
    else { // This client is behind a NAT
      System.out.println("This Client is behind a NAT");
      System.out.println("Local IP ["+UDPC.getLocalIP()
                         +":"
                         +UDPC.getLocalPort()
                         +"]");

      System.out.println("NAT IP ["
                         +SClient.getMappedAddress(SRPacket)
                         +"]");

      pappy.label1.setText("This Client is behind a NAT");
      pappy.label2.setText("Local IP ["+UDPC.getLocalIP()
                           +":"
                           +UDPC.getLocalPort()
                           +"]");
      pappy.label3.setText("NAT IP ["
                           +SClient.getMappedAddress(SRPacket)
                           +"]");
    }
  }
}