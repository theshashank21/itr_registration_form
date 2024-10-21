<?php
require_once("pdo_connect.php"); // Change to your PDO connect file
require_once("db.config.inc.php"); // Include your database config file

if (isset($_GET['id'])) {
    $id = $_GET['id'];

    // Fetch record data
    $sql_query = "SELECT id, user_pan_number, user_name, user_phone, user_email, user_father_name, itr_fill_at_paac, ay_itr_fill, user_firm_name, user_firm_address, user_pan_url, user_aadhar_f_url, user_aadhar_b_url, user_bank_statement_url, user_other_doc_url, user_aadhar_name, user_aadhar_b_name, user_other_doc_name, user_pan_name, user_bank_statement_name FROM itr_data_tbl WHERE id = ?";
    $stmt = $DB_LINK_PDO->prepare($sql_query);
    $stmt->execute([$id]);
    $record = $stmt->fetch(PDO::FETCH_ASSOC);
} else {
    echo "Invalid request.";
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Preview - ITR Form</title>
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        .table-outline {
            border: 2px solid #000;
            border-collapse: collapse;
        }

        @media print {
            .no-print {
                display: none;
            }
        }

        .img-preview {
            width: 350px;
            height: 400px;
            object-fit: cover;
            display: block;
            margin: 0 auto;
        }

        .img-preview {
            max-width: 100%;
            height: auto;
        }
    </style>
</head>

<body>
    <div class="container mt-4">
        <h1 class="text-center"><u>ITR Form Preview</u></h1><br><br>

        <h2>User Information</h2>
        <table class="table table-bordered table-hover table-outline">
            <tbody>
                <tr>
                    <th>Full Name</th>
                    <td><?php echo htmlspecialchars($record['user_name']); ?></td>
                </tr>
                <tr>
                    <th>Father's Name</th>
                    <td><?php echo htmlspecialchars($record['user_father_name']); ?></td>
                </tr>
                <tr>
                    <th>Phone</th>
                    <td><?php echo htmlspecialchars($record['user_phone']); ?></td>
                </tr>
                <tr>
                    <th>Email</th>
                    <td><?php echo htmlspecialchars($record['user_email']); ?></td>
                </tr>
            </tbody>
        </table>

        <h2>Firm Details</h2>
        <table class="table table-hover table-bordered table-outline">
            <tbody>
                <tr>
                    <th>Pan Number</th>
                    <td><?php echo htmlspecialchars($record['user_pan_number']); ?></td>
                </tr>
                <tr>
                    <th>Firm Name</th>
                    <td><?php echo htmlspecialchars($record['user_firm_name']); ?></td>
                </tr>
                <tr>
                    <th>Firm Address</th>
                    <td><?php echo htmlspecialchars($record['user_firm_address']); ?></td>
                </tr>
            </tbody>
        </table>

        <h2>Uploaded Documents</h2>
        <table class="table table-hover">
            <thead>
                <tr>
                    <th>Document</th>
                    <th>Image</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>PAN Card</td>
                    <td>
                        <a href="<?php echo htmlspecialchars($record['user_pan_url']); ?>" target="_blank">
                            <img src="<?php echo htmlspecialchars($record['user_pan_url']); ?>" alt="PAN Card" class="img-preview">
                        </a>
                    </td>
                </tr>
                <tr>
                    <td>Aadhar Card (Front)</td>
                    <td>
                        <a href="<?php echo htmlspecialchars($record['user_aadhar_f_url']); ?>" target="_blank">
                            <img src="<?php echo htmlspecialchars($record['user_aadhar_f_url']); ?>" alt="Aadhar Card Front" class="img-preview">
                        </a>
                    </td>
                </tr>
                <tr>
                    <td>Aadhar Card (Back)</td>
                    <td>
                        <a href="<?php echo htmlspecialchars($record['user_aadhar_b_url']); ?>" target="_blank">
                            <img src="<?php echo htmlspecialchars($record['user_aadhar_b_url']); ?>" alt="Aadhar Card Back" class="img-preview">
                        </a>
                    </td>
                </tr>
                <tr>
                    <td>Bank Statement</td>
                    <td>
                        <a href="<?php echo htmlspecialchars($record['user_bank_statement_url']); ?>" target="_blank">
                            <img src="<?php echo htmlspecialchars($record['user_bank_statement_url']); ?>" alt="Bank Statement" class="img-preview">
                        </a>
                    </td>
                </tr>
                <tr>
                    <td>Other Documents</td>
                    <td>
                        <a href="<?php echo htmlspecialchars($record['user_other_doc_url']); ?>" target="_blank">
                            <img src="<?php echo htmlspecialchars($record['user_other_doc_url']); ?>" alt="Other Documents" class="img-preview">
                        </a>
                    </td>
                </tr>
            </tbody>
        </table>
        <br>
        <div class="text-center no-print mb-4">
            <a href="#" onclick="window.print();" class="btn btn-primary">📥 Download as PDF</a>
        </div>
    </div>

    <!-- Bootstrap JS (Optional) -->
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>

</html>