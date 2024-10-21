<?php
require_once('common/master.php');
require_once("pdo_connect.php");
$db = new pdo_connect();

function test_data($data)
{
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
}

if (isset($_POST['submit'])) {
    $maxFileSize = 200 * 1024;
    $allowedFileExtension = ["jpg", "jpeg", "png", "webp", "pdf"];

    // Function to handle file uploads
    function handleFileUpload($fileKey, $newFileName, $targetDirectory)
    {
        move_uploaded_file($_FILES[$fileKey]['tmp_name'], "upload/$targetDirectory/" . $newFileName);
        return "upload/$targetDirectory/$newFileName";
    }

    if (
        isset($_FILES['aadhar_front_upload_file']) &&
        isset($_FILES['aadhar_back_upload_file']) &&
        isset($_FILES['pan_front_upload_file']) &&
        isset($_FILES['bank_statement_upload_file']) &&
        isset($_FILES['other_doc_upload_file'])
    ) {
        // Generate new file names
        $aadhar_front_new_name = time() . rand(10, 99) . '.' . pathinfo($_FILES['aadhar_front_upload_file']['name'], PATHINFO_EXTENSION);
        $aadhar_back_new_name = time() . rand(10, 99) . '.' . pathinfo($_FILES['aadhar_back_upload_file']['name'], PATHINFO_EXTENSION);
        $pan_front_new_name = time() . '.' . pathinfo($_FILES['pan_front_upload_file']['name'], PATHINFO_EXTENSION);
        $bank_statement_new_name = time() . '.' . pathinfo($_FILES['bank_statement_upload_file']['name'], PATHINFO_EXTENSION);
        $other_doc_new_name = time() . '.' . pathinfo($_FILES['other_doc_upload_file']['name'], PATHINFO_EXTENSION);

        // Validate file extensions and sizes
        foreach ($_FILES as $file) {
            if (!in_array(pathinfo($file['name'], PATHINFO_EXTENSION), $allowedFileExtension) || $file['size'] > $maxFileSize) {
                $_SESSION['msg'] = "<center style='margin-bottom: 20px; color:red;'>Invalid file type or size exceeded 200kb.</center>";
                header("location:index.php");
                exit;
            }
        }

        // Move files to the target directories
        $aadhar_front_file_url = handleFileUpload('aadhar_front_upload_file', $aadhar_front_new_name, 'aadhar');
        $aadhar_back_file_url = handleFileUpload('aadhar_back_upload_file', $aadhar_back_new_name, 'aadhar');
        $pan_front_file_url = handleFileUpload('pan_front_upload_file', $pan_front_new_name, 'pan');
        $bank_statement_file_url = handleFileUpload('bank_statement_upload_file', $bank_statement_new_name, 'bank_statement');
        $other_doc_file_url = handleFileUpload('other_doc_upload_file', $other_doc_new_name, 'other_doc');

        // Database connection
        global $DB_LINK; // Using the mysqli connection from db.config.inc.php
        $pan_number = test_data($_POST['pan_number']);
        $name = test_data($_POST['name']);
        $phone = test_data($_POST['phone_number']);
        $email = test_data($_POST['email']);
        $father_name = test_data($_POST['father_name']);
        $b_name = test_data($_POST['b_name']);
        $itr_paac = test_data($_POST['itr_paac']);
        $ay = test_data($_POST['ay']);
        $b_address = test_data($_POST['b_address']);

        // Prepare SQL statement for insertion
        $sql = "INSERT INTO itr_data_tbl (user_pan_number, user_name, user_phone, user_email, user_father_name, itr_fill_at_paac, ay_itr_fill, user_firm_name, user_firm_address, user_pan_url, user_aadhar_f_url, user_aadhar_b_url, user_bank_statement_url, user_other_doc_url, user_pan_name, user_aadhar_name, user_aadhar_b_name, user_bank_statement_name, user_other_doc_name) 
                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

        $params = [
            $pan_number,
            $name,
            $phone,
            $email,
            $father_name,
            $itr_paac,
            $ay,
            $b_name,
            $b_address,
            $pan_front_file_url,
            $aadhar_front_file_url,
            $aadhar_back_file_url,
            $bank_statement_file_url,
            $other_doc_file_url,
            $pan_front_new_name,
            $aadhar_front_new_name,
            $aadhar_back_new_name,
            $bank_statement_new_name,
            $other_doc_new_name
        ];

        // Execute the insert
        if ($db->executeQueryData($sql, $params)) {

            $_SESSION['msg'] = "<center style='margin-bottom: 20px; color:green;'> <h1>Registration Successful!</h1></center>";
        } else {
            $_SESSION['msg'] = "<center style='margin-bottom: 20px; color:red;'> <h1>Error in Submission</h1></center>";
        }

        header("location:index.php");
        exit;
    }
}
