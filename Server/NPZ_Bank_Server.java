//=================== OS-2-Project ============================
// SERVER Site
// + Receive Data (Question) from the Client Site
// + Send Data (Answer) to the Client Site
//===============================================================

import java.net.*;
import java.util.*;
import java.io.*;
import java.net.HttpURLConnection;
import java.net.URL;

//===============================================================
// * creates instances of InServer and OutServer
//================================================================
public class NPZ_Bank_Server {

   static int Data_Port = 10700;   // to Receive QUESTION from the Client
   static int Reply_Port = 10701;  // to Send ANSWER to the Client
   private final boolean debug = false;
   //String QlockFile = "f-Qs.dat";  // Flag-File for writting QUESTION (from CLIENT)
   //String AlockFile = "f-As.dat";  // Flag-File for writting ANSWER(to the CLIENT)
   //String QFile = "Qs.dat";  // File for writting QUESTION (from CLIENT)
   //String AFile = "As.dat";  // File for writting ANSWER(to the CLIENT)
   

   /*
    * Application entry point
    * @param args - command line arguments
    */
 public static void main (String args[]) {

   // To Receive QUESTION "Qc.dat" (as a SERVER) from the Client
   //........................................................
   InServer IP_Server  = new InServer (Data_Port);
   
   // To Send the ANSWER "As.dat" (as a SERVER) to the Client 
   //........................................................
   //OutServer List_Server  = new OutServer (Reply_Port);	//@@@
   /*for(int i = Reply_Port; i<(Reply_Port + 20); i++) {
	   OutServer List_Server  = new OutServer (i); //REALLY bad option
   }*/
   
  }

} // END of the SERVER class


//==================================================================
// * class creates a OutServer socket for SENDING data TO the CLIENT
//==================================================================
class OutServer implements Runnable {

   ServerSocket server = null;      //Instance of ServerSocket
   Socket socket = null;            //The actual socket used for SENDING data

   String fnameSO = "OUTGOING.dat"; // Name of the File (by default) containning O_data at Server
 
   String AlockFile = "f-As.dat";     // Flag-File for ANSWER (DATA at Server to send to Client)
   int fAnswer=0;                    // Value of FLAG for the ANSWER read from file

   PrintStream stream = null;       //A PrintStream is used for SENDING data

   //DataInputStream inStream = null; //A DataInputStream is used for reading file name
   BufferedReader inStream = null;
   int thePort;                     //Port number

   Thread thread;                   //A seperate thread is used to wait for a socket accept

   /*
    * constructor starts the thread
    * @param port - the port to listen to
    */
   public OutServer (int port) {
      thePort = port;

      thread = new Thread (this);
      thread.start ();
   }

   /*
    * the thread that waits for socket
    * connections and SENDs data from a FILE to the CLIENT
    */
   public void run () {

      // Create a ServerSocket
      //.......................................
      try {
         //server = new ServerSocket (thePort);
    	 server = new ServerSocket();	//make sure to do: server.close();
         server.setReuseAddress(true);	//@@@
         server.bind(new InetSocketAddress(thePort));
         System.out.println ("OPEN a socket for SENDING data TO the CLIENT at port " + thePort);
      }
      catch (Exception e) {
         System.out.println ("Tried making a connection for sending data to client: " + e);
         System.exit (1);
      }

      //while (true) { //accept a connection and then...?
         try {
        	socket = new Socket();	//make sure to do: socket.close();
        	socket.setReuseAddress(true);
            socket = server.accept();
            System.out.println ("Connected to port : " + thePort + " WAITING for the Answer ...");
         }
         catch (Exception e) {
            System.out.println ("Trying to connect a socket" + e);
            System.exit (1);
         }

         // Accept file name "name" from the Client
         // ( Client tells WHICH FILE it wants to READ )
         //...............................................................
         String name = null;
         try {
            // Prepare a stream for SENDing later
            stream = new PrintStream (socket.getOutputStream ());

            //Get File name from the CLIENT
            // new DataInputStream ();
            inStream = new BufferedReader(new InputStreamReader(socket.getInputStream ()));
            name = inStream.readLine ();
            if (name == null) name = fnameSO;
         }
         catch (Exception e) {
            System.out.println ("trying to read and write: " + e);
            //break; //!!!
         }

    // Need to CHECK the LOCK of the ANSWER from the Server if the given file is READY
    //..................................................................................
         while (fAnswer!=1) {
               // Waiting for the ANSWER to be ready
               // Assume DONE (change 0->1 in "f-As.dat")
               fAnswer = ReadFlag(AlockFile);
                  System.out.println ("Flag Value = "+ fAnswer);
         }         
         fAnswer=0; 
         
         // Open the given FILE
         //........................................................
         FileInputStream fs = null;
         try {
            fs = new FileInputStream (name);
         }
         catch (Exception e) {
            System.out.println ("Trying to create fileinputstream: " + e);
            //break; //!!!
         }

         // Read Data from the given File and SEND it to the "stream"
         // so that it will go to the Client
         //...........................................................
         //DataInputStream ds = new DataInputStream (fs);
         BufferedReader ds = new BufferedReader(new InputStreamReader(fs));
         while (true) {
            try {
               String s = ds.readLine (); // Read Data from the given File
               //if (s == null) break; //!!!
               stream.println (s); // SEND it to the "stream"
               break;
            }
            catch (IOException e) {
               System.out.println (e);
               break;
            }
         }

         //  Close FILE and close socket
         //.................................................
         try {
            fs.close ();
            System.out.println ("   Reading file " + name + " and SENDING it,done.");
            socket.close ();
            System.out.println ("Connection closed at port : " + thePort);
         }
         catch (IOException e) {
            System.out.println ("There was an error closing some stuff: " + e);
         }

      //}   // end of while
      try {
    	  server.close(); //@@@
    	  System.out.println("outServer now closed");
      } catch(Exception e) {
    	  System.out.println("Couldn't close server: " + e);
      }
   }   // end of run ()
   
//**************************
//    FUNCTIONS PART
//**************************
    
    
//...............................................................
// Read what is in a FLAG file and Converts it into an Interger
//...............................................................
public int ReadFlag (String FileName) {

    int ReturnValue=0;

  // OPEN the given File 
  //.........................................................

              FileInputStream ReadFile = null;

                try {
                   ReadFile = new FileInputStream (FileName);
                }

                catch (Exception e) {
	               System.out.println (e);
	               //System.exit (1);
                 }

    // READ the file
    //..................................................................

             //DataInputStream ds = new DataInputStream (ReadFile);
             BufferedReader ds = new BufferedReader(new InputStreamReader(ReadFile));
              try {
                      String s = ds.readLine (); // Read Data from the given File
   	          try {
                      ReturnValue = Integer.parseInt(s);
                      }
              catch (NumberFormatException e) {
                          System.out.println (e);
               	          //System.exit (1);
                      }
                  }
              catch (IOException e) {
                          System.out.println (e);
		          //System.exit (1);
                  }

      //Close File

      try {
            ReadFile.close ();
      }
      catch (IOException e) {
            System.out.println (e);
      }

                System.out.println ("Flag Value = "+ ReturnValue);

      return ReturnValue;
   } // end of FUNCTION
   
   
}   // END of OutServer class


//================================================================
// * class creates a server socket for RECEIVING data from
// * the client
//=================================================================
class InServer implements Runnable {

   ServerSocket server = null;    //Instance of ServerSocket
   Socket socket = null;          //The actual socket used for RECEIVING data

   int thePort;                   //Port number
   Thread thread;                 //A seperate thread is used to wait for a socket accept

   String QlockFile = "f-Qs.dat";  // Flag-File for writting QUESTION (from the CLIENT)

   String fnameSI = "INCOMING.dat";      // Name of the File (by default) containning I_data at Server

   //DataInputStream stream = null; //A DataInputStream is used for RECEIVING data
   BufferedReader stream = null;

   /*
    * constructor starts the thread
    * @param port - the port to listen to
    */
   public InServer (int port) {

      thePort = port;
      thread = new Thread (this);
      thread.start ();
   }

   /*
    * the thread that waits for socket
    * connections and RECEIVES data from the client
    */
   public void run () {

      // Create a ServerSocket
      //.......................................
      try {
         server = new ServerSocket (thePort);
         System.out.println ("OPEN a socket for RECEIVING data from the CLIENT at port " + thePort);
      }
      catch (Exception e) {
         System.out.println ("Trying to create a new ServerSocket(): " + e);
         System.exit (1);
      }
      System.out.println("InServer - Trying to make a Connection!");
      while (true) {
         try {
        	System.out.println("InServer - Connection?");
            socket = server.accept ();
            System.out.println("InServer - Connected!");
            System.out.println ("Connected to port : " + thePort);
         }
         catch (Exception e) {
            System.out.println ("Trying to accept a connection: " + e);
            System.exit (1);
         }

         // Accept file name "name" from the Client
         // ( Client tells WHICH FILE it wants to WRITE )
         //...............................................................
         String name = null;
         String port = null;
         int recievePort = 0;
         try {
            // Prepare a stream for RECEIVING
            //stream = new DataInputStream (socket.getInputStream ());
        	 stream = new BufferedReader(new InputStreamReader(socket.getInputStream()));

            //Get File name from the CLIENT
            name = stream.readLine ();
            if (name == null) name = fnameSI;
            
            //Get the RecievePort from the CLIENT   @@@
            port = stream.readLine ();
            if (port != null) recievePort = Integer.parseInt(port);
            else System.out.println("NO PORT RECIEVED");
            //Create the OutServer for the client		@@@
            OutServer outServer = new OutServer (recievePort);
            WriteFlag(QlockFile,"0");
         }
         catch (Exception e) {
            System.out.println ("Trying to read in from port: " + e);
            break; 
         }

         // Open the given FILE
         //........................................................
         FileOutputStream wf = null;
         try {
            wf = new FileOutputStream (name);
            
            //Do I need this?
            /*
            int fAnswer = 0; //Assume it is locked
            while (fAnswer!=1) {
                // Waiting for the ANSWER to be ready
                // Assume DONE (change 0->1 in "f-As.dat")
                fAnswer = ReadFlag(AlockFile);
                   System.out.println ("Flag Value = "+ fAnswer);
	          }
            WriteFlag(QlockFile,"0");
            */
         }
         catch (Exception e) {
            System.out.println (e);
            System.exit (1);
         }


         // Read Data from the "stream" and WRITE it to the given File
         //...........................................................

         PrintStream ds = new PrintStream (wf); // Why do we need this ???
         String lastS = "";
         while (true) {
            try {
               String s = stream.readLine ();
               if (s == null) break;
               lastS = s;
               ds.println (s); // Why not just fs.println (s); ???
            }
            catch (IOException e) {
               System.out.println (e);
               break;
            }
         }
         String[] request =  lastS.split(":");
         System.out.println("Request: " + lastS);
   //set the f-As.dat flag to 0 so that we know that I am writing the response
   //Do whatever we need to with the Question from Client
       //=========================================================================
       //      Update the Accounts
       //=========================================================================  
         		//call updateAccounts.php?action=payment&payer=123456789&payee=123456789&ammount=25
         		//http://www.mkyong.com/java/how-to-send-http-request-getpost-in-java/
         //customerId:password:BankRequestId:payee:payer:ammount
         String bankReply = null;
         if(request.length == 6) {
	         String payerAcc = request[4];
	         String payeeAcc = request[3];
	         int ammount = 0;
	         try{
	        	 ammount = Integer.parseInt(request[5]);
	         } catch(Exception e) {
	        	 ammount = 0;
	        	 System.out.println("Ammount required is too large: " + e);
	         }
	         String response = "failure to get a response from makePayment.php";
	         try {
	        	 response = sendGet(payerAcc, payeeAcc, ammount);
	         } catch (Exception e) {
	        	 System.out.println("Exception when trying to post: " + e);
	         }
	         System.out.println(response);
	       //=========================================================================
	       //      Write the reply
	       //=========================================================================
	         //System.out.println("Here is the value of lastS: " + lastS);
	         //split it on ":" then write bankReply.dat
	         //Then add the bankReply.dat to bankRequestRecord.dat
	         //customerId:password:BankRequestId:payee:payer:ammount
	         //BankRequestID:TransferConfirmation:CustomerID
	         //change the bankRequest to ---> this format
	         // 071000-01 ---> 07-O-00001
	         StringBuilder sb = new StringBuilder(request[2]);
	         sb.deleteCharAt(6);
	         sb.replace(2, 3, "-B-");
	         String confirmation = sb.toString();
	         if(response.equals("success")) {
	        	 bankReply = request[2] + ":" + confirmation + ":" + request[0];
	        	 System.out.println("Reply: " + bankReply);
	         } else
	        	 bankReply = request[2] + ":" + response + ":" + request[0];
         } else {
        	 System.out.println("Invalid bankRequest");
        	 bankReply = "Invalid bankRequest";
         }
         FileOutputStream wfBRR = null;
         try {
            wfBRR = new FileOutputStream ("bankRequestRecord.dat", true);
         }
         catch (Exception e) {
            System.out.println (e);
            System.exit (1);
         }
         PrintStream dsBRR = new PrintStream (wfBRR);
         dsBRR.println(bankReply);
         
         FileOutputStream wfBRS = null;
         try {
            wfBRS = new FileOutputStream ("bankReplyS.dat");
         }
         catch (Exception e) {
            System.out.println (e);
            System.exit (1);
         }
         PrintStream dsBRS = new PrintStream (wfBRS);
         dsBRS.println(bankReply);
         
   // WRITE "1" to the LOCK(FLAG) of the ANSWER 
   //........................................................
   WriteFlag(QlockFile,"1");

         System.out.println ("   RECEIVING and writing data to file " + name +", done.");

         try {
            wf.close ();
            socket.close ();
            System.out.println ("Connection closed at port : " + thePort + " and Continue to LISTEN ...");
         }
         catch (IOException e) {
            System.out.println (e);
         }
      }
   }
   
//**************************
//    FUNCTIONS PART
//**************************

//=========================================================================
//      WRITE a Value to the FLAG File 
//=========================================================================

   public void WriteFlag (String FlagFileName, String FlagValue) {
   
         // Open the a FILE at Client site to WRITE
         //........................................................

         FileOutputStream wf = null;

         try {
            wf = new FileOutputStream (FlagFileName);
         }

         catch (Exception e) {
            System.out.println (e);
            System.exit (1);
         }

         // WRITE Value to the FILE 
         //........................................................

         PrintStream ds = new PrintStream (wf); // Create Output Sream to WRITE
         ds.println (FlagValue); 
      
      //Close File
      try {
         wf.close ();
      }
      catch (IOException e) {
         System.out.println (e);
      }

   }  // end of WriteFlag
   
   //...............................................................
   // Read what is in a FLAG file and Converts it into an Interger
   //...............................................................
   public int ReadFlag (String FileName) {

	   int ReturnValue=0;

  // OPEN the given File 
  //.........................................................

              FileInputStream ReadFile = null;

                try {
                   ReadFile = new FileInputStream (FileName);
                }

                catch (Exception e) {
	               System.out.println (e);
	               //System.exit (1);
                 }

    // READ the file
    //..................................................................
                
            //DataInputStream ds = new DataInputStream (ReadFile);
            BufferedReader ds = new BufferedReader(new InputStreamReader(ReadFile));
             try {
                     String s = ds.readLine (); // Read Data from the given File
  	          try {
                     ReturnValue = Integer.parseInt(s);
                     }
             catch (NumberFormatException e) {
                         System.out.println (e);
              	          //System.exit (1);
                     }
                 }
             catch (IOException e) {
                         System.out.println (e);
		          //System.exit (1);
                 }

     //Close File

     try {
           ReadFile.close ();
     }
     catch (IOException e) {
           System.out.println (e);
     }

               System.out.println ("Flag Value = "+ ReturnValue);

     return ReturnValue;
  } // end of ReadFlag

   public String ReadAccounts() { 
			FileInputStream ReadFile = null;
			String value = "";
			try {
			   ReadFile = new FileInputStream ("accountRecords.txt");
			}
			catch (Exception e) {
			   System.out.println (e);
			 }
			
			// READ the file
			BufferedReader ds = new BufferedReader(new InputStreamReader(ReadFile));
			try {
			    value = ds.readLine (); // Read Data from the given File
			}
			catch (IOException e) {
			         System.out.println (e);
			 }
			
			//Close File
			try {
			ReadFile.close ();
			}
			catch (IOException e) {
			System.out.println (e);
			}
			
			return value;
	}

   
   	// HTTP GET request
	private String sendGet(String payerAcc, String payeeAcc, int ammount) throws Exception {
		//call makePayment.php?action=payment&payer=123456789&payee=123456789&ammount=25
		String url = "http://ebz.newpaltz.edu/~n02004019/NPZ_Bank/makePayment.php?action=payment&payee=" + 
		payerAcc + "&payer=" + payeeAcc + "&ammount=" + ammount;
		
		String USER_AGENT = "Mozilla/5.0";
		
		URL obj = new URL(url);
		HttpURLConnection con = (HttpURLConnection) obj.openConnection();
 
		// optional default is GET
		con.setRequestMethod("GET");
 
		//add request header
		con.setRequestProperty("User-Agent", USER_AGENT);
 
		int responseCode = con.getResponseCode();
		System.out.println("\nSending 'GET' request to URL : " + url);
		System.out.println("Response Code : " + responseCode);
 
		BufferedReader in = new BufferedReader(
		        new InputStreamReader(con.getInputStream()));
		String inputLine;
		StringBuffer response = new StringBuffer();
 
		while ((inputLine = in.readLine()) != null) {
			response.append(inputLine);
		}
		in.close();
		
		return response.toString();
	}
   
} // END of CLASS InServer


