<?php
session_start();
$errorMessage = "";
$agree = "";

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST["next"])) {
    if (isset($_POST["agree"])) {
        $agree = $_POST["agree"];
        $_SESSION["agree"] = $agree;
        header("Location: CustomerInfo.php");
        exit();
    } else {
        $errorMessage = "You must agree to the terms and conditions!";
    }
} else {
    if (isset($_SESSION["agree"]) && $_SESSION["agree"] == "1") {
        $agree = "1";
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
            <h1 class="text-center">Terms and Conditions</h1>
            <form action="Disclaimer.php" method="POST">
                <table border="1" cellpadding="8" cellspacing="0" style="width: 100%;" bordercolor="#D3D3D3">
                    <tr>
                        <td style="padding: 15px;">I agree to abide by the Bank's Terms and Conditions and rules in force and the changes thereto in Terms and Conditions from time to time relating to my account as communicated and made available on the Bank's website.</td>
                    </tr>
                    <tr>
                        <td style="padding: 15px;">I agree that the bank before opening any deposit account, will carry out a due diligence as required under Know Your Customer guidelines of the bank. I would be required to submit necessary documents or proofs, such as identity, address, photograph and any such information, I agree to submit the above documents again at periodic intervals, as may be required by the Bank.</td>
                    </tr>
                    <tr>
                        <td style="padding: 15px;">I agree that the Bank can at its sole discretion, amend any of the services/facilities given in my account either wholly or partially at any time by giving me at least 30 days notice and/or provide an option to me to switch to other services/facilities.</td>
                    </tr>
                </table>
                <br />
                <?php
                if (!empty($errorMessage)) {
                    echo '<p class="text-danger">' . htmlspecialchars($errorMessage) . '</p>';
                }
                ?>
                <div class="form-check mb-3">
                    <input type="checkbox" class="form-check-input" id="agreeCheckbox" name="agree" value="1" <?php echo $agree == "1" ? 'checked' : ''; ?>>
                    <label class="form-check-label" for="agreeCheckbox">I have read and agree with the terms and conditions</label>
                </div>
                <br />
                <input type="submit" name="next" value="Start >" class="btn btn-primary" />
            </form>
        </div>

        <?php include('./common/footer.php'); ?>
    </body>
</html>