/* @(#)STUNd.java        0.01 2002/02/08
 * STUNServer listens for incoming STUN requests on the default port 1221
 * unless is specified otherwise at start up.  
 *  
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
 *
 */


import java.io.*;
import java.nio.*;
import java.net.*;
import java.util.*;
import gnu.getopt.Getopt;
import gnu.getopt.LongOpt;

public class STUNd {
	
	/** usage()
	 * Displays the command line use of the server.
	 */
	public static void usage() {
		System.out.println("STUNServer <options>");
		System.out.println("	[-p] Set the local server port.");
		System.out.println("	[-s] Indicate another STUN server to "
															+"communicate.");
															
		System.out.println("	[-r]  	  remote STUN server port to "
															+"communicate.");
		System.out.println("	[-d] Generate debbuging messages.");
		System.out.println("	[-h] Help, this listing.");
	}
	
	public static void main(String []args) {
	
	String changeIP_address = "192.168.1.1";/* Used with the "change-ip" flag 
											 * for testing.
											 */
											 
	String remoteSTUNserver	= "127.0.0.1";	/* Another STUN server to exchange 
											 * messages. 
											 */
											 
	int remoteSTUNserverPort = 1221;		/* Default */
	SizeOf s = new SizeOf();
	int indx = 0; 							/* Used for indexing a byte array. */
	
	int ofst = 0;							/* Used to mark offset in a byte 
											 * array. 
											 */
		
	boolean DEBUG  = false; 				/* Used to turn on DEBUGGING 
											 * messages.
											 */	
	boolean DISCARD = false; 				/* If the flags is set based on the
											 * evaluation of the attributes then 
											 * the STUN message is discarded.
											 */
	
	boolean CHANGEIP = false;				/* If change IP is requested by the client this flag
											 * is set to true and it ais in 
											 * stoping further processing of 
											 * attributes in a STUN message. 
	                                         */
	  
	boolean CHANGEPORT = false;
	                                         
	InetAddress inet_destinationAddress = null;  /* Destination address may 
												  * be aclient or a server. 
												  */
	int i_destinationPort = -1;
	
	STUNd STUNserver = new STUNd();
											 
	/* Data type declerations that are used to set up the UDP server */
	DatagramSocket   	ds_sock = null;
	DatagramPacket   	dg_responsePacket = null;
	DatagramPacket   	dg_requestPacket = null;
    STUNPacket 			STUNServerResponse = null;
	
	int 	serverPort 	= 1221; 			/* Default server port 
											 *(STUN) S=19, T=20, U=21, N=14, 
											 * takes the first digit from the 
											 * letter translation 1221. 
										     */
	
	int 	serverSourcePort = serverPort;  /* Sets the source port of the 
											 * response when a client request 
											 * has the "change-port"
								   			 * attribute set.
								   			 */
								   
	String serverAddress = null; 			/* holds the STUN server IP address 
											 * it can be used later if we need 
											 * to bind to a specific IP address.	
											 */
							     
	byte[]	buffer	= null;
	byte[] messageAttribute = null;	/*  Byte array to hold a message attribute 
								     *  for parsing.
								     */
								     
	int BUFFERSIZE = 1024;  		// Default buffer size to hold packet data

	int times = 0; 	// Used to restrict the amount of times a change-port attribute
					// is translated.
					
	int c; // Used for parsing the command line arguments
	
	Getopt options = null;
	try {
		options = new Getopt("STUNd", args, "p::s::d::r::h::");
	} catch (MissingResourceException mr){
		mr.printStackTrace();
		usage();
		System.exit(0);
	} // try
	
	try {
		while ( (c = options.getopt()) != -1) {
			switch (c) {
				case 's': // Remote STUN Server address
						remoteSTUNserver = options.getOptarg();
						if ( STUNserver == null ) {
							usage();
						}
						break;
				case 'p': // Local STUN Server port
					    String s_serverPort = options.getOptarg();
					    if ( s_serverPort != null ){
					    	serverPort = Integer.parseInt(s_serverPort);
					    }
						else {
							usage();
							System.exit(0);
						}
						
						if ( (serverPort < 0) || (serverPort > 65534) ) {
							usage();
							System.exit(0);
						}
						break;
				case 'r': // Remote server port
						remoteSTUNserverPort = Integer.parseInt(
														options.getOptarg());
						break;
				case 'd': // DEBUG messages turned on
						System.out.println("Debugging messages turned on.");
						DEBUG = true;
						break;
				case 'h': // help
						usage();
						System.exit(0);
				default:
					// Run with default parameters		
			} // switch
			
		} // while - parsing command line arguments
	} catch (NullPointerException np) {
		usage();
		System.exit(0);
	} //try
		
		buffer = new byte[BUFFERSIZE]; 
		dg_requestPacket = new DatagramPacket( buffer,  BUFFERSIZE);
		
		/* Setup UDP socket for incoming connections. */
		try {
        	ds_sock = new DatagramSocket(serverPort);
        }  catch (BindException be){
        	System.out.println("Port ["+serverPort+"] may be used by another "
        														+" service.");
        	System.out.println("Please select another port using the -p option.");
        	System.exit(0);
        }catch (SocketException se) {
        	 
                System.out.println("Datagrams socket Exception: " 
                					+ se.getMessage());
                se.printStackTrace();
        }
        
        try {
        	serverAddress = ds_sock.getLocalAddress()
        							.getLocalHost().getHostAddress();
        							
        	System.out.println("["+serverAddress 
        								+"] STUN Server listening on port " 
        								+serverPort);
        								
        } catch (UnknownHostException uh) { 
        	System.out.println("Unknown Host "+uh.getMessage()+"\n");
       		uh.printStackTrace();
       	}
	
	
	    if (DEBUG) {
	    	System.out.println("Remote server configured at "
							    				+remoteSTUNserver
							    				+":"
							    				+remoteSTUNserverPort);
	    }
		
		/* Listen for client requests */
		while (true) {
			
			/* Initialize the DISCARD flag to false before 
			 * accepting any new connections.
			 */
			 
			DISCARD = false; 
			CHANGEIP = false;
			CHANGEPORT = false;
			times = 0;
			
	        try {
		        ds_sock.receive( dg_requestPacket );
	        } catch (IOException ioe) {
	                System.out.println("Receive error: " + ioe.getMessage());
	                ioe.printStackTrace();
	        }
	        
	        if (DEBUG) {
	        	System.out.println("UDP request from ["
	        		+(dg_requestPacket.getAddress()).getHostAddress()
	        		+":"+dg_requestPacket.getPort()
	        		+"]\n");
	        	System.out.println("Packet Data "+dg_requestPacket.getData());
	        }
	        
	        String data = new String(dg_requestPacket.getData());          
	        
	      /*
	       * Create a STUN packet instance for message exchange with clients
	       * and set it's debug flag.
	       */
	       STUNPacket STUNClientRequest = new STUNPacket();
	       STUNClientRequest.DEBUG = DEBUG; 
	       
	       /* Parse the received packet */
	       STUNClientRequest.parse(dg_requestPacket.getData()); 
	       
	       if (DEBUG) {
	       	    System.out.println("Client STUN Request Header");
	        	STUNClientRequest.printHeader();
	        	System.out.println("Client STUN Request Payload");
        		STUNClientRequest.printPayload(
        								STUNClientRequest.messageAttributes);
	       }
	          
	       /* Gather IP information about the client. This
	        * information will be usefull in responses sent to the client.
	        */
	       String clientIP = dg_requestPacket.getAddress().getHostAddress();	            									
		   int clientPort = dg_requestPacket.getPort();
		   InetAddress clientAddress = dg_requestPacket.getAddress();
		   inet_destinationAddress = clientAddress;
		   i_destinationPort = clientPort;    
		   
	       /* Print the transaction ID and client IP address
	        * for auditing purposes.
	        */
	       System.out.println("STUN Request ["
	       					+STUNClientRequest.getTransactionID()
	       					+"] from "
	       					+(dg_requestPacket.getAddress()).getHostAddress()
	       					+":"+dg_requestPacket.getPort());
	              
	        
	        /* Identify whether the client request is:
	         * 	a) STUN Request without RESPONSE-ADDRESS attribute
	         *  b) STUN Request with RESPONSE-ADDRESS attribute
	         *  c) STUN Request with the Discard Flag set
	         *  d) STUN Request with the Change Port set
	         *  e) STUN Request with the Change IP set
	         *
	         */
	        String s_MType = new String(STUNClientRequest.getMessageType()); 
	        String s_MTRequest = new String(
	        						STUNClientRequest.getMessageTypeRequest());
	        indx = 0;		
	        if (s_MType.compareTo(s_MTRequest) == 0) {            
	            //if (STUNClientRequest.messageAttributes != null ) {
	            	
	            	/* Check the fields of all the available attributes */


	            ByteBuffer bb_attributes = ByteBuffer.allocate(
	            				s.SizeOf(STUNClientRequest.messageAttributes));
	            
	            if ( STUNClientRequest.messageAttributes != null ){
            		bb_attributes.put(STUNClientRequest.messageAttributes);
            		ofst = s.SizeOf(STUNClientRequest.messageAttributes);
            		messageAttribute = new byte[ofst];
            		bb_attributes.rewind();
            		bb_attributes.position(indx);
            		bb_attributes.get(messageAttribute,0,ofst);

	            	
		            while (indx < s.SizeOf(STUNClientRequest.messageAttributes)) {
		            	
		            	/* Extract a single attribute from the attributes array for 
		            	 * further processing.
		            	 */
		          		
		            	String s_mAT = new String(messageAttribute,
		            							indx ,
		            							s.SizeOf(STUNClientRequest
		            									.messageAttributeType)
		            							);
		            	
		            	STUNClientRequest.messageAttributeType = s_mAT.getBytes();
		            	indx  += s.SizeOf(STUNClientRequest.messageAttributeType);
		            	
		            	String s_mAL = new String(messageAttribute,
		            							indx ,
		            							s.SizeOf(STUNClientRequest.messageAttributeLength)
		            							);
		            							
		            	STUNClientRequest.messageAttributeLength = s_mAL.getBytes();
		            	ByteBuffer bb_mAL = ByteBuffer.allocate(s_mAL.length());
		            	bb_mAL.put(s_mAL.getBytes());
		            	indx += s.SizeOf(STUNClientRequest.messageAttributeLength);
		            				            
			            /* Check if "discard" bit is set in the attribute FLAG */
						ByteBuffer bb_mAT = ByteBuffer.allocate(
											s.SizeOf(STUNClientRequest.messageAttributeType));
										
						bb_mAT.put(STUNClientRequest.messageAttributeType);
		

						/* Check if the attribute type is FLAGS */
			            
			            if (bb_mAT.getShort(0) == STUNClientRequest.FLAGS) {
							ByteBuffer bb_mA = ByteBuffer.allocate(4);
							String s_mA = new String(messageAttribute,4,4);
							bb_mA.put(s_mA.getBytes());
							bb_mA.rewind();
							int i_mAF = 0; /* Used to store the type of attribute
											* for comparison.
											*/
							
							/* Check that we are not trying to parse an null string */				
							try {								
								//i_mAF = Integer.parseInt(s_mV);	
								i_mAF = bb_mA.getInt(0);		            	
			            	}catch (NumberFormatException nfe) {
			            		if (DEBUG) {
			            			nfe.printStackTrace();
			            		}
			            	}
			            	
			            	/* Check if the "discard" bit is set */    
			            	if ( i_mAF == STUNClientRequest.DISCARD  ) {
			            		System.out.println("STUN Request ["
			            				+STUNClientRequest.getTransactionID()
			            				+"] from "
			            				+(dg_requestPacket.getAddress()).getHostAddress()
			            				+ " was discared.");
			            		DISCARD = true;
			            	} // discard if
			            	
			            	
			            	if (DISCARD == true) {
		 						break;
		 					}
			            	
			            	/* Check if "change port" is set */
			            	/* Times is used to restrict the number of times a change 
			            	 * port attribute is interpreted. A STUN message should
			            	 * contain one change-port attribute at a time.
			            	 */
			            			
			            	if ( (i_mAF == STUNClientRequest.CHANGE_PORT) && (times < 1) ) {
			            		CHANGEPORT = true;
			            		/* If so set the flag so we can configure the packet */
								
								/* Generate a random port between 4000 and 5000-
								 * We will need some verification here to avoid conflicts
								 * with services running on existing ports.
								 */
							    Random r_port = new Random();
		    					Integer i_port = new Integer(r_port.nextInt(5000));
							    while ( (i_port.intValue() < 4000) ){
									r_port = new Random();
									
									/* Ensure that the new port is not the same as the
									 * port that we received the response (per STUN proposal)
									 */
									if (serverPort != i_port.intValue()) {
		    							i_port = new Integer(r_port.nextInt(5000));
		    							times = 1;
		    							break;
									} else 
										continue;
		    					} // while	
		    					
		    					System.out.println("STUN Request ["
			            				+STUNClientRequest.getTransactionID()
			            				+"]"
			            				+" Change Port Requested, changed to : "
			            				+i_port.intValue());		            		
			            	
			            		// Set the destination port of the response.
			            		serverSourcePort = i_port.intValue();
			            		
			            	} // "change port" if 
		
			            	/* Check if "change IP" is set */
			            		            	
			            	if ( i_mAF == STUNClientRequest.CHANGE_IP ) {
			            		CHANGEIP = true;
			            		/* Try to contact another STUN server.
			            		 * if none has been configured, then spoof the 
			            		 * IP address in the response message.
			            		 * This may not work well in some environments where
			            		 * IP spoofing is not permited.
			            		 */

			            		
		            			// Send a request to the configured server
			        			
			        			Byte b_MAFamily = new Byte(
			        								STUNClientRequest.mapped_Address_Family);
			        								
			        			int i_messageLength = 0;

		    					System.out.println("STUN Request ["
			            				+STUNClientRequest.getTransactionID()
			            				+"]"
			            				+" Change Address Requested. Forwarding request to "
			            				+remoteSTUNserver
			            				+":"+remoteSTUNserverPort);			            			
		            			
		            			
		            			/* Create the STUN packet containing the request */
		                		STUNServerResponse =   new STUNPacket
											        (STUNClientRequest.messageTypeRequest,
												   	STUNClientRequest.getTransactionID(),
											   		(short) i_messageLength 
													);
		            			
		            			
		            			/* Create an attribute value with the RESPONSE_ADDRESS set
		            			 * so the remote STUN server can respond. 
		            			 */
    							byte [] av_MAV = STUNServerResponse.makeAttributeValue((short)clientPort,
												STUNClientRequest.mapped_Address_Family,
												clientIP.getBytes()
												);
		            			
		            			/* Add the attribute to the STUN request. */
								STUNServerResponse.addAttribute(STUNClientRequest.RESPONSE_ADDRESS, 
		        						(short)(s.SizeOf(av_MAV)),
		        						av_MAV
		        						);
		            			
		            			/* Calculate the size of the STUN packet except the
						         * 64-bit header, which consists of:
						         *		-Type of attribute (16-bit)
						         *		-Length of attribute (16-bit)
						         *		-Value (variable)
						         */						
						        i_messageLength = s.SizeOf(STUNServerResponse.messageAttributes);
				
				                /* Reset message length to reflect new additions*/
								STUNServerResponse.setMessageLength((short)i_messageLength ); 												 					            			
		            			
		            			dg_responsePacket = new DatagramPacket(STUNServerResponse.getPacket(), 
											s.SizeOf(STUNServerResponse.getPacket()), 
											inet_destinationAddress, 
											i_destinationPort
											);  
		            			
		            			/* Overwrite the remote STUN server's IP and port
		            			 * to send the packet.
		            			 */
		            			try {
		            				// Set the destination IP of the request
		            				if ( remoteSTUNserver != null ){ 
		            					dg_responsePacket.setAddress(InetAddress
			            	    						.getByName(remoteSTUNserver));
		            				} else { // Spoof the IP address
		            					dg_responsePacket.setAddress(InetAddress
			            	    						.getByName(changeIP_address));
		            				}
		            				
		            				// Set the destination port of the request.
		            				dg_responsePacket.setPort(remoteSTUNserverPort);
		            				
		            			} catch (UnknownHostException uh) { 
						        	System.out.println("Unknown Host "+uh.getMessage());
						        	uh.printStackTrace();
						        }
						        
								
		            			
					            /* UDP request to the remote STUN server */
								try {

									String packetData = new String(dg_responsePacket.getData());
				
									if ((DEBUG == true)) {
									System.out.println("Sending response to "
														+dg_responsePacket.getAddress().getHostAddress()
														+":"+dg_responsePacket.getPort());
									System.out.println("Data "+ packetData);
									}
						        	
						        	ds_sock.send(dg_responsePacket);
								} catch (IOException ioe) {
					                System.out.println("Send error: " + ioe.getMessage());
					                ioe.printStackTrace();
					        	}
		            			
		            			continue; 
			            		
			            	} // "change IP" if 
			            	 
			            } // if - type FLAGS 
			            indx  += bb_mAL.getShort(0);	            
				     	        	        
				        /* Construct STUN response packet
				         * First the Attribute Fields (port, family, IP address)
				         */
				        Integer i_ClientPort = new Integer(clientPort);
				        Byte b_MappedAddressFamily = new Byte(STUNClientRequest.mapped_Address_Family);
				        int i_messageLength = 0;
				        String s_MessageAttributeValue = null;
		
		  				/* Create the STUN packet containing the response */
		                STUNServerResponse =   new STUNPacket
											        (STUNClientRequest.messageTypeResponse,
												   	STUNClientRequest.getTransactionID(),
											   		(short) i_messageLength 
													);
		        
		                
		                			
		 				/* Check if the client has requested to send
		 				 * the STUN response to alternate destination 
		 				 * (using the RESPONSE-ADDRESS attribute).
		 				 */ 
									
		 				try {
		 					/* RESPONSE-ADDRESS is set, update the IP and port destination 
		 					 * fields in the UDP packet.
		 					 */
			 				if ( bb_mAT.getShort(0) ==  STUNClientRequest.RESPONSE_ADDRESS ){
			 					
			 					/* Extract the values in the "Value" field and 
			 					 * construct the UDP packet using the specified
			 					 * IP address and port.
			 					 */
			 					
			 					try {
			 						inet_destinationAddress = InetAddress.getByName(STUNClientRequest.getResponseIP(STUNClientRequest)); 
			 					} catch (UnknownHostException uh) { 
						        	System.out.println("Unknown Host "+uh.getMessage()+"\n");
						        	uh.printStackTrace();
						        }
			 			        i_destinationPort = STUNClientRequest.getResponsePort(STUNClientRequest);
			 			        System.out.println("STUN Request ["+STUNClientRequest.getTransactionID()+"]"
			 			        					+" from "
			 			        					+(dg_requestPacket.getAddress()).getHostAddress()
			 			        					+":"+dg_requestPacket.getPort()
			 			        					+" with RESPONSE-ADDRESS to "
			 			        					+STUNClientRequest.getResponseIP(STUNClientRequest)
			 			        					+":"+STUNClientRequest.getResponsePort(STUNClientRequest));
			 			        
			 			        try {					
			 						inet_destinationAddress = InetAddress.getByName(
			            	    						STUNClientRequest.getResponseIP(STUNClientRequest));
			 			        } catch (UnknownHostException uh) {
			 			        	if (DEBUG == true) {
			 			        		uh.printStackTrace();
			 			        	}
			 			        }
			 			        
			            	    clientIP = 	STUNClientRequest.getResponseIP(STUNClientRequest);					
			 					i_destinationPort = STUNClientRequest.getResponsePort(STUNClientRequest);
			 				} //if - RESPONSE-ADDRESS

		 					 					
		 					/* Else populate the attribute field with the port, family and IP
		 					 * that the client was identifed.
		 					 */
		 					 
		 					 /* Create the attribute */
							byte [] av_ma = STUNServerResponse.makeAttributeValue((short)i_destinationPort,
												STUNClientRequest.mapped_Address_Family,
												clientIP.getBytes()
												);
																											
							/* Add the attribute to the STUN response. */
							STUNServerResponse.addAttribute(STUNClientRequest.MAPPED_ADDRESS, 
			        						(short)(s.SizeOf(av_ma)),
			        						av_ma
			        						);
		 				
		 				
		 				} catch (NullPointerException np){
		 					np.getMessage();
					    	np.printStackTrace();
		 				} //try
		 			
		 			} // while - more attributes in messageAttributes[]			
	            } // if - not empty attribute byte array
	            
	 			/* Check if this packet had the discard flag set before 
	 			 * continuing to process. If so break out the for
	 			 * loop and stop processing the STUN message further.
	 			 */
	 			if (DISCARD == true) {
	 				continue;
	 			}
	 			
	 			/* Check if the server has contacted another STUN server
	 			 * and if so continue to listen for new requests without
	 			 * sending a response.
	 			 */	 				 			
	 			if (CHANGEIP == true) {
	 				continue;
	 			}

	 			if (CHANGEPORT == true) {
	 				serverPort = serverSourcePort ;
	 			}
	 			
	 			
	 			/* Make sure we assign the proper port in the MAPPED_ADDRESS
	 			 * attribute value. 
	 			 *
	 			 */
	 			Integer i_CPort = null;	 			
	 			if ( i_destinationPort != -1 ) {
	 				i_CPort = new Integer(i_destinationPort);
	 			}else {
	 				i_CPort = new Integer(clientPort);
	 			}

	 			
			    Byte b_MAFamily = new Byte(STUNClientRequest.mapped_Address_Family);
			    
	 			/* Initialize the message length */
	 			int i_messageLength = 0;
	 			
	 			/* Create the STUN packet containing the response */
		        STUNServerResponse =   new STUNPacket
										(STUNClientRequest.messageTypeResponse,
										STUNClientRequest.getTransactionID(),
										(short) i_messageLength 
										);
				STUNServerResponse.DEBUG = DEBUG;
		
				/* Create the attribute */				
				byte [] av_MAV = STUNServerResponse.makeAttributeValue(i_CPort.shortValue(),
																STUNClientRequest.mapped_Address_Family,
																clientIP.getBytes()
																);
										
				/* Add the attribute to the STUN response. */
				STUNServerResponse.addAttribute(STUNClientRequest.MAPPED_ADDRESS, 
        						(short)(s.SizeOf(av_MAV)),
        						av_MAV);
        						
        		/* Add the SOURCE-ADDRESS attribute as described in the STUN proposal */
					   		   
				byte []atrval = STUNServerResponse.makeAttributeValue((short)serverPort, 
																	STUNClientRequest.mapped_Address_Family,
																	serverAddress.getBytes()
																	);
									   
 		 		STUNServerResponse.addAttribute(STUNClientRequest.SOURCE_ADDRESS, 
        						(short)(s.SizeOf(atrval)),
        						atrval
        						);
        		
        		
        		
        		/* Add the CHANGED-ADDRESS attribute */
				atrval = STUNServerResponse.makeAttributeValue((short)remoteSTUNserverPort, 
													STUNClientRequest.mapped_Address_Family,
													remoteSTUNserver.getBytes()
													);
									   
 		 		STUNServerResponse.addAttribute(STUNClientRequest.CHANGED_ADDRESS, 
        						(short)(s.SizeOf(atrval)),
        						atrval
        						);		
        						       						
 				 /* Calculate the size of the STUN packet except the
		         * 64-bit header, which consists of:
		         *		-Type of attribute (16-bit)
		         *		-Length of attribute (16-bit)
		         *		-Value (variable)
		         */						
		        i_messageLength = s.SizeOf(STUNServerResponse.messageAttributes);

                /* Reset message length to reflect new additions*/
				STUNServerResponse.setMessageLength((short)i_messageLength ); 
				
 				
				/* Encapsulate the STUN header in the UDP packet */
				dg_responsePacket = new DatagramPacket(STUNServerResponse.getPacket(), 
											s.SizeOf(STUNServerResponse.getPacket()), 
											inet_destinationAddress, 
											i_destinationPort
											); 
		           
		            
                /* Parse the server's constructed packet. Aids in debugging. */              		       
		        if ((DEBUG == true)) {
		        	System.out.println("Parsing Server packet");              
                	STUNServerResponse.parse(STUNServerResponse.getPacket());
                  	System.out.println("DONE - Parsing Server packet");
		        	System.out.println("Response sent to client.");
		        	STUNServerResponse.printHeader();
					STUNServerResponse.printPayload(STUNServerResponse.messageAttributes);		
		        } 
				
				/* UDP response to the client */
				try {
					dg_requestPacket = dg_responsePacket ;
					String packetData = new String(dg_requestPacket.getData());

					if ((DEBUG == true)) {
					System.out.println("Sending response to "+inet_destinationAddress+":"+i_destinationPort);
					System.out.println("Data "+ packetData);
					}
		        	
		        	ds_sock.send(dg_requestPacket);
				} catch (IOException ioe) {
	                System.out.println("Send error: " + ioe.getMessage());
	                ioe.printStackTrace();
	        	}
	        

	        
	        } // if Request
	        
		} // while

	} // End of main()
	
} // End of STUNd
/************************* END OF FILE *******************************/
