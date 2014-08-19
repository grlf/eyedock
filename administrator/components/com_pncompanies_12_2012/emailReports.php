<?php

// multiple recipients (note the commas)
$to = "\"Todd Zarwell\" <zarwell@gmail.com> ";


// subject
$subject = "Testing CL company reports";

// compose message
$message = "
<html>
  <head>
    <title>CL company report</title>
  </head>
  <body>
    <h1>Acme Corporation</h1>
    <p>Here is an email reporting on your company's products on our Web site. and here's a link to  <a href=\"http://www.cnn.com/\">cnn</a>.
       </p>
  </body>
</html>
";

// To send HTML mail, the Content-type header must be set
$headers = 'From: admin@eyedock.com' . "\r\n" .
$headers .= "MIME-Version: 1.0\r\n";
$headers .= "Content-type: text/html; charset=iso-8859-1\r\n";

// send email
mail($to, $subject, $message, $headers);
?>