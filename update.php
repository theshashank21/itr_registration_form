<?php
require_once("pdo_connect.php"); // Change to your PDO connect file
require_once("db.config.inc.php"); // Include your database config file

if (isset($_POST['submit'])) {
    $id = $_POST['id'];
    $maxFileSize = 200 * 1024;
    $allowedFileExtension = ["jpg", "jpeg", "png", "webp", "pdf"];

    // Function to check if a file is uploaded and valid
    function isFileUploaded($fileKey)
    {
        return isset($_FILES[$fileKey]) && $_FILES[$fileKey]['error'] == 0;
    }

    // Function to retrieve file details
    function getFileDetail($fileKey, $detail)
    {
        return $_FILES[$fileKey][$detail] ?? '';
    }

    // Prepare statements for file updates
    $queries = [
        'pan' => [
            'file_key' => 'new_pan_front_upload_file',
            'db_columns' => ['user_pan_url', 'user_pan_name'],
            'upload_dir' => 'upload/pan',
        ],
        'aadhar_front' => [
            'file_key' => 'new_aadhar_front_upload_file',
            'db_columns' => ['user_aadhar_f_url', 'user_aadhar_name'],
            'upload_dir' => 'upload/aadhar',
        ],
        'aadhar_back' => [
            'file_key' => 'new_aadhar_back_upload_file',
            'db_columns' => ['user_aadhar_b_url', 'user_aadhar_b_name'],
            'upload_dir' => 'upload/aadhar',
        ],
        'bank_statement' => [
            'file_key' => 'new_bank_statement_upload_file',
            'db_columns' => ['user_bank_statement_url', 'user_bank_statement_name'],
            'upload_dir' => 'upload/bank_statement',
        ],
        'other_doc' => [
            'file_key' => 'new_other_doc_upload_file',
            'db_columns' => ['user_other_doc_url', 'user_other_doc_name'],
            'upload_dir' => 'upload/other_doc',
        ],
    ];

    $sub_queries = [];

    foreach ($queries as $key => $query) {
        if (isFileUploaded($query['file_key'])) {
            $file_tmp = getFileDetail($query['file_key'], 'tmp_name');
            if ($file_tmp != '') {
                $file_ext = pathinfo(getFileDetail($query['file_key'], 'name'), PATHINFO_EXTENSION);
                $new_file_name = time() . '' . rand(10, 99) . '.' . $file_ext;

                // Retrieve existing file details
                $stmt = $DB_LINK_PDO->prepare("SELECT {$query['db_columns'][0]}, {$query['db_columns'][1]} FROM itr_data_tbl WHERE id = ?");
                $stmt->execute([$id]);
                $result = $stmt->fetch(PDO::FETCH_ASSOC);

                $old_file_path = $result[$query['db_columns'][0]];

                // Remove old file if it exists
                if (file_exists($old_file_path)) {
                    unlink($old_file_path);
                }

                // Move new file to the appropriate directory
                move_uploaded_file($file_tmp, "{$query['upload_dir']}/{$new_file_name}");

                $sub_queries[] = "{$query['db_columns'][0]} = '{$query['upload_dir']}/{$new_file_name}', {$query['db_columns'][1]}='{$new_file_name}'";
            }
        }
    }

    $pan_number = $_POST['pan_number'];
    $name = $_POST['name'];
    $phone = $_POST['phone_number'];
    $email = $_POST['email'];
    $father_name = $_POST['father_name'];
    $firm_name = $_POST['b_name'];
    $firm_address = $_POST['b_address'];
    $itr_paac = $_POST['itr_paac'];
    $ay = $_POST['ay'];

    // Prepare the update statement
    $sql = "UPDATE itr_data_tbl SET 
        user_pan_number = :pan_number, 
        user_name = :name, 
        user_phone = :phone, 
        user_email = :email, 
        user_father_name = :father_name, 
        user_firm_name = :firm_name, 
        user_firm_address = :firm_address, 
        itr_fill_at_paac = :itr_paac,
        ay_itr_fill = :ay" .
        (count($sub_queries) > 0 ? ', ' . implode(', ', $sub_queries) : '') .
        " WHERE id = :id";

    $stmt = $DB_LINK_PDO->prepare($sql);
    $stmt->execute([
        ':pan_number' => $pan_number,
        ':name' => $name,
        ':phone' => $phone,
        ':email' => $email,
        ':father_name' => $father_name,
        ':firm_name' => $firm_name,
        ':firm_address' => $firm_address,
        ':itr_paac' => $itr_paac,
        ':ay' => $ay,
        ':id' => $id,
    ]);

    if ($stmt->rowCount()) {
        $_SESSION['msg'] = "<center style='margin-top: 8px; color: green'><h5>Record Updated Successfully</h5></center>";
    } else {
        $_SESSION['msg'] = "<center style='margin-top: 8px; color: red'><h5>Error updating record.</h5></center>";
    }

    header("location: listing.php");
    exit();
} else {
    echo "Invalid request.";
}
