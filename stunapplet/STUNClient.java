/* @(#)STUNClient.java        2002/02/08
 * STUNClient formlulates STUN requests and sends them
 * using udp packets to a STUN server. 
 *  
 * Panayiotis Thermos, pt81@columbia.edu, www.columbia.edu/~pt81 
 *
 * Copyright 2001 by Columbia University; all rights reserved
 * Permission to use, copy, modify, and distribute this software and its 
 * documentation for not-for-profit research and educational purposes and 
 * without fee is hereby granted, provided that the above copyright notice 
 * appear in all copies and that both that the copyright notice and warranty 
 * disclaimer appear in supporting documentation, and that the names of the 
 * copyright holders or any of their entities not be used in advertising or 
 * publicity pertaining to distribution of the software without specific, 
 * written prior permission. Use of this software in whole or in parts for 
 * commercial advantage and by for-profit organizations requires a license.

 * The copyright holders disclaim all warranties with regard to this software, 
 * including all implied warranties of merchantability and fitness. In no event 
 * shall the copyright holders be liable for any special, indirect or consequential 
 * damages or any damages whatsoever resulting from loss of use, data or profits, 
 * whether in an action of contract, negligence or other tortuous action, arising 
 * out of or in connection with the use or performance of this software.
 */

import java.io.*;
import java.nio.*;
import java.net.*;
import java.util.*;
import gnu.getopt.Getopt;
import gnu.getopt.LongOpt;

public class STUNClient {   
    static boolean DEBUG = false;
    
    /** STUNClient Conctructor */
    public STUNClient() {
    }
       
    /** generateID() Generates a random number to be used as the 
     ** Transaction ID. 
     ** Returns an integer.
     */
    public int generateID(){
    	Random IDR = new Random();
    	Integer i_IDR = new Integer(IDR.nextInt(9999));
    	return  (IDR.nextInt(2147483646));
    }
    
    /** Prints the command line options of the STUN Client */
    public static void usage() {
    System.out.println("STUNClient <options> -s<STUNServer> -p<port>");
    System.out.println("Options :");
    System.out.println("	[-d] Turn Debugging messages on.");
    System.out.println("	[-a] Automates the discovery of NAT's "
    					+ "based on the STUN proposal algorithm.");
    System.out.println("	[-h] Help, this listing.");
    System.out.println("	[-x] Turn on REPORT MODE. Additional "
    					+"messages are printed on stdout.");
    System.out.println("	[-n] Number of times to resend a STUN " 
    					+"message to a STUN server.");
    System.out.println("		 The Default is 9 times.");
    System.out.println("	[-f] Set the FLAGS in a STUN message");
    System.out.println("		 The available flags are:");
    System.out.println("		 	1 - set the change-IP flag");
    System.out.println("		 	2 - set the change-port flag");
    System.out.println("		 	3 - Discard");
    System.out.println("		 	4 - set change port and IP");
    System.out.println("	Set the type of flag in a STUN message."
    					+" Available choices are:");
    System.out.println("	[-m]    MAPPED-ADDRESS");
    System.out.println("	[-r]    RESPONSE-ADDRESS");     
    System.out.println("	[-o]    SOURCE-ADDRESS"); 
    System.out.println("		 These flags require three parameters,"); 
    System.out.println("		 	1) port - the remote port to send "
    					+" the STUN response");
    System.out.println("		 	2) family - The address familiy 1"
    					+" for IPv4, 2 for IPv6");
    System.out.println("		 	3) IP address - the remote IP "
    					+"address to send the STUN response");
    System.out.println("		 	No spaces are required in between "
    					+" when setting these parameters, ");
    System.out.println("		 	for example, -r 1221,1,127.0.0.1,"
    					+" 1221=port, 1=family, 127.0.0.1=IP");
     System.out.println("	[-u] Specify source port of UDP packet to be sent"
     					+" from.");   					
    } // end of usage()
	
	
	/** The main() function drives the "automated discovery" process and it
	 *  can be used to send single STUN messages to a STUN server.
	 */
	public static void	main(String []args) {
		byte [] clientMessage = null; 
		byte [] response = null;
		DatagramPacket udpPacket = null;
		DatagramSocket socket	= null;
		boolean REPORT_MODE = false; /* Report mode is used to print to the 
									  * standard output NAT information that 
									  * have been discovered.
									  * E.g. <IP address> <port> <NAT type>
									  * If the mode is set to true, then 
									  * additional messages are printed to 
									  * the standard output.
									  */
		
		boolean AUTOMATE	= false; /* Automate the discovery of NAT's based
									  * on the STUN proposal algorithm.
									  */							  
		String serverAddress = "127.0.0.1";  	/* Default values */
		String portNumber = "1221";
		STUNPacket t_SP	 = new STUNPacket(); 	/* Used to reference fields 
												 * from the STUN packet.
											 	 */
		STUNClient SClient = new STUNClient();
		SClient.DEBUG = DEBUG; 					/* Set the default debugging
												 * flag. 
												 */
		
		int retransmit	  = 9; /* Number of times to restransmit a STUN message.
								* Default is 9 based on the STUN proposal
								* but it can be dynamically configured by 
								* the client.
								*/
	
		int sourcePort = 5000;	/* User specified source port for the UDP 
								 * packet 
								 */
		
		String flag = null;     /* Used to indicate the type of flag to be used
								 * in the STUN message. 
								 */
		
		String s_attributeTypeInfo	= null;/* Holds the information when the 
											* response flag is set.
										 	* This information is formated 
										 	* as port-family-IPaddress
										 	*/
										 	
		int i_attributeFlagsInfo = 0; 		/* Used to indicate which flag 
											 * is used 
											 */
		
		short attributeType = 0; 	/* Holds the type of the message attribute. */
		
		int c; // Used for parsing the command line arguments
		
		Getopt options = null;
		try {
			options = new Getopt("STUNClient", args, 
								"h::a::x::d::i::f::m::n::r::o::l::t::u::s:p:");
		} catch (MissingResourceException mr){
			mr.printStackTrace();
			usage();
			System.exit(0);
		} // try
	
		
		try {
			while ( (c = options.getopt()) != -1) {
				switch (c) {
					case 's': // Remote STUN Server address
							serverAddress = options.getOptarg();
							if ( serverAddress == null ) {
								usage();
							}
							break;
					case 'p': // Remote STUN Server port
						    portNumber = options.getOptarg();
							if ( portNumber == null ) {
								usage();
							}
							break;
					case 'n': // Retransmits
							retransmit = Integer.parseInt(options.getOptarg());
							break;
					case 'f': // FLAGS
							attributeType = 0x0003;
							flag = options.getOptarg();
							if ( flag.compareTo("1") == 0 ){ 
								i_attributeFlagsInfo = t_SP.CHANGE_IP; 
								}
							if ( flag.compareTo("2") == 0 ){ 
								i_attributeFlagsInfo = t_SP.CHANGE_PORT; 
								}
							if ( flag.compareTo("3") == 0) { 
								i_attributeFlagsInfo = t_SP.DISCARD; 
								}
							if ( flag.compareTo("4") == 0) { 
								i_attributeFlagsInfo = t_SP.CHANGE_IP 
								| t_SP.CHANGE_PORT; 
								}
							break;
					case 'm': // MAPPED-ADDRESS
							attributeType = 0x0001;
							s_attributeTypeInfo = options.getOptarg();
							break;
				    case 'r': // RESPONSE-ADDRESS
				    		attributeType = 0x0002;
				    		s_attributeTypeInfo = options.getOptarg();
							break;
				    case 'o': // SOURCE-ADDRESS
							attributeType = 0x0004;
							s_attributeTypeInfo = options.getOptarg();
							break;
					case 'd': // DEBUG messages turned on
							DEBUG = true;
							break;
					case 'x': // REPORT_MODE is turned on
							REPORT_MODE = true;
							break;
					case 'a': // AUTOMATE
							AUTOMATE = true;
							break;
					case 'u':// Source port for UDP packet specified
							sourcePort = Integer.parseInt(options.getOptarg());	
							break;	
					case 'h': // Help on command line options
							usage();
							System.exit(0);
					default:
						usage();
						System.exit(0);			
				} // switch
					
			} // while - parsing command line arguments
			
		} catch (NullPointerException np) {
			usage();
			System.exit(-1);
		} //try
		
		/* If automated discovery is selected, perform discovery and exit.
		 * Otherwise use the supplied options to "drive" the STUN client.
		 */
		if (AUTOMATE == true) {
			
			String localIP = null;
			try {
				socket = new DatagramSocket();
				localIP = new String(socket.getLocalAddress()
							.getLocalHost().getHostAddress());
			} catch (SocketException se){
				System.out.println("STUNClient CLIENT ERROR "+se.getMessage()
									+" Couldn't create UDP socket.\n");
				se.printStackTrace();
			} catch (UnknownHostException uhe){
				System.out.println("UDP CLIENT ERROR :"+uhe.getMessage());
				uhe.printStackTrace();
			}			
			
			int localPort = socket.getLocalPort();
			
			if (REPORT_MODE == true) {
				System.out.println("Automated Discovery started.");
			}
			
			
			if (REPORT_MODE == true) {
				System.out.println("Test I - Sending message with no attributes " 
									+"set to "
									+serverAddress
									+":"+portNumber);
			}
			
			/* Create an instance of a STUN packet,
			 * set the debug flag and send the first
			 * request without any attributes set.
			 */
			STUNPacket sp_Client = new STUNPacket();
			sp_Client.DEBUG = DEBUG;
			
			
			/* Test I
			 * send a simple message with no attributes and request
			 * to get the "MAPPED-ADDRESS" by indicating "1".
			 */
			sp_Client = SClient.sendSimpleMessage(serverAddress, 
													portNumber, 
													sourcePort);


			/* Parse the server's response message. Aids in debugging. */              		       
		    if ( DEBUG ) {
	        	System.out.println("Parsing Received Message");              
            	sp_Client.parse(sp_Client.getPacket());
              	System.out.println("DONE - Parsing Received Message");
	        	System.out.println("Message Contents");
	        	sp_Client.printHeader();
				sp_Client.printPayload(sp_Client.messageAttributes);		
		    } 

			/* If the response is null then we are probably restricted
			 * from routing any traffic (Perhaps we are behind a firewall).
			 */
			if ( sp_Client == null ) {
		
				if (REPORT_MODE == true) {
					System.out.println("Unable to establish a UDP connection.");
				}
				
				System.out.println(localIP+" "+localPort+" NU");
				System.exit(0);
			}			
			
			/* Retrieve the MAPPED_ADDRESS and CHANGED_ADDRESS
			 * from the initial response for further processing.
			 */
			String s_firstResponse = SClient.getMappedAddress(sp_Client);
			String s_ChangedAddress = SClient.getChangedAddress(sp_Client);
			String s_NATIP_FrstRsp = null;
			String s_NATPort_FrstRsp = null;
			StringTokenizer st_firstResponse = null;
			
			/* Parse the response information */
			try {
				st_firstResponse = new StringTokenizer(s_firstResponse,":");
				s_NATIP_FrstRsp = st_firstResponse.nextToken();
				s_NATPort_FrstRsp = st_firstResponse.nextToken();
			
			} catch (NoSuchElementException nse) {
				if (DEBUG == true) {
					nse.getMessage();
					nse.printStackTrace();
				}
			}
			
			/* See if this host is behind a NAT.
			 * If the current IP matches the IP received in the response
			 * then we are not behind a NAT.
			 */
			if (localIP.compareTo(s_NATIP_FrstRsp) == 0) {
				System.out.println(s_NATIP_FrstRsp+" "+s_NATPort_FrstRsp+" NN");
				System.exit(0);
			}
			
			if (REPORT_MODE == true) {
				System.out.println("\tResponse from Test I - NAT Information " 
													+"["
													+s_NATIP_FrstRsp+":"
													+s_NATPort_FrstRsp+"]");
			}
													
			
			if (REPORT_MODE == true) {
				System.out.println("Test II - Sending message with CHANGE-IP"
									+" and CHANGE-PORT attributes set, to "
									+serverAddress+":"+portNumber);
				System.out.println("\t Local IP and port "
									+localIP+":"+sourcePort);					
			}
			
			/* Test II
			 * Send a message with the change-ip and change-port flags set.
			 * In this case we pass a predifined short integer "34" to triger
			 * the sendMessageWithAttributes function to craft a message
			 * with the change-ip and change-port two attributes.
			 * The number 34 is taken is derived from the following:
			 *  	3 = FLAGS attribute 0x0003
			 *		4 = the fourth choice in the combination of the flags
			 *          (see command line args)
			 */
			short attr_flag = 34;
			STUNPacket sp_Client_TestII  = SClient.sendMessageWithAttributes(
															serverAddress, 
															portNumber, 
															attr_flag, 
															s_NATIP_FrstRsp, 
															s_NATPort_FrstRsp, 
															sourcePort);
															
			/* Parse the server's response message. Aids in debugging. */              		       
		    if ( DEBUG ) {
	        	System.out.println("Parsing Received Message");              
            	sp_Client_TestII.parse(sp_Client_TestII.getPacket());
              	System.out.println("DONE - Parsing Received Message");
	        	System.out.println("Message Contents");
	        	sp_Client_TestII.printHeader();
				sp_Client_TestII.printPayload(sp_Client_TestII.messageAttributes);		
		    } 
			
		
			try {
				sp_Client_TestII.DEBUG = DEBUG;														
			} catch (NullPointerException npe){
				if (DEBUG == true) {
					npe.printStackTrace();
				}
			}
			
			/* Check to see if we received a response.
			 * if we did, then we are probably behind a NAT or a firewall
			 * otherwise we are probably behind a symetric NAT.
			 */ 
			
			if ( sp_Client_TestII  == null ) { 
			/* no response looks like a Symetric NAT */
				
				/* Perform Test I again but use the CHANGED-ADDRESS information
				 * that was received from the STUN message in Test-II.
				 */

				/* Get the changed-address information */ 
				try {
					s_ChangedAddress = SClient.getChangedAddress(sp_Client);
					st_firstResponse = new StringTokenizer(s_ChangedAddress,":");
				} catch (NullPointerException np) {
					if (DEBUG == true) {
						np.getMessage();
						np.printStackTrace();
					}
				}
				
				String s_NATIP_ChgAdr =  null;
				String s_NATPort_ChgAdr  = null;
				String s_NATIP_ChgAdr_Client =  null;
				String s_NATPort_ChgAdr_Client  = null;
				
				
				try {
					s_NATIP_ChgAdr = st_firstResponse.nextToken();
					s_NATPort_ChgAdr = st_firstResponse.nextToken();
				} catch (NoSuchElementException nse) {
					if (DEBUG == true) {
						nse.getMessage();
						nse.printStackTrace();
					}
				}
						
				if (REPORT_MODE == true){
					System.out.println("\tNo Response Received. Resending "
									+"message with no attributes to "								
									+s_NATIP_ChgAdr+":"+s_NATPort_ChgAdr);
				}
						
				/* Since no response was received we resend the message with 
				 * the new IP and port from the changed address info.
				 */
				
				STUNPacket sp_Client_testII_simple = SClient.sendSimpleMessage(
													s_NATIP_ChgAdr, 
													s_NATPort_ChgAdr, 
													sourcePort);
				
				/* Parse the server's response message. Aids in debugging. */              		       
		    	if ( DEBUG ) {
		        	System.out.println("Parsing Received Message");              
	            	sp_Client_testII_simple.parse(sp_Client_testII_simple.getPacket());
	              	System.out.println("DONE - Parsing Received Message");
		        	System.out.println("Message Contents");
		        	sp_Client_testII_simple.printHeader();
					sp_Client_testII_simple.printPayload(
											sp_Client_testII_simple.messageAttributes);		
		    	}
				
				
				
				sp_Client_testII_simple.DEBUG = DEBUG;
				
				/* Extract the MAPPED_ADDRESS observed by the STUN server */									
				String s_ResponseChgAdr = SClient.getMappedAddress(
														sp_Client_testII_simple);
				StringTokenizer st_ResponseChgAdr = new StringTokenizer(
														s_ResponseChgAdr,":");
				s_NATIP_ChgAdr_Client = st_ResponseChgAdr.nextToken();
				s_NATPort_ChgAdr_Client = st_ResponseChgAdr.nextToken();				
				

				if (REPORT_MODE == true){
					System.out.println("\tResponse Received From "
									+"message with no attributes ["								
									+s_NATIP_ChgAdr_Client+":"+s_NATPort_ChgAdr_Client
									+"]");
				}
				
				if ( (s_NATIP_FrstRsp.compareTo(s_NATIP_ChgAdr_Client) != 0) 
					//||  (s_NATPort_FrstRsp.compareTo(s_NATPort_ChgAdr_Client) != 0) 
					){
					
					/* We are behind a symmetric NAT */
					System.out.println(s_NATIP_FrstRsp+" "
										+s_NATPort_FrstRsp+" SN");	
					System.exit(0);
				}
				
				/* If the received address is the same as the client's then it
				 * is behind a restricted or port restricted NAT.
				 */ 
				else if ( s_NATIP_FrstRsp.compareTo(s_NATIP_ChgAdr_Client) == 0 ){
				
					if (REPORT_MODE == true) {
					System.out.println("Test III - Sending message with "
										+"CHANGE-PORT attribute set, to "
										+serverAddress+":"+portNumber);
					}
				
					/* Test III
					 * Send a message with the change-port flag set only.
					 * In this case we pass a predifined short integer "32" to 
					 * triger the sendMessageWithAttributes function to craft 
					 * a message with the change-port attribute.
					 * The number 32 is taken is derived from the following:
					 *  	3 = FLAGS attribute 0x0003
					 *		2 = change port from the available flag combinations 
					 *          (see command line args)
					 *			 
					 */
				
					STUNPacket sp_Test_III_response = 
											SClient.sendMessageWithAttributes(
											serverAddress, 
											portNumber,
											(short)sp_Client.CHANGE_PORT,
											s_NATIP_FrstRsp,
											s_NATPort_FrstRsp,
											sourcePort
											);					
					
					
					/* Parse the server's response message. Aids in debugging. */              		       
		    		if ( DEBUG ) {
			        	System.out.println("Parsing Received Message");              
		            	sp_Test_III_response.parse(sp_Test_III_response.getPacket());
		              	System.out.println("DONE - Parsing Received Message");
			        	System.out.println("Message Contents");
			        	sp_Test_III_response.printHeader();
						sp_Test_III_response.printPayload(
												sp_Test_III_response.messageAttributes);		
		    		}
					
					
					sp_Test_III_response.DEBUG = DEBUG;
					
					String s_Test_III_Response = SClient.getMappedAddress(
														sp_Test_III_response);
														
					StringTokenizer st_Test_III_Response = new StringTokenizer(
														s_Test_III_Response,":");
														
					String s_Test_III_Response_IP = 
											st_Test_III_Response.nextToken();
											
					String s_Test_III_Response_Port  = 
											st_Test_III_Response.nextToken();
				
					if (REPORT_MODE == true) {
						System.out.println("\tResponse from message with "
										+" CHANGE-PORT attribute set ["
									   	+s_Test_III_Response_IP +":"
									   	+s_Test_III_Response_Port+"]");
					}
					
					if ( sp_Test_III_response == null ) { 
						
						/* We are porbably behind a Port restricted NAT */
						System.out.println(s_NATIP_FrstRsp+" "
											+s_NATPort_FrstRsp+" PR");	
						System.exit(0);												
					}
					else {
						/* We are porbably behind a restricted NAT */
						System.out.println(s_NATIP_FrstRsp
											+" "
											+s_NATPort_FrstRsp+" RN");	
						System.exit(0);	
					}
					
				} // else-if
			
			} // if no-response
			
			/* If there is a  response from test II then
			 * we are behind a Full Cone  NAT.
			 */
			if (sp_Client_TestII  != null) {
				
				if ( REPORT_MODE ){
					System.out.println("\tTest II Response is not empty. Looks "
													+" like a Full Cone NAT");
				}

				String s_Test_II_Response = SClient.getMappedAddress(
															sp_Client_TestII);
														
				st_firstResponse = new StringTokenizer(s_Test_II_Response,":");
				
				String s_NATIP_FrstRsp_TestII = "";
				String s_NATPort_FrstRsp_TestII = "";
				try {
					s_NATIP_FrstRsp_TestII = st_firstResponse.nextToken();
					s_NATPort_FrstRsp_TestII = st_firstResponse.nextToken();
				}catch (NoSuchElementException nse){
					if (DEBUG) {
						nse.printStackTrace();
					}
				}
				
				if ( localIP.compareTo(s_NATIP_FrstRsp) != 0 ){
						
					/* We are behind a symmetric NAT */
					System.out.println(s_NATIP_FrstRsp+" "
										+s_NATPort_FrstRsp
										+" FC");	
				}
				else {
					/* ??? what NAT ???  */
					System.out.println(s_NATIP_FrstRsp+" "
										+s_NATPort_FrstRsp
										+" UNDEFINED");	
				}
			
			} // if response not null
			
			System.exit(0); /* terminate program if automated discovery 
							 * is selected.
							 */
				
		} // AUTOMATE Discovery
		
		STUNPacket sPacket = new STUNPacket();
        sPacket.DEBUG = DEBUG; /* Pass the DEBUG flag to the packet in order to
                                * activate the debug messages during parsing.
                                */
        SizeOf s = new SizeOf();
        
        /***********************************/
        /* Format the client STUN request. */
        /***********************************/
        
        short mt = 1;						// Message type is request
        int p_id = SClient.generateID();  	// Get a random transaction ID
        byte []attr_val = new byte[100];	// Allocate attribute value buffer
        
        
        /* Byte array holds attribute information to be passed to
         * the STUNpacket.
         */
        byte [] b_Attributes = null;
  
  
  		STUNPacket SPacket = new STUNPacket(mt,			// Message type is 1 (request)
											p_id,   	// Packet ID
											(short) (s.SizeOf(b_Attributes)) 	// Calculate packet size
											);  
  
        
        /* If an attribute is  requested format and populate the attributes 
         * fields and recalculate the message size.
         */
        
        /* If the attribute is a FLAG then populate the value field with the 
         * corresponding value.
         */

        if ( attributeType == t_SP.FLAGS ){
        	ByteBuffer bb_flag = ByteBuffer.allocate(s.SizeOf(
        												i_attributeFlagsInfo));
        												
        	bb_flag.putInt(i_attributeFlagsInfo);                 									
	      	SPacket.addAttribute((short)attributeType,
	        					(short)s.SizeOf(bb_flag.array()), 
	        					bb_flag.array());
	        						
      
        } else if ( attributeType > 0) { 
            
            /* Extract the attributes [port][family][address] */
            StringTokenizer st_attributeTypeInfo = new StringTokenizer(
														s_attributeTypeInfo,",");
		
			short aTIport = (short) Integer.parseInt(st_attributeTypeInfo.nextToken());
			String s_fam = new String(st_attributeTypeInfo.nextToken());
			Byte b_aTIfamily = new Byte(s_fam);
			byte aTIfamily = b_aTIfamily.byteValue();			 						
            String aTIaddress = st_attributeTypeInfo.nextToken();
           
            ByteBuffer bb_TI = ByteBuffer.allocate(s_attributeTypeInfo.length());
            bb_TI.position(0);
            bb_TI.putShort(aTIport);
            bb_TI.position(2);
            bb_TI.put(aTIfamily); 
            bb_TI.position(3);
            bb_TI.put(aTIaddress.getBytes()); 
            bb_TI.rewind();                                  
            //byte [] b_AVt = s_attributeTypeInfo.getBytes();            	 	
	        SPacket.addAttribute((short)attributeType,
					(short)s.SizeOf(bb_TI.array()), 
					bb_TI.array());											
        } // if attribute is requested.
        else {
        	b_Attributes = null;
        }

        SPacket.setMessageLength((short)s.SizeOf(SPacket.messageAttributes));
	    

	    
	    SPacket.DEBUG = DEBUG; 		// Set DEBUGGING flag if requested by user.
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
		
		UDPClient UDPC = new UDPClient(serverAddress, portNumber, 
										SPacket.getPacket());						
		UDPC.DEBUG = DEBUG; 	// Set DEBUGGING flag if requested by user.	
		
		/* The 	i_attributeFlagsInfo at the moment is used to halt further 
		 * processing in the sendPacket routine in case the mesage request 
		 * was marked as DISCARD.
		 */
		udpPacket = UDPC.sendPacket(i_attributeFlagsInfo, 
									retransmit, 
									sourcePort);
		
		/* NO UDP REACHABILITY -NU-
		 * If we haven't received anything from the server it may be because
		 * we are not allowed to route UDP packets. 
		 */
		if ( udpPacket == null ) {
			System.out.println(UDPC.getLocalIP()+" "+UDPC.getLocalPort()+" NU");
			System.exit(0);
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
         * 		FC = full cone
      	 * 		RC = Restricted Cone
	     * 		PR = Port restricted cone
         * 		SN = Symmetric NAT
		 *      NN = Not behind NAT
		 *      NU = No UDP accesibility
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
			}
			else {
			    String s_response = SClient.getMappedAddress(SRPacket);
				StringTokenizer st_firstResponse = new StringTokenizer(
																s_response,":");
																
				String s_NATIP_Rsp = st_firstResponse.nextToken();
				String s_NATPort_Rsp = st_firstResponse.nextToken();				
				System.out.println(s_NATIP_Rsp +" "+s_NATPort_Rsp+" NN");
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
		
		}
		
		
	} // End of main()
	
	
	/** sendSimpleMessage()
	 **  Sends a simple message with no attributes.
	 **  Returns a STUN packet or null if no response is received.
	 */
	public STUNPacket sendSimpleMessage(String serverAddress, 
										String portNumber, 
										int sourcePort){
		
		STUNClient SClient = new STUNClient();
		short mt = 1;						// Message type is request
        int p_id = SClient.generateID();  	// Get a random transaction ID
        int messagesize = 0;
		
		
		STUNPacket SPacket = new STUNPacket(mt,		// Message type is 1 (request)
									p_id,   		// Packet ID
									(short)messagesize 	// Calculate packet size
									);
		SPacket.DEBUG = DEBUG;
		UDPClient UDPC = new UDPClient(serverAddress, portNumber, 
										SPacket.getPacket());
		UDPC.DEBUG = DEBUG;
		
		/* parameteres in UDPC.sendPacket
		 * 		-attribute flag = 0, no flags
		 *		-retransmit	= 9, default
		 */									
		DatagramPacket udpPacket = UDPC.sendPacket(0, 9, sourcePort);							
		
		/* We parse the packet so we can populate the appropriate fields 
		 * and allocate the attributes.
		 */
		if ( udpPacket != null ) {
			SPacket.parse(udpPacket.getData());
		}
		else {
			SPacket = null;
		}
		
		return SPacket;
				
	}// sendSimpleMessage()
	
	/** sendMessageWithAttributes()
	 ** Sends a simple message with attributes.
	 ** Returns a string that contains the port and IP address
	 ** observed by the STUN server.
	 *  INPUTS: 
	 * 			a) Remote Server's Address
	 *			b) Remote Server's 
	 *			c) Attribute Type
	 *			d) Client's address (used to build attributes)
	 *			e) Client's port (used to build attributes)
	 *			f) Source port that the udp should originate.
	 *
	 * OUTPUTS: 
	 *			a)Returns a STUN Packet
	 */
	public STUNPacket sendMessageWithAttributes(String serverAddress, 
												String portNumber, 
												short attrType, 
												String ClientAddress, 
												String ClientPort, 
												int sourcePort){
		
		SizeOf s = new SizeOf();
		byte [] b_Attributes = null; // Array to hold attributes
		STUNClient SClient = new STUNClient();
		short mt = 1;						// Message type is request
        int p_id = SClient.generateID();  	// Get a random transaction ID
        int messagesize = 0;
		short attributeType = attrType;
		STUNPacket temp_SPacket = new STUNPacket();
		STUNPacket s_SPacket = new STUNPacket();
		
		/* Setup the attribute information, port-address family-IP address */
		String s_attributeTypeInfo = ClientPort+"1"+ClientAddress;
		
	    STUNPacket SPacket = new STUNPacket(mt,			// Message type is 1 (request)
											p_id,   	// Packet ID
											null,   	// Attribute
											(short)messagesize 	// Calculate packet size
											);  
		
		SPacket.DEBUG = DEBUG;
		/* Change IP and change Port requested 
		 * 3 is FLAGS
		 * 4 is change IP and port
		 * therefore 34 :-)
		 * Just like the joke with the two 6 year olds in front
		 * of an elevator, on of the turns to the other and says
		 * "I pressed 2 and 3, I'm sure this will be enough to take us to
		 * the fifth floor." :-)
		 */
		if ( attributeType == 34 ) {		
			byte [] av_MAV34 = 
					SPacket.makeAttributeValue((int)SPacket.CHANGE_IP);
																					
			SPacket.addAttribute(SPacket.FLAGS, 
		        				(short)s.SizeOf(av_MAV34 ), 
		        				av_MAV34 
		        				); 
		        				
		    // Recalculate STUN message size for this attribute		   
		    messagesize = (s.SizeOf(SPacket.messageAttributeType)
		    					+ s.SizeOf(SPacket.messageAttributeLength)
		    					+ s.SizeOf(av_MAV34)
		    					); 
		    
		    				
			av_MAV34 = SPacket.makeAttributeValue(SPacket.CHANGE_PORT);		    				
			SPacket.addAttribute(SPacket.FLAGS, 
		        				(short)s.SizeOf(av_MAV34), 
		        				av_MAV34
		        				);
		        				 
		   /* Recalculate STUN message size for this attribute plus the 
		    * previous value.
		    */	   
		    messagesize += (s.SizeOf(SPacket.messageAttributeType)
		    					+ s.SizeOf(SPacket.messageAttributeLength)
		    					+ s.SizeOf(av_MAV34)
		    					); 				

		}
		else if ( attributeType ==  SPacket.CHANGE_PORT ){ 
		    /* Change-Port only flag */
		    
			byte [] av_MAV32 = SPacket.makeAttributeValue(SPacket.CHANGE_PORT);			
			SPacket.addAttribute(SPacket.FLAGS, 
		        				(short)s.SizeOf(av_MAV32), 
		        				av_MAV32
		        				); 
		        				
		    // Recalculate STUN message size for this attribute		   
		    messagesize = (s.SizeOf(SPacket.messageAttributeType)
		    					+ s.SizeOf(SPacket.messageAttributeLength)
		    					+ s.SizeOf(av_MAV32));		
		    							
		}
		else if ( attributeType ==  SPacket.CHANGE_IP){ // Change-IP only flag
			byte [] av_MAV31 = SPacket.makeAttributeValue(SPacket.CHANGE_IP);			
			SPacket.addAttribute(SPacket.FLAGS, 
		        				(short)s.SizeOf(av_MAV31), 
		        				av_MAV31
		        				); 
		        				
		    // Recalculate STUN message size for this attribute		   
		    messagesize = (s.SizeOf(SPacket.messageAttributeType)
		    					+ s.SizeOf(SPacket.messageAttributeLength)
		    					+ s.SizeOf(av_MAV31));		
		    							
		}
		else { /* attribute is probably RESPONSE_ADDRESS */
			Integer i_ClientPort = new Integer(ClientPort);
			byte [] av_MAVra = SPacket.makeAttributeValue(
												i_ClientPort.shortValue(), 
												SPacket.mapped_Address_Family, 
												ClientAddress.getBytes());
												
			SPacket.addAttribute(attributeType, 
		        				(short)s.SizeOf(av_MAVra), 
		        				av_MAVra 
		        				);
		    
		    // Recalculate STUN message size for this attribute
		    messagesize = (s.SizeOf(SPacket.messageAttributeType)
		    					+ s.SizeOf(SPacket.messageAttributeLength)
		    					+s.SizeOf(av_MAVra)); 
		    								
		}
		
		SPacket.setMessageLength( (short) messagesize );
		UDPClient UDPC = new UDPClient(serverAddress, portNumber, 
										SPacket.getPacket());
		
		UDPC.DEBUG = DEBUG;
		/* parameteres in UDPC.sendPacket
		 * 		-attribute flag = 0, no flags
		 *		-retransmit	= 9, default
		 *		-sourcePort = user specified port from where the packet 	
		 *		              should be sent.
		 */									
		DatagramPacket udpPacket = UDPC.sendPacket(attrType, 9,sourcePort);
		
		/* If we haven't received anything from the server it may be because
		 * we are not allowed to route UDP packets. 
		 */
		if ( udpPacket != null ) {
			s_SPacket.parse(udpPacket.getData());
		}
		else {
			s_SPacket = null;
		}
		return s_SPacket;							
							
	}// sendMessageWithAttributes()
	
	/** Parses a STUN packet and returns a strng which contains the mapped 
	 **  address in the form of ip:port e.g. 127.0.0.1:1221
	 */
	public String getMappedAddress(STUNPacket sp){
		SizeOf s = new SizeOf();
		int ix = 0;
		int avLength = 0; /* Attribute Value Length, not including type and 
						   * length.
						   */
		int ofst = 0;
		short attributevaluePort = 0;
		byte attributeValueFamily = 0;
		String attributeValueIP = null;
		
		ByteBuffer bb_attributes = ByteBuffer.allocate(
											s.SizeOf(sp.messageAttributes));
											
		ByteBuffer bb_attribute = null;
		byte []b_attributeValue = null;
			
			
		if (DEBUG == true) {
			System.out.println("STUN Packet Attributes in byte format "
													+ sp.messageAttributes);
		}
		
		if ( sp.messageAttributes != null ) {
			bb_attributes.put(sp.messageAttributes);
		    bb_attributes.rewind();
		}
		else {
			return "No Address Retrieved";
		}
		
		/* Search the STUN Packet attributes for a MAPPED_ADDRESS attribute */
		while ( ix < s.SizeOf(sp.messageAttributes) ) { 
			
			/* Set the position of the ByteBuffer to the beginning of the
			 * attribute.
			 */
			bb_attributes.position(ix);
			
			/* Get the attribute Type */
			bb_attributes.get(sp.messageAttributeType,
								0,
								s.SizeOf(sp.messageAttributeType));
												
			ByteBuffer bb_mAT = ByteBuffer.allocate(
								s.SizeOf(sp.messageAttributeType));
								
			/* Store the attribute Type for further processing. */
			bb_mAT.put(sp.messageAttributeType);				
	        ix += s.SizeOf(sp.messageAttributeType);					
			
			/* Get the attribute value length as inidcated in the attribute 
			 * header. 
			 */
			avLength = bb_attributes.getShort(ix);	
			ix +=  s.SizeOf(sp.messageAttributeLength);

		    if ( bb_mAT.getShort(0) == sp.MAPPED_ADDRESS ) { 
		    	b_attributeValue = new byte[avLength];
		    	bb_attributes.position(ix);				    	
				bb_attributes.get(b_attributeValue, 0,avLength);
				bb_attribute = ByteBuffer.allocate(avLength);
				if ( b_attributeValue != null ) {
					bb_attribute.put(b_attributeValue);
				} else {
					continue ;
				}
				attributevaluePort = bb_attribute.getShort(0);
				attributeValueFamily = bb_attribute.get(2);				
				attributeValueIP = new String (bb_attribute.array(),
												3,
												(avLength-3));							
				return ( attributeValueIP +":"+sp.unsigned2signed(
													attributevaluePort) );
		    } // if
			ix += avLength;
		}
		return ( attributeValueIP +":"+sp.unsigned2signed(attributevaluePort));
	} // getMappedAddress


	/** Parses a STUN packet and returns  the changed address as a string
	 ** in the form of ip:port e.g. 127.0.0.1:1221
	 */
	public String getChangedAddress(STUNPacket sp){
		SizeOf s = new SizeOf();
		int ix = 0;
		int avLength = 0; 	/* Attribute Value Length, not including type and 
							 * length.
							 */
		int ofst = 0;
		short attributevaluePort = 0;
		byte attributeValueFamily = 0;
		String attributeValueIP = null;
		
		ByteBuffer bb_attributes = ByteBuffer.allocate(
												s.SizeOf(sp.messageAttributes));
		ByteBuffer bb_attribute = null;
		byte []b_attributeValue = null;
									   
		if ( sp.messageAttributes != null ) {
			bb_attributes.put(sp.messageAttributes);
		    bb_attributes.rewind();
		}
		else {
			return "No Address Retrieved";
		}
		
		/* Search the STUN Packet attributes for a MAPPED_ADDRESS attribute */
		while ( ix < s.SizeOf(sp.messageAttributes) ) { 
			
			/* Set the position of the ByteBuffer to the beginning of the
			 * attribute.
			 */
			bb_attributes.position(ix);
			
			/* Get the attribute Type */
			bb_attributes.get(sp.messageAttributeType,
								0,
								s.SizeOf(sp.messageAttributeType)
								);
													
			ByteBuffer bb_mAT = ByteBuffer.allocate(
								s.SizeOf(sp.messageAttributeType));
			/* Store the attribute Type for further processing. */
			bb_mAT.put(sp.messageAttributeType);				
	        ix += s.SizeOf(sp.messageAttributeType);					
			
			/* Get the attribute value length as inidcated in the attribute 
			 * header. 
			 */
			avLength = bb_attributes.getShort(ix);	
			ix +=  s.SizeOf(sp.messageAttributeLength);

		    
		    if ( bb_mAT.getShort(0) == sp.CHANGED_ADDRESS ) { 
		    	b_attributeValue = new byte[avLength];
		    	bb_attributes.position(ix);				    	
				bb_attributes.get(b_attributeValue, 0,avLength);
				bb_attribute = ByteBuffer.allocate(avLength);
				if ( b_attributeValue != null ) {
					bb_attribute.put(b_attributeValue);
				} else {
					continue ;
				}
				attributevaluePort = bb_attribute.getShort(0);
				attributeValueFamily = bb_attribute.get(2);				
				attributeValueIP = new String (bb_attribute.array(),
												3,
												(avLength-3)
												);
												
				return ( attributeValueIP 
							+":"
							+sp.unsigned2signed(attributevaluePort) );
		    } // if
			ix += avLength;
		}
		return ( attributeValueIP +":"+sp.unsigned2signed(attributevaluePort) );
	} // getChangedAddress	


} // End of STUNClient()
/************************* END OF FILE *******************************/