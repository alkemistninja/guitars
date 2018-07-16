<?php
  require 'vendor/autoload.php';

  if(isset($_POST['email'])){
    //Recieving Email and Desired Subject
    $email_to = "julianreyes90@gmail.com";
    $email_subject = "WE DID IT!!!!";

    function died($error){
      // When something goes wrong
      echo "Shit somebody fucked up";
      echo "Heres what happened:";
      echo $error."<br /><br />";
      echo "Lets see what we can do about that";
      die();
    }

    $name = $_POST['name']; // Pulls Visitors Name from form
    $email_from = $_POST['email']; // Users email
    $message = $_POST['message']; // Messages duh

    $error_message = "";

    // This is REGEX that checks for standard Email form "abc@xyz.com"
    $email_exp = '/^[A-Za-z0-9._%-]+@[A-Za-z0-9.-]+\.[A-Za-z]{2,4}$/';

    if(!preg_match($email_exp, $email_from)){
      $error_message .='The Email Address does not appear to be valid';
    }

    $string_exp = "/^[A-Za-z .'-]+$/";

    if(!preg_match($string_exp, $name)){
      $error_message .='The Name does not appear to be valid';
    }

    if(!preg_match($email_exp, $email_from)){
      $error_message .='The Message does not appear to be valid';
    }

    if(strlen($error_message) > 0){
      died($error_message);
    }

    $email_message = "Form details below.\n\n";

    function clean_string($string){
      $bad = array("content_type", "bcc:", "to:", "cc:", "href");
      return str_replace($bad, "", $string);
    }

    $email_message .= "Name: ".clean_string($name)."\n";
    $email_message .= "Email: ".clean_string($email)."\n";
    $email_message .= "Message: ".clean_string($message)."\n";

    $request_body = json_decode('{
      "personalizations": [
        {
          "to": [
            {
              "email":' . $email_to . '
            }
          ],
          "subject": ' . $email_subject . '
        }
      ],
      "from": {
        "email": ' . clean_string($email) . '
      },
      "content": [
        {
          "type": "text/plain",
          "value": ' . $email_message . '
        }
      ]
    }');

    $apiKey = getenv('SENDGRID_API_KEY');
    $sg = new \SendGrid($apiKey);

    $response = $sg->client->mail()->send()->post($request_body);
    echo $response->statusCode();
    echo $response->body();
    echo $response->headers();


?>
<!-- Put HTML Here -->

Thank You

<?php

  }
?>
