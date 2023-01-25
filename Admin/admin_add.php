<?php
session_start();
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] == false) {
    header("Location: ../index.php");
}

require_once '../database.php';
$db = $conn->prepare('SELECT MAX(uID) FROM evc353_1.User');
$db->execute();
$newrow = ($db->fetch())[0] + 1;

if (isset($_POST["addbtn"])) {
    $emailList = $conn->prepare('SELECT emailAddress 
                                FROM evc353_1.User');
    $emailList->execute();
    $loop = true;
    while ($loop && $row = $emailList->fetch(PDO::FETCH_ASSOC, PDO::FETCH_ORI_NEXT)) {
        if (strcmp($_POST['email'], $row['emailAddress']) == 0) {
            $loop = false;
            break;
        }
    }
    if ($loop) {
        $user = $conn->prepare('INSERT INTO evc353_1.User 
                                VALUES(:userid, :utype, :fname, :lname, :citizenship, :email, :phone, :organization, :birthdate, "Active", null)');

        $user->bindParam(':userid', $newrow);
        $user->bindParam(':utype', $_POST["utype"]);
        $user->bindParam(':fname', $_POST["fname"]);
        $user->bindParam(':lname', $_POST["lname"]);
        $user->bindParam(':citizenship', $_POST["citizenship"]);
        $user->bindParam(':email', $_POST["email"]);
        $user->bindParam(':phone', $_POST["phone"]);
        $user->bindParam(':organization', $_POST["organization"]);
        $user->bindParam(':birthdate', $_POST["birthdate"]);

        $user->execute();

        if (strcmp($_POST["utype"], "Regular") != 0) {
            $user = $conn->prepare('INSERT INTO evc353_1.Special_User 
                                VALUES(:userid, :userName, :pass)');
            $user->bindParam(':userid', $newrow);
            $user->bindParam(':userName', $_POST["username"]);
            $user->bindParam(':pass', $_POST["password"]);
            $user->execute();
        }
        header("Location: admin.php");
    }
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>COVID-19 Pandemic Progress System</title>

    <link rel="stylesheet" href="../style.css" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-gH2yIJqKdNHPEq0n4Mqa/HGKIhSkIHeL5AyhkYV8i59U5AR6csBvApHHNl/vI1Bx" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.9.1/font/bootstrap-icons.css">
    <link rel="apple-touch-icon" sizes="180x180" href="../Icon/apple-touch-icon.png">
    <link rel="icon" type="image/png" sizes="32x32" href="../Icon/favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="16x16" href="../Icon/favicon-16x16.png">
</head>

<body class="bg-light">
    <nav class="navbar navbar-expand-lg bg-dark">
        <div class="container-fluid">
            <a class="navbar-brand navbar-organization" href="../index.php">COVID-19 Pandemic Progress System</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarSupportedContent">
                <ul class="navbar-nav me-auto mb-2 mb-lg-0 ">
                    <li class="nav-item ps-5">
                        <a class="navbar-name" aria-current="page" href="admin.php">Admin Page <i class="bi bi-lock"></i></a>
                    </li>
                    <li class="nav-item ps-5">
                        <a class="navbar-name" style="color: white !important; pointer-events: none;" aria-current="page" href="admin_add.php">Add Users <i class="bi bi-plus-circle"></i></a>
                    </li>
                    <li class="nav-item ps-5">
                        <a class="navbar-name" aria-current="page" href="admin_edit.php">Edit Users <i class="bi bi-pencil-square"></i></a>
                    </li>
                    <li class="nav-item ps-5">
                        <a class="navbar-name" aria-current="page" href="admin_suspend.php">Suspend Users <i class="bi bi-dash-circle"></i></a>
                    </li>
                    <li class="nav-item ps-5">
                        <a class="navbar-name" aria-current="page" href="../sub_author.php">Author Subscription <i class="bi bi-person-plus"></i></a>
                    </li>
                    <li class="nav-item ps-5">
                        <a class="navbar-name" aria-current="page" href="../Login/logout.php">Logout <i class="bi bi-box-arrow-right"></i></a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <div class="container pt-3">
        <h1 class="display-4">ADD USER</h1>
        <form class="form-inline" action="admin_add.php" method="POST">
            <div class="row mt-2">
                <div class="input-group col-lg mt-2 my-md-none">
                    <label for="fname" class="input-group-text"><i class="bi bi-person"></i></label>
                    <input id="fname" name="fname" type="text" class="form-control" maxlength="25" placeholder="First Name" autocomplete="off" required />
                    <input id="lname" name="lname" type="text" class="form-control" maxlength="25" placeholder="Last Name" autocomplete="off" required />
                </div>
                <div class="input-group col-lg mt-2 my-md-none">
                    <label for="email" class="input-group-text"><i class="bi bi-envelope"></i></label>
                    <input id="email" name="email" type="text" class="form-control" maxlength="50" placeholder="Email" autocomplete="off" required />
                </div>
            </div>

            <div class="row">
                <div class="input-group col-lg mt-2 my-md-none">
                    <label for="birthdate" class="input-group-text"><i class="bi bi-calendar-plus"></i></label>
                    <input id="birthdate" name="birthdate" type="date" class="form-control" max="2013-01-01" placeholder="Birthdate" autocomplete="off" required />
                </div>
                <div class="input-group col-lg mt-2 my-md-none">
                    <label for="phone" class="input-group-text"><i class="bi bi-phone"></i></label>
                    <input id="phone" name="phone" type="tel" class="form-control" maxlength="20" placeholder="Phone Number" autocomplete="off" required />
                </div>
            </div>

            <div class="row">
                <div class="input-group col-lg mt-2 my-md-none">
                    <label for="username" class="input-group-text"><i class="bi bi-emoji-sunglasses"></i></label>
                    <input id="username" name="username" type="text" class="form-control" max="2013-01-01" maxlength="20" placeholder="Username" autocomplete="off" />
                </div>
                <div class="input-group col-lg mt-2 my-md-none">
                    <label for="password" class="input-group-text"><i class="bi bi-key"></i></label>
                    <input id="password" name="password" type="password" class="form-control" maxlength="16" minlength="8" placeholder="Password" autocomplete="off" />
                </div>
            </div>

            <div class="row">
                <div class="input-group col-lg mt-2 my-md-none">
                    <label for="utype" class="input-group-text">User Type</label>
                    <select class="form-select d-none d-sm-inline" name="utype" id="utype">
                        <option value="Regular">Regular</option>
                        <option value="Admin">Admin</option>
                        <option value="Researcher">Researcher</option>
                        <option value="Delegate">Delegate</option>
                    </select>
                </div>
                <div class="input-group col-lg mt-2 my-md-none">
                    <label for="citizenship" class="input-group-text"><i class="bi bi-flag"></i></label>
                    <input id="citizenship" name="citizenship" type="text" class="form-control" maxlength="25" placeholder="Citizenship" autocomplete="off" required />
                </div>
            </div>

            <div class="input-group col-lg mt-2 my-md-none">
                <label for="organization" class="input-group-text"><i class="bi bi-building"></i></label>
                <input id="organization" name="organization" type="text" class="form-control" maxlength="25" placeholder="Organization" autocomplete="off" />
            </div>

            <button type="submit" class="btn btn-outline-dark mt-3" name="addbtn" id="submit">
                Add User!
            </button>
        </form>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0/dist/js/bootstrap.min.js" integrity="sha384-ODmDIVzN+pFdexxHEHFBQH3/9/vQ9uori45z4JjnFsRydbmQbmL5t1tQ0culUzyK" crossorigin="anonymous"></script>
</body>

</html>