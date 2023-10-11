<?php

namespace WPPluginWithVueTailwind\Classes\Controllers;

use WP_Error;
use WP_REST_Request;
use WPPluginWithVueTailwind\Classes\CustomDatabaseConnection;

class QrCodeController
{

//    private $wpdb;
    private $database;

    public function __construct()
    {
        $this->database = new CustomDatabaseConnection();
    }

// ------------------------   Insert data ------------------

    public function insertData(WP_REST_Request $request)
    {
        $params = $request->get_params();

        $qrName = sanitize_text_field($params['qr_name']);
        $name = sanitize_text_field($params['name']);
        $surname = sanitize_text_field($params['surname']);
        $title = sanitize_text_field($params['title']);
        $email = sanitize_email($params['email']);
        $mobile = sanitize_text_field($params['mobile']);
        $address = sanitize_text_field($params['address']);
        $imageUrl = sanitize_text_field($params['imageUrl']);


        $required_fields = array('qr_name', 'name', 'surname', 'title', 'email', 'mobile', 'address');
        foreach ($required_fields as $field) {
            if (empty($params[$field])) {
                $validation_errors[] = 'Make sure to fill out the required field: ' . $field;
            }
        }

        if (!is_email($email)) {
            $validation_errors['email'] = 'Invalid Email Address';
        }

        if (!empty($validation_errors)) {
            $response = array(
                'status' => 'error',
                'message' => 'Validation failed',
                'errors' => $validation_errors,
            );
            wp_send_json($response);
        }


        $current_user = wp_get_current_user();
        $userId = $current_user->ID;

        $dataInsert = array(
            'user_id' => $userId,
            'qr_name' => $qrName,
            'name' => $name,
            'surname' => $surname,
            'title' => $title,
            'email' => $email,
            'mobile' => $mobile,
            'address' => $address,
            'image' => $imageUrl,
        );

        $result = $this->database->insert('userdataa', $dataInsert);

        if (!$result) {
            return rest_ensure_response(['database_error' => 'Error inserting data into the database.']);
        }

        $response = array(
            'status' => 'success',
            'message' => 'Data inserted successfully',
        );

        return rest_ensure_response($response);
    }


    //----------------------- display all data ------------------

    public function getAllData()
    {
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

        $results = $this->database->select('userdata', $columns);

        return $results;
    }


//    --------------------------- get data by id --------------------------

    public function getMyDataById($params)
    {
        $id = $params['id'];

        $columns = ['id', 'qr_name', 'name', 'surname', 'title', 'email', 'mobile', 'address', 'image'];

        $results = $this->database->selectById('userdata', $columns, $id);

        if (is_array($results) && count($results)) {
            wp_send_json_success([
                'data' => $results[0]
            ]);
        }

        if (!count($results) > 0) {
            return rest_ensure_response(['errors' => "This data is not available on our server."]);
        }
    }


//    -------------------------------- Update Data --------------------------------


    public function updateData($request)
    {
        $params = $request->get_params();
        $id = $params['id'];

        if (isset($params['imageUrl'])) {

            $updateData = array(
                'qr_name' => $params['qr_name'],
                'name' => $params['name'],
                'surname' => $params['surname'],
                'title' => $params['title'],
                'email' => $params['email'],
                'mobile' => $params['mobile'],
                'address' => $params['address'],
                'image' => $params['imageUrl'],
            );

            $where = array('id' => $id);

            $result = $this->database->update('userdata', $updateData, $where);


            if (!$result) {
                return rest_ensure_response(['errors' => 'Error updating data in the database.', array('status' => 500)]);
            }

            $response = array(
                'message' => 'Data Updated Successfully!',
            );

            return rest_ensure_response($response);

        } else {
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

            $result = $this->database->update('userdata', $updateData, $where);

            if (!$result) {
                return rest_ensure_response(['errors' => 'Error updating data in the database.', array('status' => 500)]);
            }

            $response = array(
                'message' => 'Data Updated Successfully!',
            );

            return rest_ensure_response($response);
        }
    }


//    ----------------------- Delete ----------------------


    public function deleteDataById($params)
    {
        $id = $params['id'];

        $where = array(
            'id' => $id,
        );

        $deleteRow = $this->database->delete('userdata', $where);

        if ($deleteRow) {
            return rest_ensure_response(array('message' => 'Data deleted successfully'), 200);
        } else {
            return new WP_Error('delete_failed', 'Failed to delete data', array('status' => 500));
        }
    }
}