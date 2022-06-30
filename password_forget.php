<?php
require_once("../lib/config.php");
require_once("../lib/constants.php");


use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

$user_name = $_REQUEST['user_name'];
$email = $_REQUEST['email'];
$query = "SELECT user_name,email,user_id,password FROM `user_master` WHERE `email`='" . $email . "' AND `user_name`='" . $user_name . "'";
// echo $query;
// die;

$result = $db->query($query);
$row = mysqli_fetch_assoc($result);
// print_r($row) ;
// die;
$user_id = $row['user_id'];
// echo $user_id;
// die;

// $encrypted_id = md5($user_id);
// echo $encrypted_id;die;
if ($result->num_rows > 0) {




    $token = rand(1000, 99999);
    $update = "UPDATE user_master set reset_link_token='" . $token . "' WHERE email='" . $email . "'";
    // echo $update;
    // die;

    $result = $db->query($update);
    $row = mysqli_fetch_assoc($result);
    $link = "<a href='https://signature.flamingostech.com/submit_otp.html?user_id=" . $user_id . "'>Click To Reset password</a>";
    // echo $update;
    // die();
    require_once("../PHPMailer/src/PHPMailer.php");
    require_once("../PHPMailer/src/Exception.php");
    require_once("../PHPMailer/src/SMTP.php");

    $mail = new PHPMailer();
    // echo "ok";
    // die();
    // try {


    $mail->isSMTP();
    // $mail->Host = "smtp.gmail.com";
    // $mail->Host = "mail.youthid.in";
    $mail->Host = "smtpout.secureserver.net";
    // $mail->Port = 587;
    $mail->Port = 465;
    $mail->SMTPSecure = "ssl";
    $mail->SMTPAuth = true;
    $mail->SMTPDebug = 0;

    $mail->SMTPOptions = array(
        'ssl' => array(
            'verify_peer' => false,
            'verify_peer_name' => false,
            'allow_self_signed' => true
        )
    );

    $mail->Username = 'support@newocoin.com';

    $mail->Password = 'NewoCoin@2021';

    $mail->From      = $email;

    $mail->SetFrom('$email', "Client Request");

    $mail->FromName  = "St Thomas Official";

    $mail->AddAddress('aritra.flamingostech@gmail.com');
    $mail->Subject  =  'Reset Password';
    $mail->Body    = 'Click On This Link to Reset Password  ' . $link . '.' . $token . '';
    // echo $path;die();
    $mail->isHTML(true);
    $mail->Send();
    // return json_encode($return_data);
    // catch (phpmailerException $e) {
    //     echo "mail send fail";
    //     echo $e->errorMessage();
    //     //die;
    //     exit(0);
    // }

    // if ($mail->Send()) {
        $return['key'] = 'S';
        $return['msg'] = 'successfully send a otp to valid email id';
        echo json_encode($return);
    //     echo "Check Your Email and Click on the link sent to your email";
    // } else {
    //     echo "Mail Error - >" . $mail->ErrorInfo;
    // }
} else {
    $return['key'] = 'E';
    $return['msg1'] = 'credential not match';
    echo json_encode($return);
    // echo "Invalid Email Address. Go back";
}

// $email = $_POST['key'];
// $token = $_POST['token'];
// echo $email;
// die();
// $query = "SELECT * FROM `user_master` WHERE `reset_link_token`='" . $token . "' and `email`='" . $email . "'";
// $result = $db->query($query);
// $curDate = date("Y-m-d H:i:s");
// if (mysqli_num_rows($result) > 0) {
//     $row = mysqli_fetch_array($result);
//     if ($row['exp_date'] >= $curDate) {
//     }
// } else {
//     echo "<p>This forget password link has been expired.</p>";
// }
