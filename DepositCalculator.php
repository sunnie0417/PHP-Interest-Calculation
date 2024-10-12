<!DOCTYPE html>
<!--
Click nbfs://nbhost/SystemFileSystem/Templates/Licenses/license-default.txt to change this license
Click nbfs://nbhost/SystemFileSystem/Templates/Scripting/EmptyPHPWebPage.php to edit this template
-->

<?php
session_start();

if (
        !isset($_SESSION["Name"]) ||
        (isset($_SESSION['choose_phone']) && $_SESSION['choose_phone'] === true && (!isset($_SESSION['choose_time']) || $_SESSION['choose_time'] !== true)) ||
        (isset($_SESSION['choose_email']) && $_SESSION['choose_email'] !== true)
) {
    header("Location: CustomerInfo.php");
    exit();
}


$principalAmount = $years = "";
$errors = [];
$balances = [];
$interest = [];

function ValidatePrincipal($amount) {
    return is_numeric($amount) && $amount > 0 ? '' : 'Principal Amount must be numeric and greater than zero.';
}

function ValidateYears($years) {
    return is_numeric($years) && $years >= 1 && $years <= 25 ? '' : 'Incorrect number of years.';
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $principalAmount = $_POST['principalAmount'] ?? "";
    $years = $_POST['years'] ?? "";

    $errors['principalAmount'] = ValidatePrincipal($principalAmount);
    $errors['years'] = ValidateYears($years);

    if (isset($_POST['calculate']) || isset($_POST['next'])) {
        if (empty($errors['principalAmount']) && empty($errors['years'])) {
            $principalAmount = floatval($principalAmount);
            $years = intval($years);
            $interestRate = 0.03;
            $balances = [];
            $interest = [];
            $balance = $principalAmount;

            for ($i = 1; $i <= $years; $i++) {
                $yearInterest = $balance * $interestRate;
                $interest[$i] = $yearInterest;
                $balances[$i] = $balance;
                $balance += $yearInterest;
            }

            $_SESSION['principalAmount'] = $principalAmount;
            $_SESSION['years'] = $years;
            $_SESSION['balances'] = $balances;
            $_SESSION['interest'] = $interest;

            if (isset($_POST['next'])) {
                header('Location: Complete.php');
                exit();
            }
        }
    }

    if (isset($_POST['prev'])) {
        $_SESSION['principalAmount'] = $principalAmount;
        $_SESSION['years'] = $years;

        if (isset($_SESSION['method']) && $_SESSION['method'] == 'Email') {
            header('Location: CustomerInfo.php');
        } else {
            header('Location: ContactTime.php');
        }
        exit();
    }
} else {
    if (isset($_SESSION['principalAmount'])) {
        $principalAmount = $_SESSION['principalAmount'];
    }
    if (isset($_SESSION['years'])) {
        $years = $_SESSION['years'];
    }
    if (isset($_SESSION['balances'])) {
        $balances = $_SESSION['balances'];
    }
    if (isset($_SESSION['interest'])) {
        $interest = $_SESSION['interest'];
    }
}
?>

<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <?php include('./common/header.php'); ?>
    </head>
    <body>
        <div class="container d-flex flex-column align-items-center">
            <form method="post" action="<?= htmlspecialchars($_SERVER['PHP_SELF']); ?>" class="w-50 mt-5">
                <br />
                <h5>Enter principal amount, interest rate and select number of years to deposit</h5>
                <table class="table table-borderless">
                    <tr>
                        <td class="text-nowrap">Principal Amount:</td>
                        <td>
                            <input type="text" name="principalAmount" class="form-control" value="<?= htmlspecialchars($principalAmount) ?>"/>
                        </td>
                        <td><span class="text-danger"><?= $errors['principalAmount'] ?? '' ?></span></td>
                    </tr>
                    <tr>
                        <td class="text-nowrap">Years to Deposit:</td>
                        <td>
                            <select name="years" class="form-select">
                                <option value="">Select one...</option>
                                <?php for ($i = 1; $i <= 25; $i++): ?>
                                    <option value="<?= $i ?>" <?= ($years == $i) ? 'selected' : '' ?>><?= $i ?></option>
                                <?php endfor; ?>
                            </select>
                        </td>
                        <td><span class="text-danger"><?= $errors['years'] ?? '' ?></span></td>
                    </tr>
                </table>

                <br />
                <input class="btn btn-primary" name="prev" type="submit" value="< Previous" />
                <input type="submit" name="calculate" value="Calculate" class="btn btn-primary" />
                <input class="btn btn-primary" name="next" type="submit" value="Complete >" />
            </form>

            <?php if (!empty($balances)): ?>
                <div class="w-75 mt-5">
                    <h5>Following is the result of calculation at the current interest rate of 3%:</h5>
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>Year</th>
                                <th>Principal at Year Start</th>
                                <th>Interest for the Year</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($balances as $year => $balance): ?>
                                <tr>
                                    <td><?= $year ?></td>
                                    <td>$<?= number_format($balance, 2) ?></td>
                                    <td>$<?= number_format($interest[$year], 2) ?></td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            <?php endif; ?>
        </div>

        <?php include('./common/footer.php'); ?>
    </body>
</html>