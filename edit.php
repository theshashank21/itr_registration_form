<?php
require_once("pdo_connect.php");
require("db.config.inc.php");
if (!$DB_LINK_PDO) {
    die("Database connection not established.");
}


if (isset($_GET['id'])) {
    $id = $_GET['id'];

    // Prepare the SQL statement to prevent SQL injection
    $sql = "SELECT * FROM itr_data_tbl WHERE id = :id";
    global $DB_LINK_PDO;  // Get the global PDO connection
    $stmt = $DB_LINK_PDO->prepare($sql);  // Using $DB_LINK_PDO for PDO connection
    ;
    $stmt->bindParam(':id', $id, PDO::PARAM_INT);
    $stmt->execute();

    if ($stmt->rowCount() > 0) {
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        $pan_number = $row['user_pan_number'];
        $name = $row['user_name'];
        $phone = $row['user_phone'];
        $email = $row['user_email'];
        $father_name = $row['user_father_name'];
        $itr_fill_at_paac = $row['itr_fill_at_paac'];
        $ay_itr_fill = $row['ay_itr_fill'];
        $firm_name = $row['user_firm_name'];
        $firm_address = $row['user_firm_address'];
    } else {
        echo "Record not found.";
        exit();
    }
} else {
    echo "No ID specified.";
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Record</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
</head>

<body>
    <center style="margin-top: 10px">
        <h2><u>Edit Your Record</u></h2>
    </center>
    <div class="container mt-4 w-50 mb-3">
        <form class="border border-success mx-auto p-3" enctype="multipart/form-data" action="update.php" method="post">
            <div class="mb-3 mt-2">
                <label for="idnumber" class="form-label">Id</label>
                <input type="text" name="id" class="form-control" id="exampleInputEmail1" value="<?php echo htmlspecialchars($id); ?>" readonly>
            </div>

            <div class="row">
                <div class="mb-3 mt-2 col-md-6">
                    <label for="pannumber" class="form-label">PAN NUMBER</label>
                    <input type="text" name="pan_number" class="form-control" id="example_pan" value="<?php echo htmlspecialchars($pan_number); ?>">
                </div>

                <div class="mb-3 mt-2 col-md-6">
                    <label for="name" class="form-label">Name</label>
                    <input type="text" name="name" class="form-control" id="example_name" value="<?php echo htmlspecialchars($name); ?>">
                </div>
            </div>

            <div class="row">
                <div class="mb-3 mt-2 col-md-6">
                    <label for="phonenumber" class="form-label">Phone</label>
                    <input type="number" name="phone_number" class="form-control" id="example_phone" value="<?php echo htmlspecialchars($phone); ?>">
                </div>

                <div class="mb-3 mt-2 col-md-6">
                    <label for="email" class="form-label">Email</label>
                    <input type="email" name="email" class="form-control" id="example_email" value="<?php echo htmlspecialchars($email); ?>">
                </div>
            </div>

            <div class="row">
                <div class="mb-3 col-md-6">
                    <label for="father_name" class="form-label">Father's Name</label>
                    <input type="text" name="father_name" class="form-control" id="example_fathername" value="<?php echo htmlspecialchars($father_name); ?>">
                </div>
                <div class="mb-3 col-md-6">
                    <label for="b_name" class="form-label">FIRM/BUSINESS Name</label>
                    <input type="text" name="b_name" class="form-control" id="example_bname" value="<?php echo htmlspecialchars($firm_name); ?>">
                </div>
            </div>

            <div class="row">
                <div class="col-md-6">
                    <label for="paac_itr" class="form-label">Last ITR fill at PAAC</label>
                    <div class="form-group">
                        <div class="form-check form-check-inline">
                            <input type="radio" id="damount-1" name="itr_paac" value="yes" class="form-check-input" <?php echo ($itr_fill_at_paac === 'yes') ? 'checked' : ''; ?>>
                            <label for="damount-1" class="form-check-label">Yes</label>
                        </div>
                        <div class="form-check form-check-inline">
                            <input type="radio" id="damount-2" name="itr_paac" value="no" class="form-check-input" <?php echo ($itr_fill_at_paac === 'no') ? 'checked' : ''; ?>>
                            <label for="damount-2" class="form-check-label">No</label>
                        </div>
                    </div>
                </div>

                <div class="col-md-6">
                    <label for="ay_itr" class="form-label">Last A/Y ITR filling</label>
                    <div class="form-group">
                        <div class="form-check form-check-inline">
                            <input type="radio" id="ay-y" name="ay" value="yes" class="form-check-input" <?php echo ($ay_itr_fill === 'yes') ? 'checked' : ''; ?>>
                            <label for="ay-y" class="form-check-label">Yes</label>
                        </div>
                        <div class="form-check form-check-inline">
                            <input type="radio" id="ay-n" name="ay" value="no" class="form-check-input" <?php echo ($ay_itr_fill === 'no') ? 'checked' : ''; ?>>
                            <label for="ay-n" class="form-check-label">No</label>
                        </div>
                    </div>
                </div>
            </div>

            <label for="b_address" class="form-label mt-2">Business Address</label>
            <div class="form-floating mt-2 mb-2">
                <textarea class="form-control" name="b_address" id="b_add"><?php echo htmlspecialchars($firm_address); ?></textarea>
                <label for="floatingTextarea">House No., Street/Colony, City, State</label>
            </div>

            <label for="validationCustom03" class="form-label">Upload Pan Front</label>
            <div class="input-group">
                <input type="file" name="new_pan_front_upload_file" class="form-control mb-3" id="inputGroupFile04" aria-describedby="inputGroupFileAddon04" aria-label="Upload">
            </div>

            <div class="row g-3 mb-3">
                <div class="col-md-6">
                    <label for="validationCustom03" class="form-label">Upload Aadhar Front</label>
                    <div class="input-group">
                        <input type="file" name="new_aadhar_front_upload_file" class="form-control" id="inputGroupFile04" aria-describedby="inputGroupFileAddon04" aria-label="Upload">
                    </div>
                </div>
                <div class="col-md-6">
                    <label for="validationCustom03" class="form-label">Upload Aadhar Back</label>
                    <div class="input-group">
                        <input type="file" name="new_aadhar_back_upload_file" class="form-control" id="inputGroupFile04" aria-describedby="inputGroupFileAddon04" aria-label="Upload">
                    </div>
                </div>
            </div>

            <div class="row g-3 mb-3">
                <div class="col-md-6">
                    <label for="validationCustom03" class="form-label">Attach Bank Statement</label>
                    <div class="input-group">
                        <input type="file" name="new_bank_statement_upload_file" class="form-control" id="inputGroupFile04" aria-describedby="inputGroupFileAddon04" aria-label="Upload">
                    </div>
                </div>
                <div class="col-md-6">
                    <label for="validationCustom03" class="form-label">Attach Other Document</label>
                    <div class="input-group">
                        <input type="file" name="new_other_doc_upload_file" class="form-control" id="inputGroupFile04" aria-describedby="inputGroupFileAddon04" aria-label="Upload">
                    </div>
                </div>
            </div>
            <div class="d-flex justify-content-center mt-3">
                <button type="submit" name="submit" class="btn btn-outline-primary">Update</button>
            </div>
        </form>
    </div>
</body>

</html>