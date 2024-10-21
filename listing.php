<?php
session_start();
require_once("pdo_connect.php"); // Include your PDO connection class
include 'form.php';

$db = new pdo_connect(); // Create a new instance of your PDO connection

// Fetching all records from the itr_data_tbl
$listingsql = "SELECT * FROM itr_data_tbl";
$result = $db->getQueryAll($listingsql); // Using the getQueryAll method from your PDO class

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Listing-Data</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
</head>

<body>
    <?php include 'navbar.php'; ?>

    <p>
        <?php
        if (isset($_SESSION['msg'])) {
            echo $_SESSION['msg'];
            unset($_SESSION['msg']);
        }
        ?>
    </p>

    <?php if ($result && count($result) > 0): ?>
        <center class='mt-4'>
            <h2><u>ITR Form Data</u></h2>
        </center>
        <div class='container'>
            <table class='table table-light table-hover mt-4 p-3'>
                <tr>
                    <th>Firm Details</th>
                    <th>Person Details</th>
                    <th>Contact</th>
                    <th>Documents</th>
                    <th>Action</th>
                </tr>
                <?php foreach ($result as $row): ?>
                    <tr class='align-middle'>
                        <td>id: <?= $row["id"] ?><br>Pan: <?= $row['user_pan_number'] ?><br>Firm Name: <?= $row['user_firm_name'] ?><br><?= $row['user_firm_address'] ?></td>
                        <td><?= $row["user_name"] ?><br><?= $row['user_father_name'] ?></td>
                        <td><?= $row["user_phone"] ?><br><?= $row['user_email'] ?></td>
                        <td>Pan: <?= $row["user_pan_number"] ?><br>
                            <a target="_blank" href="<?= $row['user_pan_url'] ?>">Pan Card</a><br>
                            <a target="_blank" href="<?= $row['user_aadhar_f_url'] ?>">Aadhar Card</a><br>
                            <a target="_blank" href="<?= $row['user_bank_statement_url'] ?>">Bank Statement</a><br>
                            <a target="_blank" href="<?= $row['user_other_doc_url'] ?>">Other Docs</a>
                        </td>
                        <td>
                            <a href='edit.php?id=<?= $row['id'] ?>' class='btn btn-outline-primary btn-sm'>📝Edit</a><br><br>
                            <a target='_blank' href='preview.php?id=<?= $row['id'] ?>' class='btn btn-outline-primary btn-sm'>🔍Preview</a><br><br>
                            <a href='delete.php?id=<?= $row['id'] ?>' onclick='return confirm("Are you sure you want to delete this user?");' class='btn btn-outline-danger btn-sm'>📥Delete</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </table>
        </div>
    <?php else: ?>
        <center>
            <h5>No results found 😒</h5>
        </center>
    <?php endif; ?>

</body>

</html>