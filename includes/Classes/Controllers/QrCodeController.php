<?php

namespace WPPluginWithVueTailwind\Classes\Controllers;

use WP_Error;
use WP_REST_Request;
use WPPluginWithVueTailwind\Classes\Activator;

class QrCodeController
{


// ------------------------   Insert data ------------------

    public function insertData(WP_REST_Request $request)
    {
        global $wpdb, $current_user;

        $params = $request->get_params();

        $qrName = isset($params['qr_name']) ? sanitize_text_field($params['qr_name']) : '';
        $name = isset($params['name']) ? sanitize_text_field($params['name']) : '';
        $surname = isset($params['surname']) ? sanitize_text_field($params['surname']) : '';
        $title = isset($params['title']) ? sanitize_text_field($params['title']) : '';
        $email = isset($params['email']) ? sanitize_text_field($params['email']) : '';
        $mobile = isset($params['mobile']) ? sanitize_text_field($params['mobile']) : '';
        $address = isset($params['address']) ? sanitize_text_field($params['address']) : '';


        $required_fields = array('qr_name', 'name', 'surname', 'title', 'email', 'mobile', 'address');
        foreach ($required_fields as $field) {
            if (empty($params[$field])) {
                $validation_errors[] = 'Make sure to fill out the required field: ' . $field;
            }
        }

        if (!is_email($email)) {
            $validation_errors[] = 'Invalid email address.';
        }

        if (!empty($validation_errors)) {
            $response = array(
                'status' => 'error',
                'message' => 'Validation failed',
                'errors' => $validation_errors,
            );
            wp_send_json($response);
        }


        // Image folder path
        $FolderUrl = QR_GENERATOR_DIR . '/assets/images/';

        if (!file_exists($FolderUrl)) {
            mkdir($FolderUrl, 0777, true);
        }

        define('UPLOADS_THEME_PATH', $FolderUrl);

        $file_name = null;

        if (isset($_FILES['image'])) {
            $tmp_name = $_FILES['image']['tmp_name'];

            $file_extension = pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION);
            $file_name = time() . "." . $file_extension;
            $targetpath = UPLOADS_THEME_PATH . $file_name;

            move_uploaded_file($tmp_name, $targetpath);
        }

        $userId = get_current_user_id();
        $current_user_email = $current_user->email;

        $dataInsert = array(
            'user_id' => $userId,
            'qr_name' => $qrName,
            'name' => $name,
            'surname' => $surname,
            'title' => $title,
            'email' => $email,
            'mobile' => $mobile,
            'address' => $address,
            'image' => $file_name,
        );


        // Insert data into the database table
        $table_name = $wpdb->prefix . 'userdata';

        $wpdb->insert(
            $table_name,
            $dataInsert
        );

        if ($wpdb->last_error) {
            return new WP_Error('database_error', 'Error inserting data into the database.', array('status' => 500));
        }

        $response = array(
            'status' => 'success',
            'message' => 'Data inserted successfully',
        );
//        wp_send_json($response);

        return rest_ensure_response($response);
    }




    //----------------------- display all data ------------------

    public function getMyData()
    {
        global $wpdb;

        $table_name = $wpdb->prefix . 'userdata';

        $columns = array(
            'id',
            'qr_name',
            'name',
            'surname',
            'title',
            'email',
            'mobile',
            'address',
        );

        $sql = "SELECT " . implode(', ', $columns) . " FROM $table_name";

        $results = $wpdb->get_results($sql);

        return $results;
    }


//    --------------------------- get data by id --------------------------
    public function getMyDataById($params)
    {
        global $wpdb;

        $id = $params['id'];

        $table_name = $wpdb->prefix . 'userdata';

        $columns = ['id', 'qr_name', 'name', 'surname', 'title', 'email', 'mobile', 'address', 'image'];

        $sql = $wpdb->prepare(
            "SELECT " . implode(', ', $columns) . " FROM $table_name WHERE id = %d",
            $id);

        $results = $wpdb->get_results($sql);

        foreach ($results as $result) {
            $result->image_url = WPM_URL . 'assets/images/' . $result->image;
        }


        if (is_array($results) && count($results)) {
            wp_send_json_success([
                'data' => $results[0]
            ]);
        }

        wp_send_json_error([
            'data' => "Data Not Found"
        ]);
    }


//    -------------------------------- Update Data --------------------------------


    public function updateData($request)
    {
        $params = $request->get_params();
        $id = $params['id'];

        global $wpdb;

        //table name
        $table_name = $wpdb->prefix . 'userdata';

        // Image folder path
        $FolderUrl = QR_GENERATOR_DIR . '/assets/images/';
        define('UPLOADS_THEME_PATH', $FolderUrl);

        $file_name = null;

        if (isset($_FILES['image'])) {
            $tmp_name = $_FILES['image']['tmp_name'];

            $file_extension = pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION);
            $file_name = time() . "." . $file_extension;
            $targetpath = UPLOADS_THEME_PATH . $file_name;

            move_uploaded_file($tmp_name, $targetpath);

            $getDataById = $wpdb->prepare("SELECT image FROM $table_name WHERE id = %d", $id);
            $getOnlyImage = $wpdb->get_var($getDataById);

            // Delete the previous image if it exists
            $previous_image_path = UPLOADS_THEME_PATH . $getOnlyImage;

            if (file_exists($previous_image_path)) {
                unlink($previous_image_path);
            }

            $updateData = array(
                'qr_name' => $params['qr_name'],
                'name' => $params['name'],
                'surname' => $params['surname'],
                'title' => $params['title'],
                'email' => $params['email'],
                'mobile' => $params['mobile'],
                'address' => $params['address'],
                'image' => $file_name,
            );

            $where = array('id' => $id);

            $wpdb->update($table_name, $updateData, $where);

            if ($wpdb->last_error) {
                return new WP_Error('database_error', 'Error updating data in the database.', array('status' => 500));
            }

            $response = array(
                'message' => 'Data Updated Successfully!',
            );

            return rest_ensure_response($response);

        }

        // update data with no image
        $updateData = array(
            'qr_name' => $params['qr_name'],
            'name' => $params['name'],
            'surname' => $params['surname'],
            'title' => $params['title'],
            'email' => $params['email'],
            'mobile' => $params['mobile'],
            'address' => $params['address'],
        );

        $where = array('id' => $id);

        $wpdb->update($table_name, $updateData, $where);

        if ($wpdb->last_error) {
            return new WP_Error('database_error', 'Error updating data in the database.', array('status' => 500));
        }

        $response = array(
            'message' => 'Data Updated Successfully!',
        );

        return rest_ensure_response($response);
    }


//    ----------------------- Delete ----------------------


    public function deleteDataById($params)
    {

        global $wpdb;

        $id = $params['id'];

        $table_name = $wpdb->prefix . 'userdata';

        // Image folder path
        $FolderUrl = QR_GENERATOR_DIR . '/assets/images/';
        define('UPLOADS_THEME_PATH', $FolderUrl);

        $getDataById = $wpdb->prepare("SELECT image FROM $table_name WHERE id = %d", $id);
        $getOnlyImage = $wpdb->get_var($getDataById);

        // Delete the previous image if it exists
        $previous_image_path = UPLOADS_THEME_PATH . $getOnlyImage;

        if (file_exists($previous_image_path)) {
            unlink($previous_image_path);
        }

        $query = $wpdb->delete($table_name, array('id' => $id));

        if ($query) {
            return rest_ensure_response(array('message' => 'Data deleted successfully'), 200);
        } else {
            return new WP_Error('delete_failed', 'Failed to delete data', array('status' => 500));
        }
    }


}