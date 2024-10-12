<?php
session_start();

if (!isset($_SESSION["agree"])) {
    header("Location: Disclaimer.php");
    exit();
}

$name = $postal = $number = $email = $method = "";
$errors = [];

function ValidateName($name) {
    global $errors;
    if (empty(trim($name))) {
        $errors['Name'] = 'Name cannot be blank.';
        return false;
    }
    return true;
}

function ValidatePostalCode($postalCode) {
    global $errors;
    if (empty(trim($postalCode))) {
        $errors['Postal'] = 'Postal code cannot be blank.';
        return false;
    } elseif (!preg_match('/^[A-Za-z]\d[A-Za-z][\s]?\d[A-Za-z]\d$/', trim($postalCode))) {
        $errors['Postal'] = 'Incorrect Postal Code.';
        return false;
    }
    return true;
}

function ValidatePhone($phone) {
    global $errors;
    if (empty(trim($phone))) {
        $errors['Number'] = 'Phone number cannot be blank.';
        return false;
    } elseif (!preg_match('/^[2-9]\d{2}-[2-9]\d{2}-\d{4}$/', $phone)) {
        $errors['Number'] = 'Incorrect Phone Number.';
        return false;
    }
    return true;
}

function ValidateEmail($email) {
    global $errors;
    if (empty(trim($email))) {
        $errors['Address'] = 'Email cannot be blank.';
        return false;
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors['Address'] = 'Incorrect Email Address.';
        return false;
    }
    return true;
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = $_POST['Name'] ?? "";
    $postal = $_POST['Postal'] ?? "";
    $number = $_POST['Number'] ?? "";
    $email = $_POST['Address'] ?? "";
    $method = $_POST['method'] ?? "";

    $isValid = true;

    if (!ValidateName($name)) {
        $isValid = false;
    }
    if (!ValidatePostalCode($postal)) {
        $isValid = false;
    }
    if (!ValidatePhone($number)) {
        $isValid = false;
    }
    if (!ValidateEmail($email)) {
        $isValid = false;
    }

    if (empty($method)) {
        $errors['method'] = "You must select a contact method.";
        $isValid = false;
    }

    if ($isValid && isset($_POST["next"])) {
        $_SESSION['Name'] = $name;
        $_SESSION['Postal'] = $postal;
        $_SESSION['Number'] = $number;
        $_SESSION['Address'] = $email;
        $_SESSION['method'] = $method;

        if ($method == 'Phone') {
            header("Location: ContactTime.php");
            $_SESSION['choose_phone'] = true;
        } elseif ($method == 'Email') {
            $_SESSION['choose_email'] = true;
            header("Location: DepositCalculator.php");
        }
        exit();
    }
} else {
    if (isset($_SESSION['Name'])) {
        $name = $_SESSION['Name'];
    }
    if (isset($_SESSION['Postal'])) {
        $postal = $_SESSION['Postal'];
    }
    if (isset($_SESSION['Number'])) {
        $number = $_SESSION['Number'];
    }
    if (isset($_SESSION['Address'])) {
        $email = $_SESSION['Address'];
    }
    if (isset($_SESSION['method'])) {
        $method = $_SESSION['method'];
    }
}
?>

<!DOCTYPE html>
<!--
Click nbfs://nbhost/SystemFileSystem/Templates/Licenses/license-default.txt to change this license
Click nbfs://nbhost/SystemFileSystem/Templates/Scripting/EmptyPHPWebPage.php to edit this template
-->

<html>
    <head>
        <meta charset="UTF-8">
        <?php include('./common/header.php'); ?>
        <title>Online Course Registration</title>
    </head>
    <body>
        <div class="container my-5">
            <h1 class="text-center">Customer Information</h1>
            <form action="CustomerInfo.php" method="POST">
                <table class="table table-borderless">
                    <tr>
                        <td class="text-nowrap">Name:</td>
                        <td><input type="text" name="Name" class="form-control" value="<?= htmlspecialchars($name) ?>"/></td>
                        <td><span class="text-danger"><?= $errors['Name'] ?? '' ?></span></td>
                    </tr>
                    <tr>
                        <td class="text-nowrap">Postal Code:</td>
                        <td><input type="text" name="Postal" class="form-control" value="<?= htmlspecialchars($postal) ?>"/></td>
                        <td><span class="text-danger"><?= $errors['Postal'] ?? '' ?></span></td>
                    </tr>
                    <tr>
                        <td class="text-nowrap">Phone Number:<span><br>(nnn-nnn-nnnn)</span></td>
                        <td><input type="text" name="Number" class="form-control" value="<?= htmlspecialchars($number) ?>"/></td>
                        <td><span class="text-danger"><?= $errors['Number'] ?? '' ?></span></td>
                    </tr>
                    <tr>
                        <td class="text-nowrap">Email Address:</td>
                        <td><input type="text" name="Address" class="form-control" value="<?= htmlspecialchars($email) ?>"/></td>
                        <td><span class="text-danger"><?= $errors['Address'] ?? '' ?></span></td>
                    </tr>
                </table>
                <hr />
                <p class="d-inline me-5">Preferred contact Method:</p>
                <input type="radio" name="method" value="Phone" <?= ($method == 'Phone') ? 'checked' : '' ?>/> Phone
                <input type="radio" name="method" value="Email" <?= ($method == 'Email') ? 'checked' : '' ?>/> Email
                <span class="text-danger ms-2" id="method-error"><?= $errors['method'] ?? '' ?></span>
                <br /><br />
                <input type="submit" name="next" value="Next >" class="btn btn-primary" />
            </form>
        </div>

        <?php if ($method == 'Phone'): ?>
            <script>
                document.addEventListener("DOMContentLoaded", function () {
                    var depositLinks = document.querySelectorAll('a[href="DepositCalculator.php"]');

                    depositLinks.forEach(function (link) {
                        link.addEventListener('click', function (event) {
                            event.preventDefault();
                            window.location.href = window.location.href;
                        });
                    });
                });
            </script>
        <?php endif; ?>

        <?php include('./common/footer.php'); ?>
    </body>
</html>