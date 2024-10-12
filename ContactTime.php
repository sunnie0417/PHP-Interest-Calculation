<?php
session_start();

$errors = [];
$contactTimes = [];

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $contactTimes = $_POST['contactTime'] ?? [];

    if (isset($_POST["next"])) {
        if (empty($contactTimes)) {
            $errors['contactTime'] = "When preferred contact method is phone, you have to select one or more contact times";
        } else {
            $_SESSION['contactTime'] = $contactTimes;
            $_SESSION['choose_time'] = true;
            header("Location: DepositCalculator.php");
            exit();
        }
    } else if (isset($_POST["prev"])) {
        $_SESSION['contactTime'] = $contactTimes;
        header("Location: CustomerInfo.php");
        exit();
    }
} else {
    if (isset($_SESSION['contactTime'])) {
        $contactTimes = $_SESSION['contactTime'];
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
            <h1 class="text-center">Select Contact Times</h1>
            <form action="ContactTime.php" method="POST">
                <div id="contact-times" class="mt-3">
                    <p>When can we contact you? Check all applicable:</p>

                    <?php if (!empty($errors['contactTime'])): ?>
                        <span class="text-danger"><?= htmlspecialchars($errors['contactTime']) ?></span>
                    <?php endif; ?>
                    <div>
                        <?php
                        $timeSlots = [
                            "9:00 am - 10:00 am",
                            "10:00 am - 11:00 am",
                            "11:00 am - 12:00 pm",
                            "12:00 pm - 1:00 pm",
                            "1:00 pm - 2:00 pm",
                            "2:00 pm - 3:00 pm",
                            "3:00 pm - 4:00 pm",
                            "4:00 pm - 5:00 pm",
                            "5:00 pm - 6:00 pm"
                        ];
                        foreach ($timeSlots as $timeSlot):
                            $checked = in_array($timeSlot, $contactTimes) ? 'checked' : '';
                            ?>
                            <input type="checkbox" name="contactTime[]" value="<?= htmlspecialchars($timeSlot) ?>" <?= $checked ?> />
                            <label><?= htmlspecialchars($timeSlot) ?></label><br>
                        <?php endforeach; ?>
                    </div>

                    <input class="btn btn-primary" name="prev" type="submit" value="< Prev" />
                    <input class="btn btn-primary" name="next" type="submit" value="Next >" />
                </div>
            </form>
        </div>

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

        <?php include('./common/footer.php'); ?>
    </body>
</html>
