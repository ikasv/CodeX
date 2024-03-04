
if(isset($_POST['submit_form'])){
    $contact_email = $_POST['contact_email'];
     $username = $_POST['username'];
     $phone = $_POST['phone'];
     $message = $_POST['message'];
     $company = $_POST['company'];
  $msg =    "<table style='border:1px solid gray'>
                <tr>
                    <th></th>
                    <th></th>
                </tr>
                <tr>
                    <td>
                        Name:
                    </td>
                    <td>
                        $username
                    </td>
                </tr>
                <tr>
                    <td>
                        Email:
                    </td>
                    <td>
                        $contact_email
                    </td>
                </tr>
                <tr>
                    <td>
                        Mobile:
                    </td>
                    <td>
                        $phone
                    </td>
                </tr>
                <tr>
                    <td>
                        Company:
                    </td>
                    <td>
                        $company
                    </td>
                </tr>
                <tr>
                    <td>
                        Message:
                    </td>
                    <td>
                        $message
                    </td>
                </tr>
            </table>
            ";
  $msg = wordwrap($msg,70);
  
    $headers  = "From: primeogps\r\n";
    $headers .= "CC: susan@example.com\r\n";
    $headers .= "MIME-Version: 1.0\r\n";
    $headers .= "Content-Type: text/html; charset=UTF-8\r\n";

    // mail("vikas.softechure@gmail.com",$username,$msg, $headers);
  mail("primeogps@gmail.com",$username,$msg);
}
