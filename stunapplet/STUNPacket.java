/*  @(#)STUNPacket.java        0.01 2002/02/08
 *  STUNPacket defines the structure of a STUN packet used by the 
 *  STUN server or client to communicate. 
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
import java.lang.*;
import java.net.*;

public class STUNPacket {
	
	SizeOf s = new SizeOf();
	
	boolean DEBUG = false; 					/* Used to turn on/off DEBUGGING 
											 * messages.
											 */ 
	
	/* Some notes for various types of Java literals
	 * For byte, from -128 to 127, inclusive, 8-bits 
     * For short, from -32768 to 32767, inclusive, 16-bits 
     * For int, from -2147483648 to 2147483647, inclusive, 32-bits
     * For long, from -9223372036854775808 to 9223372036854775807, inclusive, 
     * 64-bits
     * For char, from '\u0000' to '\uffff' inclusive, that is, from 0 to 655,
     * 16 bit unsigned integer in the range 0 to 65535
	 */
	
												
	/* 
	 * Defintions of the STUN header fields 
	 */
	
	/* Default flags */
	short messageTypeRequest 	= 0x0001;
	short messageTypeResponse 	= 0x0101;
	
	/* STUN packet Header fields */
	byte []messageType = new byte [2]; 		// 16 bit message type
	byte []messageLength = new byte [2];    // 16 bit message length
	byte []transactionID = new byte [4];    // 32 bit transaction ID
	/* The largest decimal literal of type int is 2147483648 (2^31) */
	
	
	/*  The attribute types (16-bit):
	 * 	    0x0001 : MAPPED-ADDRESS
	 *		0x0002 : RESPONSE-ADDRESS
	 *		0x0003 : FLAGS
	 *		0x0004 : SOURCE-ADDRESS
	 */
	byte []messageAttributeType = new byte[2]; 

	short MAPPED_ADDRESS 	= 0x0001;
	short RESPONSE_ADDRESS 	= 0x0002;
	short FLAGS 			= 0x0003;
	short SOURCE_ADDRESS 	= 0x0004;
	short CHANGED_ADDRESS 	= 0x0005;
	short CMS_SIGNED_DATA 	= 0x0006;	
	short COOKIE		 	= 0x0007;	
		
	/* 16 bit network byte ordered port (16-bit)*/
	byte []mapped_Address_Port		= new byte[2];	
	
	/* family, either IPv4 (0x01) default (8-bit)
	 * or IPv6 (0x02)
	 */
	byte  mapped_Address_Family		= 0x00001;  
	
	/* 32 bit address IPv4 (16-bit)*/
	/* We need to define also IPv6 128 bit address 
	 * respresentation 
	 */									 
	byte []mapped_Address_IP		= new byte[4];	

	
	/* Holds the message attributes as a sequential byte array (variable size)*/
	byte []messageAttributes = null; 
	
	/* Length of the attributes (16-bits) */									 
	byte []messageAttributeLength = new byte[2]; 
	 
	
	/* Holds the flag value (or a combination of):
	 *   a) "change_port" 
	 *   b) "change IP"
	 *   c) "discard"
	 */
	byte []messageAttributeFlag	 = new byte[4];
	
	/* FLAG values A, B, C */
	int CHANGE_IP	= 0x0001;
	int CHANGE_PORT	= 0x0010;
	int DISCARD		= 0x0100;
	
/*
 * End of defining the STUN header fields. 
 * From here and below we define methods that
 * help in building a STUN packet or getting
 * information about a STUN packet.
 */
	
	/* Holds the message attributes as an array of strings */
	String []s_messageAttributesArray = new String[100]; 
	
	/* attribute array index */
	int i_aa = 0; 
		
	
	
	/** STUNPacket constructor 
	 *
	 */ 
	public STUNPacket() {
		// empty
	}

    /** STUNPacket() constructor
     *  Instantiates a STUN packet.
     *  Inputs: 
     *		a) Message Type as a short int
     *      b) Transaction ID as an int 
     *      c) Message attributes as a byte array
     *		d) Message length (not factoring the STUN header)
     *
     * 	Outputs:
     *		a) A STUN Packet Object
     */
	public STUNPacket  (short sh_messageType,  
						int i_transactionID,
						byte []b_messageAttributes,
						short sh_messageLength	// Message Length at the end 
						) {
	    SizeOf s = new SizeOf();
	    ByteBuffer bb_mT = ByteBuffer.allocate(s.SizeOf(this.messageType));
	    bb_mT.putShort(sh_messageType);
		this.messageType = bb_mT.array();
		
	    ByteBuffer bb_ID = ByteBuffer.allocate(s.SizeOf(this.transactionID));
	    bb_ID.putInt(i_transactionID);		
		this.transactionID = bb_ID.array();

        /* Assign the attributes */
        if ( b_messageAttributes != null ){
        	ByteBuffer bb_aT = ByteBuffer.allocate(s.SizeOf(b_messageAttributes));
       		bb_aT.put(b_messageAttributes);		
			addAttribute(bb_aT.getShort(0), bb_aT.getShort(2), b_messageAttributes);
        }
	    
	    ByteBuffer bb_mL = ByteBuffer.allocate(s.SizeOf(this.messageLength));
        bb_mL.putShort(sh_messageLength);
		this.messageLength = bb_mL.array();		
		
	} // STUNPacket Constructor
	
	/** STUNPacket constructor, accepts three variables:
     *		a) Message Type as a short int
     *      b) Transaction ID as an int 
     *		c) Message length (not factoring the STUN header)
     *
	 *  This is a variation of the STUNPacket Constructor, 
	 *  it does not require an attribute field.
	 *
	 */ 
	public STUNPacket  (short sh_messageType,  
						int i_transactionID,
						short sh_messageLength) {
    SizeOf s = new SizeOf();
    ByteBuffer bb_mT = ByteBuffer.allocate(s.SizeOf(this.messageType));
    bb_mT.putShort(sh_messageType);
	this.messageType = bb_mT.array();
	
    ByteBuffer bb_ID = ByteBuffer.allocate(s.SizeOf(this.transactionID));
    bb_ID.putInt(i_transactionID);		
	this.transactionID = bb_ID.array();

    ByteBuffer bb_mL = ByteBuffer.allocate(s.SizeOf(this.messageLength));
    bb_mL.putShort(sh_messageLength);
	this.messageLength = bb_mL.array();		
	
} // STUNPacket Constructor

	/** addAttribute(), 
	 *  adds an attribute to the messageAttributes[] byte array of a STUN 
	 *	packet.
	 *  inputs: 
	 *	    a) the type of the attribute (as short int)
	 *      b) the length of the attribute (as short int)
	 *      c) the value (e.g. port, family, IP) of the attribute 
	 *		   (as byte array)           
	 */
	public void addAttribute(short sh_messageAttributeType,
								short sh_messageAttributeLength,
								byte []b_messageAttribute){
    
        String s_attribute = new String();
        SizeOf s = new SizeOf();
        
    	ByteBuffer bb_mAT = ByteBuffer.allocate(s.SizeOf(sh_messageAttributeType));
	    bb_mAT.putShort(sh_messageAttributeType);

	    ByteBuffer bb_mAL = ByteBuffer.allocate(s.SizeOf(sh_messageAttributeLength));
	    bb_mAL.putShort(sh_messageAttributeLength);
		
		int attributeLength = s.SizeOf(sh_messageAttributeType)
								+ s.SizeOf(sh_messageAttributeLength)
								+ s.SizeOf(b_messageAttribute);
		
		/* Store the attribute TYPE in the byte buffer */
		ByteBuffer bb_attribute = ByteBuffer.allocate(attributeLength);
		bb_attribute.putShort(sh_messageAttributeType);
		bb_attribute.position(s.SizeOf(sh_messageAttributeType));
		
		/* Store the attribute LENGTH in the byte buffer */		
		bb_attribute.putShort(sh_messageAttributeLength);
		bb_attribute.position( s.SizeOf(sh_messageAttributeLength) 
								+ s.SizeOf(sh_messageAttributeType) );
		
		/* Store the attribute VALUE in the byte buffer */	
		bb_attribute.put(b_messageAttribute);		
		
		/* Allocate a new buffer the size of the current byte array that
		 * holds the attributes, plus the new attribute that we received. 
		 */
		ByteBuffer bb_attributes = ByteBuffer.allocate(
									s.SizeOf(this.messageAttributes)
									+ attributeLength);
									
		if ( this.messageAttributes != null ) {
			bb_attributes.put(this.messageAttributes);	
			bb_attributes.position(s.SizeOf(this.messageAttributes));	
			bb_attributes.put(bb_attribute.array());	
			this.messageAttributes = bb_attributes.array();	
		} else {
			bb_attributes.put(bb_attribute.array());	
			this.messageAttributes = bb_attributes.array();	
		}
		

        
        if (DEBUG == true) {
			String sm = new String(this.messageAttributes);
			System.out.println("STUNPacket - addAttribute method - "
								+"Contents of messageAttributes[] = "+sm);							
		
		}

        
	} // addAttribute()

    /** makeAttribute(), returns a byte array containing the supplied attributes
     *  inputs:
     *	    a)Attribute Type as a short integer
     *	    b)Attribute Length as a short integer
     *	    c)Attribute value as a byte array
     *
     *	outputs: 
     *		a) array of bytes that contains an attribute
     *         of the form [Type][Length][Attribute Value]
     */		    
	public byte [] makeAttribute(short sh_messageAttributeType,
									short sh_messageAttributeLength,
									byte []b_messageAttribute){
	
        String s_attribute = new String();

        
    	ByteBuffer bb_mAT = ByteBuffer.allocate(s.SizeOf(sh_messageAttributeType));
	    bb_mAT.putShort(sh_messageAttributeType);

	    ByteBuffer bb_mAL = ByteBuffer.allocate(s.SizeOf(sh_messageAttributeLength));
	    bb_mAL.putShort(sh_messageAttributeLength);
		
		String s_mAT = new String(bb_mAT.array());
		String s_mAL = new String(bb_mAL.array());
		String s_mAV = new String(b_messageAttribute);
		
		/* Concatenate and return the byte array representing the attribute. */
		s_attribute = s_mAT + s_mAL + s_mAV;
        return (s_attribute.getBytes());

	} // makeAttribute()
	
	
	/** Creates an attribute's value using the form [port][family][address]
	 ** and returns a byte array.
	 */
	public byte []makeAttributeValue(short port, byte family, byte []address) {
		SizeOf s = new SizeOf();					
		int size = s.SizeOf(port)
					+s.SizeOf(family)
					+s.SizeOf(address);
		
		ByteBuffer bb_av = ByteBuffer.allocate(size);
		bb_av.position(0);
		bb_av.putShort(port);			
		bb_av.position(2);
		bb_av.put(bb_av.position(),family);
		bb_av.position(3);
		bb_av.put(address);
		return ( bb_av.array() );
		
	} // makeAttributeValue

	/** Creates an attribute's value using the form value [32-bits]
	 ** and returns a byte array. It is used for creating
	 ** FLAGS attribute values.
	 */
	public byte []makeAttributeValue(int value) {
		SizeOf s = new SizeOf();
					
		ByteBuffer bb_av = ByteBuffer.allocate(s.SizeOf(value));
		bb_av.putInt(value);
		return bb_av.array();
		
	} // makeAttributeValue

	
	/**
	 * parse(), parses a byte array that contains the STUN packet information.
	 * The byte array is typicaly the datat portion of a UDP packet received by 
	 * a client.
	 */
	public void parse (byte []data){
	    
		SizeOf s = new SizeOf();	
		ByteBuffer bb_data = ByteBuffer.allocate(s.SizeOf(data));
		ByteBuffer bb_attributes = null;
		ByteBuffer bb_attribute = null;
		
		String s_data = new String(data);
		bb_data.put(data);
		bb_data.rewind();
		byte []data_buffer = new byte[s.SizeOf(bb_data.array())];
		data_buffer = bb_data.array();
		int index = 0;   // Marks the current reading index in the byte array
		int offset = 0;  // Indicates the offset/max number of bytes to be read
		
		
		/* Used to store a single attribute a string for general use inside 
		 * the parse method 
		 */
		String s_messageAttribute = null;
		
		String cont = new String(bb_data.array());
		
		if ( DEBUG == true ) {
			System.out.println("STUNPacket - Length  "+ s.SizeOf(data));
			System.out.println("STUNPacket - Content "+ cont.toString());
		}

        /* Parse the STUN header */
		/* Read Message Type 16-bits (two bytes) */
		index = 0;
		offset = s.SizeOf(this.messageType);
		String s_messageType = new String(cont.getBytes(),index,offset);
		this.messageType = s_messageType.getBytes();			

        /* Parse the STUN header */		
		/* Read Message Length 16-bits (two bytes) */
		index += s.SizeOf(this.messageType);
		offset = s.SizeOf(this.messageLength);
		String s_messageLength = new String(cont.getBytes(),index,offset);
		this.messageLength  = s_messageLength.getBytes();								

        /* Parse the STUN header */		
		/* Read Transaction ID  32-bits (four bytes) */
		index += s.SizeOf(this.messageLength);
		offset = s.SizeOf(this.transactionID);		
		String s_transactionID = new String(cont.getBytes(),index,offset);
		this.transactionID  = s_transactionID.getBytes();
        
        /* If there are attributes (header + length)
         * parse for attributes.
         */
        
        i_aa = 0; // attribute array index
        int v_length = 0; // Attribute Value length
        int reading_index = 0; // Reading Index for the attribute array
        
    	int i_headersum	=	s.SizeOf(this.messageType)
				  			+ s.SizeOf(this.messageLength)
				  			+ s.SizeOf(this.transactionID); 
				  
        /* 2 bytes type + 2 bytes length + 4 bytes Transaction ID */
        int current_index = s.SizeOf(this.messageType) 
        					+s.SizeOf(this.messageLength) 
        					+s.SizeOf(this.transactionID);

        
        int aa_offset = 0; 
		

		/* Convert message length to short from the parsed byte array */
		ByteBuffer bb_messageLength = ByteBuffer.allocate(2);
		bb_messageLength.put(this.messageLength);
		int i_messageLength = bb_messageLength.getShort(0);
		
        if ( i_messageLength > 0) {
        	
        	/* Keep reading attributes as long as the index 
        	 * does not match ( == ) the content length.
        	 */
        	String s_attributes = new String(cont.getBytes()); 

       	
        	/* Keep comparing the current index to the message length plus it's header.
        	 *
        	 */						
        					   						
        	if ( DEBUG == true ){	
				System.out.println("STUN Packet - Packet length = "
													+bb_data.remaining()); 
													
				System.out.println("STUN Packet - Message length = "
													+i_messageLength);
													    
				System.out.println("STUN Packet - Current index = "
													+current_index); 
													
				System.out.println("STUN Packet - Headers Length= "
													+i_headersum); 
        	} 
        
        bb_data.position(current_index);
        this.messageAttributes = new byte[i_messageLength];	
		bb_data.get(this.messageAttributes,0,i_messageLength);	    
				
        } // if - messageLength
        
	} // End of parse
	
	
	
	/** setHeader() is used to set the fields of a STUN packet's header .
	 *  Inputs: 
	 *		a) Message Type as a Byte array
	 *		b) Message Length as a Byte array
	 *		c) Transaction ID as a Byte array
	 *
	 * Outputs:
	 *		a) NONE
	 * 
	 */	
	public void setHeader(byte []messageType, 
							byte [] messageLength, 
							byte []transactionID) {
								
		this.messageType = messageType;
		this.messageLength = messageLength;
		this.transactionID = transactionID;
	} // setHeader
	
	/** This setHeader works as the previous
	 *  but it doesn't set the transaction ID
	 *  of a packet. Used mostly by Server responses
	 *  where the server does not need to modify the
	 *  the transaction ID.
	 */
	public void setHeader(byte []messageType, byte []messageLength) {
		this.messageType = messageType;
		this.messageLength = messageLength;
	} // setHeader
	
	
	/** setPacketSize
	 *  Updates the STUN packets size value.
	 *
	 */
	 public void setMessageLength(short messageLength ){
	 	ByteBuffer bb_mL = ByteBuffer.allocate(s.SizeOf(this.messageLength));
	    bb_mL.putShort(messageLength);
		this.messageLength = bb_mL.array();	
	 }
	 
	 
	/** setAttributes()
	 *  Set's the attributes of a STUN packet.
	 *  This an alternate way to using the class
	 *  constructore.
	 */
	public void setAttributes(short messageAttributeType,  
							int messageAttributeLength, 
							byte []messageAttributes) {
		
		Short sh_MAT = new Short(messageAttributeType);
		String s_MAT = new String(sh_MAT.toString());
		this.messageAttributeType = s_MAT.getBytes();
		
		Integer i_MAL = new Integer(messageAttributeLength);
		String s_MAL = new String(i_MAL.toString());
		this.messageAttributeLength = s_MAL.getBytes();
		
		this.messageAttributes = messageAttributes; 
	} // setAttributes
	
	
	/** getMessageAttributeType()
	 *  Returns a byte array containing the attribute type that
	 *  is set in the respective STUN packet.
	 *  The choices are:
	 *     a) MAPPED-ADDRESS   (0x00001)
	 *     b) RESPONSE-ADDRESS (0x00002)
	 *     c) FLAGS            (0x00003)
	 *     d) SOURCE-ADDRESS   (0x00004)
	 */
	public byte []getMessageAttributeType() {
		String s_MAT = new String(this.messageAttributeType);
		return s_MAT.getBytes();
	}
	
	/** getMessageAttributeLength()
	 *  Returns the length of the Attribute Value in bytes.
	 *  This function is used typicaly, when calculating 
	 *  the size of the STUN request.
	 */
	public byte [] getMessageAttributeLength() {
		String s_MAL = new String(this.messageAttributeLength);
		return s_MAL.getBytes();
	}
	
	/** getmessageAttributes()
	 *  Returns the contents of the Attribute Value field in
	 *  bytes. The calling function is responsible of decoding the 
	 *  fields (e.g. [Port-Family-Address...]
	 */
	public byte [] getmessageAttributes() {
		return this.messageAttributes;
	}
	
	/** getMessageType()
	 *  Returns the message type from the respective packet.
	 */
	public byte []getMessageType() {
		return this.messageType;
	}

	/** getMessageLength()
	 *  Returns the length of the respective STUN packet
	 *  in bytes (not including the STUN header).
	 */	
	public short getMessageLength(){
		SizeOf s = new SizeOf();
		int i_sType = s.SizeOf(this.messageAttributeType);
		int i_sLength = s.SizeOf(this.messageAttributeLength);
		int i_sValue = s.SizeOf(this.messageAttributes);
		int i_Length = i_sType + i_sLength + i_sValue;
		int bb_len = s.SizeOf(this.messageLength);
		Integer I_Length = new Integer(i_Length);	
		return (I_Length.shortValue());
	}
	
	/** getMessageTypeRequest()
	 *  Returns the default message type request as it is defined in the
	 *  IETF proposal.
	 */	
	public byte []getMessageTypeRequest() {
		Short sh_MTR = new Short(this.messageTypeRequest);
		String s_MTR = new String(sh_MTR.toString());
        ByteBuffer bb_MTR = ByteBuffer.allocate(2);	
        bb_MTR.putShort(this.messageTypeRequest);
		return (bb_MTR.array());

	}

	
	/** getMessageTypeResponse()
	 *  Returns the default message response as it is defined in the
	 *  IETF proposal. 
	 */
	public byte []getMessageTypeResponse() {
		Short sh_MTR = new Short(this.messageTypeResponse);
		String s_MTR = new String(sh_MTR.toString());
		return (s_MTR.getBytes());
	}	


	/** Returns the transaction ID associated with the respective packet
	 *  as an integer.
	 */
	public int getTransactionID() {
		SizeOf s = new SizeOf();
		ByteBuffer bb_ID = ByteBuffer.allocate(s.SizeOf(this.transactionID));
		bb_ID.put(this.transactionID);	
		int tID = bb_ID.getInt(0);
		
		return tID;
	}
	
	/** Returns the size of the STUN packet header as an int.  
	 */	
	public int getHeaderLength(){
		
		int mLength = (s.SizeOf(messageType) + s.SizeOf(messageLength) 
						+ s.SizeOf(transactionID)); 
						
		//Integer i_mL = new Integer(mLength);
		//ByteBuffer bb_HL = ByteBuffer.allocate(2);
		//bb_HL.putShort(i_mL.shortValue());	
		//return (bb_HL.array()); 
		return mLength;
		
	} // End of getHeaderLength()


    /** Returns the length of the entire STUN packet
      * header plus attributes as an int.
      */
    public int getPacketLength() {
    	
    	/* convert from byte arrays to strings */
    	SizeOf s = new SizeOf();
		ByteBuffer bb_HL = ByteBuffer.allocate(s.SizeOf(getHeaderLength()));
    
    	Integer i_pl = new Integer((getMessageLength() + getHeaderLength()));
    	String s_pl = i_pl.toString();
    	//return ( s_pl.getBytes() );
    	return i_pl.intValue();
    }

	/** Returns a byte array that contains the header information of 
	 *  a STUN packet. 
	 */
	public byte [] getHeader(){
		
		/* Read the each byte array to a string */		
		String mt = new String(this.messageType);
		String ml = new String(this.messageLength);
		String tid = new String(this.transactionID);
		
		/* Concatenate each string that represents each field
		 * in to one single string and return the byte array.
		 */
		String header = mt+ml+tid;	
		return (header.getBytes());
	} // End of getHeader()


	/** Returns a byte array that contains the payload information of 
	 *  a STUN packet. 
	 */	
	public byte [] getPayload(){
			    
		return ( this.messageAttributes ); 
		
	} // getPayload()
	
	
	/** Returns a byte array that contains the header and body of the 
	 *  STUN packet. 
	 */
	public byte [] getPacket(){
		SizeOf s = new SizeOf();
		ByteBuffer bb_header = ByteBuffer.allocate(s.SizeOf(getHeader()));
		bb_header.put(getHeader());
		ByteBuffer bb_packet = ByteBuffer.allocate(
									s.SizeOf(this.messageAttributes));
									
		ByteBuffer bb_Pt = ByteBuffer.allocate(s.SizeOf(getHeader()) 
									+ s.SizeOf(this.messageAttributes));
		
		if  ( this.messageAttributes != null ){ 
		/* If there are attributes add them to the 
		 * returning string
		 */
		 	bb_packet.put(this.messageAttributes);
		 	bb_Pt.put(bb_header.array());
		 	bb_Pt.position(s.SizeOf(getHeader()));
		 	bb_Pt.put(bb_packet.array());
		}
		else {
			/* if there are no attributes just return the header */
		 	bb_Pt.put(bb_header.array());
		}
		
		return ( bb_Pt.array() ); 
	} // getPacket()
	
	/** printPacket() is used mostly for debugging purposes.
	 *  It prints the fiels in a STUN packet on the stdout.
	 *
	 */
	public void printHeader(){
		
		ByteBuffer bb_mT = ByteBuffer.allocate(2);
		bb_mT.put(this.messageType);
		
		ByteBuffer bb_mL = ByteBuffer.allocate(2);
		bb_mL.put(this.messageLength);
		
		ByteBuffer bb_tID = ByteBuffer.allocate(4);
		bb_tID.put(this.transactionID);
		
		System.out.println(" *--- STUN Packet Header Information ----*");
		System.out.println("  Header Message Type               : "
													+bb_mT.getShort(0));
													
		System.out.println("         Message Length             : "
													+bb_mL.getShort(0));
													
		System.out.println("         Message Transaction ID     : "
													+bb_tID.getInt(0));
		System.out.println(" *---------------------------------------*"); 
	} // printHeader()
	
	public void printPayload(byte  []b_attributes){
		
		STUNPacket sp = new STUNPacket();
		SizeOf s = new SizeOf();
		int ix = 0;
		int avLength = 0; // Attribute Value Length, not including type and length
		int ofst = 0;
		short attributevaluePort = 0;
		byte attributeValueFamily = 0;
		String attributeValueIP = null;
		
		ByteBuffer bb_attributes = ByteBuffer.allocate(s.SizeOf(b_attributes));
		ByteBuffer bb_attribute = null;
		byte []b_attributeValue = null;
									   
		if ( b_attributes != null ) {
			bb_attributes.position(0);
			bb_attributes.put(b_attributes);
		    bb_attributes.rewind();
		} else {
			return ;
		}
			
		while (ix < s.SizeOf(bb_attributes.array())) {

			if (DEBUG) {
				System.out.println("Payload attributes in byte format = "
																+b_attributes);
																
				System.out.println("Payload attributes size = "
											+s.SizeOf(bb_attributes.array()));
																	
				String sb = new String(bb_attributes.array());
				System.out.println("Payload content = "+sb);
			}
				
	        bb_attributes.position(ix);
			bb_attributes.get(this.messageAttributeType,
								0,
								s.SizeOf(this.messageAttributeType));
								
			ByteBuffer bb_mAT = ByteBuffer.allocate(
								s.SizeOf(this.messageAttributeType));
								
			bb_mAT.put(this.messageAttributeType);				
	        ix += s.SizeOf(this.messageAttributeType);					
 
	        bb_attributes.position(ix);
			bb_attributes.get(this.messageAttributeLength,
								0,
								s.SizeOf(this.messageAttributeLength));	
										
			ByteBuffer bb_mAL = ByteBuffer.allocate(
								s.SizeOf(this.messageAttributeLength));
								
			bb_mAL.put(this.messageAttributeLength);
			avLength= bb_mAL.getShort(0);
				

			ix +=  s.SizeOf(this.messageAttributeLength);

		    if (bb_mAT.getShort(0) == sp.FLAGS) { // FLAGS 
		    	bb_attribute = ByteBuffer.allocate(4);
		    	bb_attribute.rewind();	    	
		    	bb_attributes.position(ix);
		    	bb_attributes.get(this.messageAttributeFlag,0,avLength);
		    	bb_attribute.put(this.messageAttributeFlag);
		    	ix += s.SizeOf(this.messageAttributeFlag);
		    	String flag = new String(this.messageAttributeFlag);	    	
				System.out.println(" *--- STUN Packet Payload Information ---*");	
				System.out.println("    Attribute Type          : "
														+bb_mAT.getShort(0));
														
				System.out.println("              Length        : "
														+avLength);		
																
				System.out.println("              FLAG          : "
														+bb_attribute.getInt(0));		
				System.out.println(" *---------------------------------------*"); 			    
		    }
		    
		    else { // This is probably an attribute of the form [port][family][address]
		    	b_attributeValue = new byte[avLength];
		    	bb_attributes.position(ix);				    	
				bb_attributes.get(b_attributeValue, 0, avLength);
				bb_attribute = ByteBuffer.allocate(s.SizeOf(b_attributeValue));	    	

				if ( b_attributeValue != null ) {
					bb_attribute.position(0);
					bb_attribute.put(b_attributeValue);
					bb_attribute.rewind();
		 
				} else {
					continue ;
				}
	
				attributevaluePort = bb_attribute.getShort(0);
				attributeValueFamily = bb_attribute.get(2);
				
				attributeValueIP = new String (bb_attribute.array(),3,(avLength-3));
		    	ix += avLength;	
				System.out.println(" *--- STUN Packet Payload Information ---*");	
				System.out.println("    Attribute Type          : "+bb_mAT.getShort(0));
				System.out.println("              Length        : "+avLength);				
				System.out.println("              Value Port    : "+attributevaluePort);			
				System.out.println("              Value Family  : "+attributeValueFamily);
				System.out.println("              Value (NAT IP): "+attributeValueIP);
				System.out.println(" *---------------------------------------*"); 
		    } // if-else
		} // while 
	}
	
	/** getResponseIP(), returns a String  
	 ** Returns the IP address contained in the STUN request/response.	
	 */
	public String getResponseIP(STUNPacket sp) {
		SizeOf s = new SizeOf();
		int ix = 0;
		int avLength = 0; // Attribute Value Length, not including type and length
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
								s.SizeOf(sp.messageAttributeType));
								
			ByteBuffer bb_mAT = ByteBuffer.allocate(
											s.SizeOf(sp.messageAttributeType));
											
			/* Store the attribute Type for further processing. */
			bb_mAT.put(sp.messageAttributeType);				
	        ix += s.SizeOf(sp.messageAttributeType);					
			
			/* Get the attribute value length as inidcated in the attribute header. */
			avLength = bb_attributes.getShort(ix);	
			ix +=  s.SizeOf(sp.messageAttributeLength);

		    
		    if ( bb_mAT.getShort(0) == sp.RESPONSE_ADDRESS ) { 
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
												
				return ( attributeValueIP );
		    } // if
			ix += avLength;
		}
		return ( attributeValueIP );
	}
	
	/** getResponsePort(), returns an int
	 **  Returns the port assossiated with IP address contained 
	 **  in the STUN request/response.	
	 */
	public int getResponsePort( STUNPacket sp ) {
		SizeOf s = new SizeOf();
		int ix = 0;
		int avLength = 0; // Attribute Value Length, not including type and length
		int ofst = 0;
		short attributevaluePort = 0;
		
		ByteBuffer bb_attributes = ByteBuffer.allocate(
											s.SizeOf(sp.messageAttributes));
											
		ByteBuffer bb_attribute = null;
		byte []b_attributeValue = null;
									   
		if ( sp.messageAttributes != null ) {
			bb_attributes.put(sp.messageAttributes);
		    bb_attributes.rewind();
		}
		else {
			return -1;
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
			
			/* Get the attribute value length as inidcated in the attribute header. */
			avLength = bb_attributes.getShort(ix);	
			ix +=  s.SizeOf(sp.messageAttributeLength);

		    
		    if ( bb_mAT.getShort(0) == sp.RESPONSE_ADDRESS ) { 
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
				return ( sp.unsigned2signed(attributevaluePort) );
		    } // if
			ix += avLength;
		}
		return ( sp.unsigned2signed(attributevaluePort) );
	}
	/** Converts a signed short integer to unsigned
	 *  integer.
	 */
	public int unsigned2signed(short s) {
		int i = 0;
		if (s < 0) {
			i = s+65536;
		}
		else {
			i = s;
		}
		return i;
	} // unsigned2signed	
	
} // Edn of STUNPacket
/************************* END OF FILE *******************************/