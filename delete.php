<?php
require_once("pdo_connect.php"); // Change to your PDO connect file
require_once("db.config.inc.php"); // Include your database config file
session_start();

if (isset($_GET['id'])) {
    $id = $_GET['id'];

    // Fetch record data before deletion
    $sql_query = "SELECT id, user_pan_number, user_name, user_phone, user_email, user_father_name, itr_fill_at_paac, ay_itr_fill, user_firm_name, user_firm_address, user_pan_url, user_aadhar_f_url, user_aadhar_b_url, user_bank_statement_url, user_other_doc_url, user_aadhar_name, user_aadhar_b_name, user_other_doc_name, user_pan_name, user_bank_statement_name FROM itr_data_tbl WHERE id = ?";
    $stmt = $DB_LINK_PDO->prepare($sql_query);
    $stmt->execute([$id]);
    $record = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($record) {
        // Paths for associated files
        $aadharPathFront = 'upload/aadhar/' . $record['user_aadhar_name'];
        $aadharPathBack = 'upload/aadhar/' . $record['user_aadhar_b_name'];
        $otherPathdoc = 'upload/other_doc/' . $record['user_other_doc_name'];
        $panPath = 'upload/pan/' . $record['user_pan_name'];
        $bankPath = 'upload/bank_statement/' . $record['user_bank_statement_name'];

        // Prepare the query for moving data to the trash table
        $trash_query = "INSERT INTO itr_trash_tbl (id, user_pan_number, user_name, user_phone, user_email, user_father_name, itr_fill_at_paac, ay_itr_fill, user_firm_name, user_firm_address, user_pan_url, user_aadhar_f_url, user_aadhar_b_url, user_bank_statement_url, user_other_doc_url)
                        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

        $stmt = $DB_LINK_PDO->prepare($trash_query);

        if ($stmt) {
            // Bind the parameters for the INSERT query
            $stmt->execute([
                $record['id'],
                $record['user_pan_number'],
                $record['user_name'],
                $record['user_phone'],
                $record['user_email'],
                $record['user_father_name'],
                $record['itr_fill_at_paac'],
                $record['ay_itr_fill'],
                $record['user_firm_name'],
                $record['user_firm_address'],
                $record['user_pan_url'],
                $record['user_aadhar_f_url'],
                $record['user_aadhar_b_url'],
                $record['user_bank_statement_url'],
                $record['user_other_doc_url']
            ]);

            // Delete record from itr_data_tbl
            $sql_delete = "DELETE FROM itr_data_tbl WHERE id = ?";
            $stmt_delete = $DB_LINK_PDO->prepare($sql_delete);
            if ($stmt_delete->execute([$id])) {
                $_SESSION['msg'] = "<center style='margin-top: 8px; color: green'><h5>Record deleted successfully.</h5></center>";
            } else {
                $_SESSION['msg'] = "<center style='margin-top: 8px; color: red'><h5>Error deleting record.</h5></center>";
            }
        } else {
            $_SESSION['msg'] = "<center style='margin-top: 8px; color: red'><h5>Failed to prepare trash insertion query.</h5></center>";
        }
    } else {
        $_SESSION['msg'] = "<center style='margin-top: 8px; color: red'><h5>Record not found.</h5></center>";
    }
}

header("location: listing.php");
exit;
