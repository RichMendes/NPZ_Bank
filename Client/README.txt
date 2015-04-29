Author: Richard Mendes (Original code provided by: Prof. Pham)

Please read all of this before you try to use this client as you need to change at least 1 variable.
If you choose to change some of the other variables and this program stops working redownload it and
try again.

!!!!!!~~~~~PLEASE CHANGE THIS PORT~~~~~!!!!!!
Change the last 2 numbers of this int so that it follows this pattern: 107YY (Where YY is your group id)
   static int Port_from_Server = 10701;
!!!!!!~~~~~PLEASE CHANGE THIS PORT~~~~~!!!!!!
   
A normal confirmation will look like this: BankRequestID:TransferConfirmation:CustomerID
BankRequestID and CustomerId is whatever your original request provided.
IF there is an error TransferConfirmation will instead be an error message