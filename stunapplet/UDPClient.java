/*  @(#)UDPClient.java        0.01 2002/02/08
 *  UDPClient sends a udp packet to a UDP server at a specific port. 
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
import java.net.*;
import java.io.*;
import java.util.*;


/** UDPClient
 *  Sends and receives UDP packets from a UDP server.
 *
 */
public class UDPClient {

	boolean DEBUG = false; 			/* Used to turn on DEBUGGING 
									 * messages.
									 */ 
											 
	static DatagramSocket socket; 		// UDP Socket
	static DatagramPacket packet; 		// communications packet
	
	int BUFFERSIZE = 256;  					/* Default buffer size to hold 
											 * packet data.
											 */
											 
	byte[] message = new byte[BUFFERSIZE]; 	// message buffer
	
	byte[] in_buffer = new byte[BUFFERSIZE];/* Buffer used to store receiving 
											 * messages.
											 */
											 
	String remoteServer;					/* Supplied remote server name as 
											 * a string, it will be converted 
											 * to InetAddress below in 
											 * DatagramPacket() method. 
									   		 */
								
	String remotePort;						/* Supplied remote server port as 
											 * a string, it will be converted 
											 * to integer below in 
											 * DatagramPacket() method. 
									  		 */

   int localPort = 0;						/* Local port. used for comparison */
   int sockettimeout	= 100; 				/* Default timeout value 100ms */
   int limittimeout 	= sockettimeout * 16;
	
	/** Simple UDPClient() constructor
	 */
	public UDPClient() { } 
	
	/**
	 * UdpClient() Constructor with additional arguments
	 * remoteServer: is the remote server's address in a String form
	 * remotePort  : is the remote server's port in a String form
	 * message     : is the message to be send to the server in a byte array
	 */
	public UDPClient (String remoteServer, String remotePort, byte[] message) {
		this.remoteServer = remoteServer;
		this.remotePort = remotePort;
		this.message = message;
		
	} //Constructor

	/**
	 * sendPacket()
	 * Sends a packet to a UDP server (address is supplied in 
	 * the udpServer variable)  at port udpPort.
	 * Inputs:
	 * Outputs:
	 */
	public DatagramPacket sendPacket(int i_attributeFlagsInfo, 
										int retransmit, 
										int sourcePort) {
											
		InetAddress serverAddress = null;	
	
		try {
			serverAddress = InetAddress.getByName(remoteServer);
		} catch (UnknownHostException uhe){
			System.out.println("UDP CLIENT ERROR :"
										+uhe.getMessage()
										+" when attempting to resolve " 
										+ remoteServer+"\n");
			uhe.printStackTrace();
		}

		if (DEBUG) {
			System.out.println("UDP CLIENT Message "+this.message);
			System.out.println("UDP CLIENT Message Length "+this.message.length);
		}
		
		
		/* spacket contains data destined for the server */
		DatagramPacket spacket = new DatagramPacket(this.message, 
													this.message.length, 
													serverAddress, 
													Integer.parseInt(remotePort)
													); 
		
		try {
			/* Check if use has requested to send
			 * the packet from a specific port.
			 */
			if (sourcePort != 0 ) {  
			    try {
					socket = new DatagramSocket(sourcePort);	
			    } catch (BindException be) {
			    	if (DEBUG){
			    		be.printStackTrace();
			    	}
			    	/* Let the system pick a port */
			    	socket = new DatagramSocket();
			    }
			    
				if (DEBUG) {
					System.out.println("UDP CLIENT Sending packet from port "
														+socket.getLocalPort());
				}
			}
			socket.send(spacket);
		    localPort = socket.getLocalPort();	
		} catch (IOException ioe) {
			System.out.println("UDP CLIENT ERROR "
									+ioe.getMessage()
									+" when attempting to send UDP packet.\n");
			ioe.printStackTrace();
		} 
		
		/* Receive response */
		DatagramPacket cpacket = new DatagramPacket(in_buffer, (BUFFERSIZE));
		if (DEBUG) {
			System.out.println("UDP CLIENT Waiting for response from remote "
										+"server ["
										+serverAddress
										+"]\n");
			
			try{
				System.out.println("UDP CLIENT Local IP ["
						+socket.getLocalAddress().getLocalHost().getHostAddress()
						+"] Port "
						+socket.getLocalPort());	
			}catch (UnknownHostException uhe){
				System.out.println("UDP CLIENT ERROR :"+uhe.getMessage()
										+" when attempting to resolve " 
										+ remoteServer+"\n");
				uhe.printStackTrace();
			}
		}
		
		int count = 0;
		while ( count < retransmit ){
			try {
					
				socket.setSoTimeout (sockettimeout); // Start the timer
				
				/* Temporary STUN packet used to compare flags. */
				STUNPacket t_SP = new STUNPacket();
				 
				if ( i_attributeFlagsInfo == t_SP.DISCARD){
					/* If the user has set the discard flag there
					 * is no need to go furter, so we terminate.
					 */
					System.exit(0);	
				}
				socket.receive(cpacket);
			} catch (IOException ioe) {
				if (DEBUG) {
					System.out.println("UDP CLIENT ERROR "
										+ioe.getMessage()
										+" when attempting to receive "
										+"UDP packet.\n");
										
					ioe.printStackTrace();
				}
				if ( sockettimeout <= limittimeout ) {
					sockettimeout += sockettimeout;
				}
				count += 1;
				try{
					socket.send(spacket);
				} catch (IOException ie) {
					System.out.println("UDP CLIENT ERROR "
									+ie.getMessage()
									+" when attempting to send UDP packet.\n");
									
					ie.printStackTrace();
				} 
			 	continue;
			}
            break;
		} // while

        // Safety checking
        if ( count >= retransmit ){
        	cpacket = null;
        }
				
		if (DEBUG) {
			if (cpacket != null ){
				String s_dbg = new String(cpacket.getData());
				SizeOf s = new SizeOf();	
				System.out.println("UDP Client Packet "+s_dbg);
				System.out.println("UDP Client Packet Size "
												+s.SizeOf(cpacket.getData()));
			}
		}
		
		socket.close();
				
		/* return response for further processing by the calling class */
		return cpacket; 
		
	} // End of sendPacket()


    /** getLocalIP{), returns a String
     *  Returns a string containing the local IP address of
     * the client's socket.
     */
	public String getLocalIP () {
		String s = null;
		try{
			s = new String(this.socket.getLocalAddress().getLocalHost().getHostAddress());
		}catch (UnknownHostException uhe){
			System.out.println("UDP CLIENT ERROR :"
											+uhe.getMessage()
											+" when attempting to resolve " 
											+ remoteServer+"\n");
			uhe.printStackTrace();
		}
		return s;
	} // getLocalIP()

	/** getLocalPort(), returns an int
	 *  Returns the local port that the client's UDP socket is bound.
	 */
	public int getLocalPort() {
		return localPort;
	}

} // End of UDPClient.java
/************************* END OF FILE *******************************/