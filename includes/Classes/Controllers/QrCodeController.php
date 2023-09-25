<?php

namespace WPPluginWithVueTailwind\Classes\Controllers;

use WP_Error;
use WP_REST_Request;
use WPPluginWithVueTailwind\Classes\Activator;

class QrCodeController
{
    public function test($params)
    {
        $posts = get_posts(array(
            'author' => $params['id'],
        ));

        if (empty($posts)) {
            return 'post not found';
        }

        return $posts[0];
    }


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
//        $image = isset($params['image']) ? sanitize_text_field($params['image']) : '';


        if (empty($name)) {
            return new WP_Error('empty_name', 'Name field is empty.', array('status' => 400));
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
            'address' => $address
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
            'message' => 'Data inserted successfully!',
        );

        return rest_ensure_response($userId);
    }


    //    display data form databse

    public function getMyData(){
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


    public function getMyDataById($params){

//        if (isset($params['id'])) {
//            return "not found";
//        }


        $id = $params['id'];
        global $wpdb;

        $table_name = $wpdb->prefix . 'userdata';


        $columns = ['id', 'qr_name', 'name', 'surname', 'title', 'email', 'mobile', 'address'];

        $sql = $wpdb->prepare(
            "SELECT " . implode(', ', $columns) . " FROM $table_name WHERE id = %d",
            $id);

        $results = $wpdb->get_results($sql);

        if (is_array($results) && count($results)){
            wp_send_json_success([
                'data' => $results[0]
            ]);
        }

        wp_send_json_error([
            'data' => "Data Not Found"
        ]);
    }


    public function updateData($request){
        $params = $request->get_params();
        $id = $params['id'];

        global $wpdb;
        $table_name = $wpdb->prefix . 'userdata';

        $updateData = array(
            'id' => $id,
            'qr_name'  => $params['qr_name'],
            'name'     => $params['name'],
            'surname'  => $params['surname'],
            'title'    => $params['title'],
            'email'    => $params['email'],
            'mobile'   => $params['mobile'],
            'address'  => $params['address'],
        );

        $where = array('id' => $updateData['id']);

        $wpdb->update($table_name, $updateData, $where);

        if ($wpdb->last_error) {
            return new WP_Error('database_error', 'Error inserting data into the database.', array('status' => 500));
        }

        $response = array(
            'message' => 'Data Updated Successfully!',
        );

        return rest_ensure_response($response);
    }


    public function deleteData($params) {

        global $wpdb;

        $id = $params['id'];

        $table_name = $wpdb->prefix . 'userdata';

        $query = $wpdb->delete( $table_name, array( 'id' => $id ) );

        if ($query) {
            return rest_ensure_response(array('message' => 'Data deleted successfully'), 200);
        } else {
            return new WP_Error('delete_failed', 'Failed to delete data', array('status' => 500));
        }
    }


}