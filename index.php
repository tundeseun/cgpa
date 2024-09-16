<?php
ob_start();
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://kit.fontawesome.com/ba46c9c7c0.js" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="bootstrap.min.css">
    <link rel="icon" href="logo.png" type="image/x-icon" />
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <link href="https://fonts.googleapis.com/css2?family=Dancing+Script:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link href="css/sb-admin-2.min.css" rel="stylesheet">
    
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        .checker {
            color: #0a2b4f;

            margin-top: 2rem;
            font-size: 1.5rem;
            text-align: center;
            font-weight: bold;
        }

        #feechecker {
            background: #0a2b4f;
            border-radius: 50px;
            padding: 3rem;
            display: flex;
            flex-direction: column;
        }

        #feechecker label {
            color: #fff;
        }

        #feechecker form .btn {
            border: 2px solid #FFCA42 !important;
        }


        #myTable th {
            text-align: left !important;
            background: #0a2b4f;
            color: #fff;

        }

        #myTable td {
            text-align: left !important;
        }

        .table th:first-child {
            border-top-left-radius: 10px;

        }

        .table th:last-child {
            border-top-right-radius: 10px;

        }

        .table tr:hover {
            background-color: #01314852;
            border: 2px solid #f1f1f1 !important;
            color: #000;
        }

        .table a {
            color: #000;

        }

        #navchecker {
            background: #fff;
            display: flex;
            padding-top: 0.5rem;
            padding-bottom: 0.5rem;
            justify-content: center;
            align-items: center;
            text-align: center;
        }



        .left {
            background: #0a2b4f;
        }

        .lr {
            display: flex;
        }

        #form {
            background: #fff;

            padding: 3rem;
            display: flex;
            flex-direction: column;
        }

        #form label {
            color: #0a2b4f;
            display: block;
            font-weight: bold;
        }


        #form form .btn {
            background: #e74a3b;
            border: 1px solid #e74a3b !important;
            transition: 0.3s ease-in-out !important;
            width: 50% !important;
            text-align: center !important;
            padding-left: 0 !important;
        }

        #form form .btn:hover {
            background: #0a2b4f;
            border: 1px solid #0a2b4f !important;
            color: #fff;

        }

        .left {
            display: flex;
            justify-content: center;
            align-items: center;
            color: #fff;
            background: url(img/the_college.jpg) no-repeat center center/cover;


        }

        .left p {
            font-family: 'Dancing Script', cursive;
            font-size: 2rem;
            padding: 1rem;
            text-align: center;
            font-weight: 700;
            color: #fff;

        }

        .right #form {
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;

        }

        .right #form i {
            background: #e74a3b;
            color: #fff;
            font-size: 4rem;
            padding: 1.5rem;
            border-radius: 5rem;
        }

        .box {
            margin: auto;
            max-width: 850px;
            padding: 1rem;
            padding-bottom: 0 !important;
            overflow: auto;

        }

        .box input {
            padding-left: 2rem !important;
            margin-bottom: 0.5rem;
            border-radius: 2rem !important;
            outline: none;
            border: 1px solid #0a2b4f;



        }

        .mr {
            margin-right: 1rem;
        }

        .ml {
            margin-left: 1rem;
        }
    </style>
    <link rel="stylesheet" href="style.css">
    <title>The Postgraduate College</title>
</head>

<body>
    <nav id="navchecker">
        <div class="mr">
            <img style="width:40px" src="img/ui-logo.png" class="logo">
        </div>

        <div>
            <h1 class="h3 m-0 font-weight-100 text-primary" style="">University of Ibadan,<br>The&nbsp;Postgraduate&nbsp;College</h1>

        </div>
        <div class="ml">
            <img style="width:50px" src="img/logo.png" class="logo">
        </div>





    </nav>
    <section>

        <div class="box lr">

            <div class="left">
                <p>Welcome to the Result Processing Portal</p>
            </div>

            <div class="right">
                <div id="form">
                    <i class="fas fa-user"></i>
                    <form method="post">
                        <div class="form-group">
                            <label for="username">Enter Username:</label>
                            <input type="text" placeholder="Username" name="username">
                        </div>
                        <div class="form-group">
                            <label for="password">Enter Password:</label>
                            <input type="password" placeholder="Password" name="password">
                        </div>

                        <input type='submit' value='Sign in' name='send' class='btn'>
                    </form>
                </div>
            </div>
        </div>
        <?php
        include('function/script.php');

        function authenticateUser($username, $password, $conn)
        {
            if ($username == 'HeadICT') {
                checkHeadIct($username, $password, $conn);
            } elseif ($username == 'Exams') {
                checkExams($username, $password, $conn);
            } elseif ($username == 'BCM') {
                checkBCM($username, $password, $conn);
            } else {
                ob_start();
                checkuser($username, $password, $conn);
                $output = ob_get_clean();
                if (strpos($output, 'Login Successful') === false) {
                    ob_start();
                    checkFacUser($username, $password, $conn);
                    $output = ob_get_clean();
                }
                echo $output;
            }
        }

        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $username = $_POST['username'];
            $password = $_POST['password'];
            authenticateUser($username, $password, $conn);
        }
        ?>
    </section>




    <footer id="main-footer" class="bg-light" onmouseover="closeCity(event, 'Paris')">

        <p>Copyright &copy;<?php echo date("Y"); ?>, Postgraduate College, All Rights Reserved</p>
    </footer>


</body>

</html>