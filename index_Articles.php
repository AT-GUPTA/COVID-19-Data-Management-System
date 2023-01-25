<?php require_once 'database.php';
$db = $conn->prepare('SELECT
CASE WHEN a.authType = "Researcher" THEN (
  SELECT CONCAT(fName, " ", lName)
  FROM User, Researcher, Author
  WHERE a.authID = Author.authID AND Author.rID = Researcher.rID AND Researcher.uID = User.uID)
  WHEN a.authType = "Organization" THEN (
  SELECT organizationName
  FROM Organization org, Author
  WHERE a.authID = Author.authID AND Author.oID = org.oID
) END AS author, publicationDate, majorTopic, minorTopic, summary, article
FROM evc353_1.Article a
ORDER BY publicationDate DESC');
$db->execute();
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>COVID-19 Pandemic Progress System</title>

    <link rel="stylesheet" href="style.css" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-gH2yIJqKdNHPEq0n4Mqa/HGKIhSkIHeL5AyhkYV8i59U5AR6csBvApHHNl/vI1Bx" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.9.1/font/bootstrap-icons.css">
    <link rel="apple-touch-icon" sizes="180x180" href="/Icon/apple-touch-icon.png">
    <link rel="icon" type="image/png" sizes="32x32" href="/Icon/favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="16x16" href="/Icon/favicon-16x16.png">
    <link rel="manifest" href="/Icon/site.webmanifest">
</head>

<body class="bg-light">
    <nav class="navbar navbar-expand-lg bg-dark">
        <div class="container-fluid">
            <a class="navbar-brand navbar-organization" href="index.php">COVID-19 Pandemic Progress System</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarSupportedContent">
                <ul class="navbar-nav me-auto mb-2 mb-lg-0 ">
                    <li class="nav-item ps-5">
                        <a class="navbar-name" aria-current="page" href="Login/login.php">Login <i class="bi bi-person-circle"></i></a>
                    </li>
                    <li class="nav-item ps-5">
                        <a class="navbar-name" aria-current="page" href="sub_author.php">Author Subscription <i class="bi bi-person-plus"></i></a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>


    <div class="container py-2 my-2">
        <ul class="nav nav-tabs">
            <li class="nav-item">
                <a class="nav-link" style="color: grey !important;" aria-current="page" href="index.php">ProStaTer Statistics</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" style="color: grey !important;" href="index_Vaccines.php">Vaccine Statistics</a>
            </li>
            <li class="nav-item">
                <a class="nav-link active" style="color: black !important;" href="index_Articles.php">Articles</a>
            </li>
        </ul>


        <?php while ($row = $db->fetch(PDO::FETCH_ASSOC, PDO::FETCH_ORI_NEXT)) { ?>
            <div class="card my-3" style="width: 100%;">
                <div class="card-body" id="<?= $row['author'] ?>">
                    <h5 class="card-title"><?= $row['majorTopic'] ?></h5>
                    <h6 class="card-subtitle mb-2 lead"><?= $row['minorTopic'] ?> | <?= $row['publicationDate'] ?> </h6>
                    <p class="card-text"><?= $row['summary'] ?></p>
                    <p class="card-text"><?= $row['article'] ?></p>
                    <div class="text-muted">- <?= $row['author'] ?></div>
                </div>
            </div>
        <?php } ?>
    </div>


    <script src="index_Articles.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0/dist/js/bootstrap.min.js" integrity="sha384-ODmDIVzN+pFdexxHEHFBQH3/9/vQ9uori45z4JjnFsRydbmQbmL5t1tQ0culUzyK" crossorigin="anonymous"></script>
</body>

</html>