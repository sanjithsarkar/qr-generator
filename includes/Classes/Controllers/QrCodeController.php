<?php

namespace WPPluginWithVueTailwind\Classes\Controllers;

use WP_Error;
use WP_REST_Request;

class QrCodeController
{

    private $wpdb;

    public function __construct()
    {
        global $wpdb;
        $this->wpdb = $wpdb;
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

        if(!is_email($email)){
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


        // Insert data into the database table
        $table_name = $this->wpdb->prefix . QR_GENERATOR_SLUG . '_' . 'userdata';

        $this->wpdb->insert(
            $table_name,
            $dataInsert
        );

        if ($this->wpdb->last_error) {
            return new WP_Error('database_error', 'Error inserting data into the database.', array('status' => 500));
        }

        $response = array(
            'status' => 'success',
            'message' => 'Data inserted successfully',
        );

        return rest_ensure_response($response);
    }


    //----------------------- display all data ------------------

    public function getMyData()
    {
        $table_name = $this->wpdb->prefix . QR_GENERATOR_SLUG . '_' . 'userdata';

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

        $results = $this->wpdb->get_results($sql);

        return $results;
    }



//    --------------------------- get data by id --------------------------

    public function getMyDataById($params)
    {
        $id = $params['id'];

        $table_name = $this->wpdb->prefix . QR_GENERATOR_SLUG . '_' . 'userdata';

        $columns = ['id', 'qr_name', 'name', 'surname', 'title', 'email', 'mobile', 'address', 'image'];

        $sql = $this->wpdb->prepare(
            "SELECT " . implode(', ', $columns) . " FROM $table_name WHERE id = %d",
            $id);

        $results = $this->wpdb->get_results($sql);

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

        //table name
        $table_name = $this->wpdb->prefix . QR_GENERATOR_SLUG . '_' . 'userdata';

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

            $this->wpdb->update($table_name, $updateData, $where);

            if ($this->wpdb->last_error) {
                return new WP_Error('database_error', 'Error updating data in the database.', array('status' => 500));
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

            $this->wpdb->update($table_name, $updateData, $where);

            if ($this->wpdb->last_error) {
                return new WP_Error('database_error', 'Error updating data in the database.', array('status' => 500));
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
        $table_name = $this->wpdb->prefix . QR_GENERATOR_SLUG . '_' . 'userdata';

        $where = array(
            'id' => $id,
        );

        $deleteRow = $this->wpdb->delete($table_name, $where);

        if ($deleteRow) {
            return rest_ensure_response(array('message' => 'Data deleted successfully'), 200);
        } else {
            return new WP_Error('delete_failed', 'Failed to delete data', array('status' => 500));
        }
    }
}