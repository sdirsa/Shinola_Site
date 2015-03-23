<?php

  //new google recaptcha verify.
        $name;$email;$message;$captcha;
        if(isset($_POST['name'])){
          $visitor_email=$_POST['name'];
        }if(isset($_POST['email'])){
          $visitor_email=$_POST['email'];
        }if(isset($_POST['message'])){
          $message=$_POST['message'];
        }if(isset($_POST['g-recaptcha-response'])){
          $captcha=$_POST['g-recaptcha-response'];
        }
        if(!$captcha){
          echo 
            '<h3 style="font-family: sans-serif; text-align: center; color: #555; margin-top: 60px;">Check the captcha verification box before sending.</h3>
            <h4 style="font-family: sans-serif; text-align: center; color: #555; margin-top: 30px;">This window can be closed.</h4>
            <a href="http://it.ojp.gov/default.aspx?area=privacy&page=1285"><p style="font-family: sans-serif; font-size: 12px; text-align: center; color: #555; margin-top: 60px;">U.S. Department of Justice, Electronic Communications Privacy Act of 1986 (ECPA)</p></a>';

          exit;
        }
        $response=file_get_contents("https://www.google.com/recaptcha/api/siteverify?secret=6LdFkAATAAAAABrePjDU2LD5FqtPl3zR-1qdJGp7=".$captcha."&remoteip=".$_SERVER['REMOTE_ADDR']);
        if($response.success==false)
        {
          echo 
            '<h3 style="font-family: sans-serif; font-style: bold; text-align: center; color: #555; margin-top:30px;">Captcha verification has failed.</h3>
            <h4 style="font-family: sans-serif; font-style: bold; text-align: center; color: #555; margin-top: 30px;">This window can be closed.</h4>
            <a href="http://it.ojp.gov/default.aspx?area=privacy&page=1285"><p style="font-family: sans-serif; font-size: 12px; text-align: center; color: #555; margin-top: 60px;">U.S. Department of Justice, Electronic Communications Privacy Act of 1986 (ECPA)</p></a>';
        }else
        {
          echo '<h2 style="font-family: sans-serif; text-align: center; color: #555; margin-top:30px;">Thank you for the message.</h2>';
        }
  //END new google recaptcha verify.




if(!isset($_POST['submit']))
{
	//This page should not be accessed directly. Need to submit the form.
	echo "error; you need to submit the form!";
}
$name = $_POST['name'];
$visitor_email = $_POST['email'];
$message = $_POST['message'];

//Validate first
if(empty($name)||empty($visitor_email)) 
{
    echo "Name and email are mandatory!";
    exit;
}

if(IsInjected($visitor_email))
{
    echo "Bad email value!";
    exit;
}

$email_from = 'steve@shinolastudio.com'; //<== update the email address
$email_subject = "New Shinola Form Submission";
$email_body = "You have received a new message from:\r\n $name\n\n".
    "Sender:\r\n $visitor_email\n\n".
    "Message:\r\n $message\n\n -End of message\n\n".
    
$to = "steve@shinolastudio.com"; //<== update the email address
$headers = "From: $email_from \r\n";
$headers .= "Reply-To: $visitor_email \r\n";
//Send the email!
mail($to,$email_subject,$email_body,$headers);
//done. redirect to thank-you page.
header('Location: thank-you.html');


// Function to validate against any email injection attempts
function IsInjected($str)
{
  $injections = array('(\n+)',
              '(\r+)',
              '(\t+)',
              '(%0A+)',
              '(%0D+)',
              '(%08+)',
              '(%09+)'
              );
  $inject = join('|', $injections);
  $inject = "/$inject/i";
  if(preg_match($inject,$str))
    {
    return true;
  }
  else
    {
    return false;
  }
}
   
?> 