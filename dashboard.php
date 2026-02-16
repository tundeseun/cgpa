<?php
session_start();
if (isset($_GET["token"])) {
    $_SESSION["token"] = $_GET["token"];
    //echo $_SESSION["token"]."<br>";
}
if (isset($_GET["dept_new"])) {
    $_SESSION["dept_new"] = $_GET["dept_new"];
}
if (isset($_GET["faculty_id"])) {
    $_SESSION["faculty_id"] = $_GET["faculty_id"];
}
if (isset($_GET["matric"])) {
    $_SESSION["matric"] = $_GET["matric"];
}
if (isset($_GET["code"])) {
    $_SESSION["code"] = $_GET["code"];
}
if (isset($_GET["effectivedate"])) {
    $_SESSION["effectivedate"] = $_GET["effectivedate"];
}

if (isset($_GET["user"])) {
    $_SESSION["user"] = $_GET["user"];
}

if (isset($_GET["name"])) {
    $_SESSION["name"] = $_GET["name"];
}

if(isset($_GET["sec"])) {
    $_SESSION["sec"]=$_GET["sec"];
   
}
if(isset($_GET["degree"])) {
$degree=$_GET['degree'];
}
if(isset($_GET["special"])) {
$special=$_GET['special'];
}
if(isset($_GET["ext"])) {
$ext=$_GET['ext'];
}
if(isset($_GET["effect"])) {
$effect=$_GET['effect'];
}
if(isset($_GET["sec"])) {
$sec=$_GET['sec'];
}
if(isset($_GET["user_id"])) {
    $user_id=$_GET['user_id'];
    }
    if(isset($_GET["status"])) {
        $status=$_GET['status'];
        }

if(isset($_GET["p"])) {
$p = $_GET["p"];
$dept = isset($_SESSION["dept_new"]) ? $_SESSION["dept_new"] : ''; // Check if it exists in $_SESSION
$user = isset($_SESSION["user"]) ? $_SESSION["user"] : ''; // Check if it exists in $_SESSION
$sec = isset($_SESSION["sec"]) ? $_SESSION["sec"] : ''; // Check if it exists in $_SESSION
//echo $sec."<br>";
//echo $dept;


if ($p == 1) {
     header("Location: home");
}

if ($p == "head") {
    header("Location: headict");
}
if ($p == "exam") {
    header("Location: examView");
}
if ($p == "bcm") {
    header("Location: bcmView");
}
if ($p == "deen") {
    header("Location: facultyView");
}
if ($p == "unlock") {
    header("Location: unlock");
}
if ($p == "headsec") {
    header("Location: headict_sec");
}
// if ($p == "headsec") {
//     header("Location: updresult/2");
// }

if ($p ==  "pass") {
    header("Location: changePassword");
}

if ($p == "disable"||$p == "enable") {
    header("Location: createHod");
}

if ($p == "disableex"||$p == "enableex") {
    header("Location: external");
}

if ($p == "disablesd"||$p == "enablesd") {
    header("Location: subdean");
}

if ($p == "editresult") {
    header("Location: editresult");
}
if ($p == "deleteresult") {
    header("Location: deleteresult");
}
if ($p == "hod") {
    header("Location: createHod");
}
if ($p == "ex") {
    header("Location: external");
}
if ($p == "sd") {
    header("Location: subdean");
}
if ($p == "assign") {
    header("Location: assign2");
}

if ($p == "chkstudrec") {
    header("Location: chkstudrec    ");
}
if ($p == "lsr") {
    header("Location: liststudrec");
}
if ($p == "stud") {
    header("Location: updresult");
}
if ($p == "couserCode") {
    header("Location: updresultwithcode");
}


if ($p == "setupresolve") {
    header("Location: setupresolve");
}

if ($p == "setupscore") {
    header("Location: setupscore");
}

if ($p == "viewstudent") {
    header("Location: registeredstudent");
}
if ($p == "viewcourse") {
    header("Location: viewcourse");
}
if ($p == "lock") {
    header("Location: lock/2");
}

if ($p == "score") {
    header("Location: score");
}
if ($p == "setupresult") {
    header("Location: setupreport");
}
if ($p == "setupdraft") {
    header("Location: setupdraftreport");
}
if ($p == "graduating") {
    header("Location: graduating");
}
if ($p == "regstatus") {
    header("Location: regstatus");
}
if ($p == "graduating-word") {
    header("Location: graduating/word.php");
}
if ($p == "regstatus-word") {
    header("Location: regstatus/word.php");
}
if ($p == "result") {
    header("Location:result.php?sec2=".$sec.'&degree='.$degree.'&special='.$special.'ext='.$ext.'effect='.$effect);  //48/927/5/458/2019-05-24
    //result.php?degree=48&special=927&sec2=5&ext=458&effect=2019-05-24&send=Submit
}
if ($p == "processresult") {
    header("Location: processresult");
}
if ($p == "ictadmin") {
    header("Location: student_search.php");
}

}

if(isset($_POST['logout'])){
    session_destroy();
    header('Location: login.php');
}
?>
