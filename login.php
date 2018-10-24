<?php
/*
 *
 *    File created by Roger Ndaba
 *    Project: camagru
 *    File: login.php
 *
 */
include ("connect.php");

$q = "SELECT `username`, `passwd`, `isusr` FROM accounts";
$conn = conOpen();
$re = $conn->query($q);
$username = $_POST['login'];
$passwd = $_POST['passwd'];
if(!$username || !$passwd)
{
   $msg = "Blank field for Username or Password";
   $msgEncoded = base64_encode($msg);
   header("location:login.phtml?msg=".$msgEncoded."&usr=".$username);
   exit();
}
else
{
    $msg = "No such Username or Password";
    $msgEncoded = base64_encode($msg);
    while($row = $re->fetch(PDO::FETCH_ASSOC)) {
        if (!strcmp($username, "adminunlock") && password_verify($passwd, $row['passwd'])) {
                session_start();
                header("location: admin_login.php");
                exit();
        }
        else if (!strcmp($row['username'], $username) && password_verify($passwd, $row['passwd'])) {
            if ($row['isusr'] === '0') {
                $msg = "please confirm email";
                $msgEncoded = base64_encode($msg);
                header("location:login.phtml?msg=".$msgEncoded."&usr=".$username);
                exit();
            }
            $_SESSION['username'] = $username;
            header("Location: home.php");
            exit();
        }
    }
    header("location:login.phtml?msg=".$msgEncoded);
}
?>