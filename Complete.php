<!DOCTYPE html>
<!--
Click nbfs://nbhost/SystemFileSystem/Templates/Licenses/license-default.txt to change this license
Click nbfs://nbhost/SystemFileSystem/Templates/Scripting/EmptyPHPWebPage.php to edit this template
-->
<?php
session_start();  

if (!isset($_SESSION["principalAmount"])) {
    header("Location: Index.php"); 
    exit();
}

include("./common/header.php");
?>

<html>
    <head>
        <meta charset="UTF-8">
        <title></title>
    </head>
    <body>
        <div class="container">	
            <h1>Thank you, <span class="text-primary"><?php echo $_SESSION["Name"] ?></span>, for using our deposit calculation tool.</h1>
            <?php
            if (isset($_SESSION['method'])) {
                if ($_SESSION['method'] == 'Email') {
                    echo "<p>Our customer service will contact you shortly at your email address: <strong>" . htmlspecialchars($_SESSION['Address']) . "</strong>.</p>";
                } elseif ($_SESSION['method'] == 'Phone') {
                    echo "<p>Our customer service will call you tomorrow ";
                    if (isset($_SESSION['contactTime']) && is_array($_SESSION['contactTime'])) {
                        echo "<strong>" . htmlspecialchars(implode(', ', $_SESSION['contactTime'])) . "</strong>";
                    }
                    echo " at <strong>" . htmlspecialchars($_SESSION['Number']) . "</strong>.</p>";
                }
            }
            ?>

            <?php session_destroy(); ?>
        </div>	
    </body>
</html>

<?php include('./common/footer.php'); ?>