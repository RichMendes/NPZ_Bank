
//=================== OS-2 Projects ============================
// CLIENT Site to the Server site
// + Send Data (Question) to the Server Site with FLAG (lock)
// + Receive Data (Answer) from the Server Site
//===============================================================

import java.awt.*;
import java.io.*;
import java.net.*;

public class Client {

   // !!!!!!~~~~~PLEASE CHANGE THIS PORT~~~~~!!!!!!
   //Change the last 2 numbers of this int so that it follows this pattern: 107YY (Where YY is your group id)
   static int Port_from_Server = 10710;
   // !!!!!!~~~~~PLEASE CHANGE THIS PORT~~~~~!!!!!!
   
   //FILE NAMES
   //*************
   
   //CHANGE THESE IF YOU WANT
   //MAKE SURE THEY ARE ALREADY CREATED IN THE CORRECT FOLDER
   //Name of File to send to Server
   String QnameAtC = "bankRequest.dat";   // Question-File name (of DATA=QUESTION) at Client to send to Server
   String AnameAtC = "bankReply.dat";  // Answer-File name used to write the DATA=ANSWER 
   //(received at Client site from the Server)
   
   //A normal confirmation will look like this: BankRequestID:TransferConfirmation:CustomerID
   //BankRequestID and CustomerId is whatever your original request provided.
   //IF there is an error TransferConfirmation will instead be an error message

   //String ServerName = "localhost";
   String ServerName = "ebz.newpaltz.edu";   
   
   //DO NOT CHANGE THESE VARIABLES
   //****************************
   private final boolean debug = false;
   Socket fs = null;
   boolean isConnect = false;
   static int Port_to_Server = 10700;
   String AnameAtS = "bankReplyS.dat";  // Answer-File name used to ask for at Server site
   //Name of File on server end to write to
   String QnameAtS = "bankRequestS.dat";   // Question-File name (of DATA=QUESTION) to be GIVEN to the Server 
   String QflagName = "f-Qc.dat"; // Lock Flag-File for Question (DATA at Client to send to Server)
   String AflagName = "f-Ac.dat"; // Lock Flag-File for ANSWER (DATA at Client to send to Server)
   //****************************
   //DO NOT CHANGE THESE VARIABLES
   
   
  /*=============================================
   * MAIN PART :
   *=============================================*/

   public Client (String host) {
    
   //*************************
   //Connection to the Server
   //**************************
    
    // CHECK the LOCK(FLAG) of the QUESTION until it turns to "1" 
    //........................................................
	   
    //while(true){	//let it run forever? or should client only run this when they have something to send?
	    while (ReadFlag(QflagName)!=1)
	       {
	         System.out.println (" ... Waiting for Data ... its flag is at "+QflagName+"\n");
	       }
	
	 
	    // To Send QUESTION (as a CLIENT) to the Server, exp "Qc.dat"
	    //........................................................
	       System.out.println("Sending file to server ...");
		   SendClientFile(QnameAtS,QnameAtC,Port_to_Server,ServerName, Port_from_Server);
	
	
	    // TAKE the ANSWER (as a CLIENT) from the SERVER
		   System.out.println("Recieving file from server ...");
	       TakeServerFile(AnameAtS,AnameAtC,Port_from_Server,ServerName);
	       
	    // WRITE "1" to the LOCK(FLAG) of the ANSWER 
	    //........................................................
	       WriteFlag(AflagName,"1");
	  
	  //END of connection to the Server
	  //*********************************
	       
    //}	//let it run forever? or should client only run this when they have something to send?
} 


   //================================
   //FUNCTION PART
   //================================


   //...................................................
   //Open Socket
   //..................................................
   public boolean Connect (int port, String hostName) {

      if (isConnect) Disconnect ();

      try {
         //fs = new Socket (hostName, port);
         fs = new Socket(); //@@@
    	 fs.setReuseAddress(true);
         //fs.bind(new InetSocketAddress(hostName, port));
         fs.connect(new InetSocketAddress(hostName, port));
      }
      catch (Exception e) {
         System.out.println (" Unable to open Input-Port socket at : " + hostName + " " + port);
         return false;
      }

      System.out.println  (" Connected to " + hostName + " at port: " +port);
      isConnect = true;
      return true;
   }

   //.................................................
   //Close Socket
  //.................................................

   public void Disconnect () {

      if (isConnect) {
         try {
            fs.close ();
         }
         catch (IOException e) {
               System.out.println ("Exception caught closing socket.");
          }
         System.out.println  (" Disconnected ");
         isConnect = false;
      }
   }

    
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
      return ReturnValue;
   } // end of FUNCTION



   //======================================================================
   //Open a File at Client site, READ data and SEND it to the Server
   //======================================================================

   public void SendClientFile (String nameStS, String nameCtS, int SendPort,String hostName, int RecievePort)

  {
      // OPEN the given File at Client site
      //.........................................................

      FileInputStream ReadFile = null;
         try {
            ReadFile = new FileInputStream (nameCtS);
         }
         catch (Exception e) {
                System.out.println (e);
                System.exit (1);
         }

      // Check if socket is still open
      //..........................................................
      if (!Connect (SendPort,hostName)) {
         System.out.println (" No connection to server.");
         System.exit (1);
      }
      if(debug) System.out.println("Connection made!");
      // Check the stream
      //..........................................................
      PrintStream streamOut = null; // stream from the Client to the socket
      try { 
         streamOut = new PrintStream (fs.getOutputStream());
         if(debug) System.out.println("Creating PrintStream");
      }
      catch (Exception e) {
         System.out.println ("Error at stream from client to the socket: " + e);
         Disconnect();
         System.exit (1);
      }

      //SEND file name
      //.........................................................

      streamOut.println (nameStS);

      //SEND port to server   @@@
      streamOut.println(RecievePort);
      
      // Read Data from the given File and SEND it to the "stream"
      // so that it will go to the Server
      //...........................................................

    //DataInputStream ds = new DataInputStream (ReadFile);
      BufferedReader ds = new BufferedReader(new InputStreamReader(ReadFile));

      while (true) {
            try {
               String s = ds.readLine (); // Read Data from the given File
               if (s == null) break;
               streamOut.println (s); // SEND it to the "streamOut"
            }
            catch (IOException e) {
               System.out.println (e);
               System.exit (1);
            }
      }

      //Close File

      try {
         ds.close ();
      }

      catch (IOException e) {
         System.out.println (e);
      }

      System.out.println(" SENT File " + nameCtS + " to the Server: " + hostName + " at port : " + SendPort);
      Disconnect ();
      System.out.println(" DONE. ");

   }  // end of SendClientFile()


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



//=========================================================================
//      RECEIVE a File from the Server and WRITE to a file at Client site
//=========================================================================

   public void TakeServerFile (String nameSF, String nameCF, int ReceivePort,String hostName) {

	   BufferedReader streamIn = null; // stream from the Client to the socket
      PrintStream streamOut = null;

      // Check if socket is still open
      //..........................................................

      while (!Connect (ReceivePort,hostName)) {	//wait for the outServer to come up
         System.out.println ("No connection to server.");
      }
      if(debug) System.out.println("Trying to create read/write streams for the take server");
      // Check the stream
      //..........................................................
      try {
         //streamIn = new DataInputStream (fs.getInputStream());
    	 streamIn = new BufferedReader(new InputStreamReader(fs.getInputStream()));
         streamOut = new PrintStream (fs.getOutputStream());
      }
      catch (Exception e) {
         System.out.println (e);
         Disconnect();
         System.exit (1);
      }
      if(debug) System.out.println("Streams are now created");
       //SEND file name to read at Server site
      //.........................................................
      streamOut.println (nameSF);
      if(debug) System.out.println("Printed to the out stream");
         // Open the a FILE at Client site to WRITE
         //........................................................

         FileOutputStream wf = null;

         try {
            wf = new FileOutputStream (nameCF);
         }

         catch (Exception e) {
            System.out.println (e);
            System.exit (1);
         }
         
        // Read Data from the "streamIn" and WRITE it to the given File
        //...........................................................

         PrintStream ds = new PrintStream (wf); // Why do we need this ???

         while (true) {
            try {
            	if(debug) System.out.println("Reading in...");
               String s = streamIn.readLine ();
               if(debug) System.out.println("Read: " + s);
               if (s == null) {
            	   if(debug) System.out.println("Read: " + s);
            	   break;
               }
               ds.println (s); 
               if(debug) System.out.println("Writing...");
            }

            catch (IOException e) {
               System.out.println (e);
               break;
            }
         }
         if(debug) System.out.println("Now closing the writeFile");
      //Close File

      try {
         wf.close ();
      }

      catch (IOException e) {
         System.out.println (e);
      }

      System.out.println (" RECEIVING data from the Server at port : " + ReceivePort + " and write it to File " + nameCF + " at port : " +ReceivePort);
      Disconnect ();
      System.out.println (" DONE.");
}

    
   /* Application entry point
    * The first command line argument is the remote host name.
    * @param args - command line arguments
    */
                                          
 public static void main (String args[]) {

      if (args.length == 1) new Client (args[0]);
      else new Client (null);

 } 
                                         
}
