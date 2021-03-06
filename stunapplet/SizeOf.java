/* @(#)SizeOf.java        0.01 2002/02/08  
 * Calculates the size of a given variable except "String()" 
 * since it has it's own method (.length()).
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
import java.lang.*;
import java.nio.*;

public class SizeOf {
              
	public static int SizeOf(boolean b) {
		return 1;
	}
        
	public static int SizeOf(byte []b) {
		try {
			String s_b = new String(b);
			return s_b.length();
		} catch (NullPointerException npe) {
			return 0;
		}

	}

	public static int SizeOf(byte b) {
		return 1;
	}
    
 	public static int SizeOf(Byte b) {
		return 1;
	} 
	      
    public static int SizeOf(char c) {
        return 2;
    }
        
    public static int SizeOf(short s) {
        return 2;
    }
        
    public static int SizeOf(int i) {
         return 4;
    }

} // SizeOf()
/************************* END OF FILE *******************************/