<?php

namespace WPPluginWithVueTailwind\Classes;

class CustomDatabaseConnection
{
    private $db;
    private $dbPrefix;

    public function __construct() {
        global $wpdb;
        $this->db = $wpdb;
        $this->dbPrefix = $this->db->prefix . QR_GENERATOR_SLUG . '_';
    }

    public function getDbPrefix(){
        return $this->dbPrefix;
    }

    public function select($table,$columns){
        $table_name = $this->getDbPrefix() . $table;
        $sql = "SELECT " . implode(', ', $columns) . " FROM $table_name";
        return $this->query($sql);
    }

    public function selectById($table,$columns, $id){
        $table_name = $this->getDbPrefix() . $table;
        $sql = "SELECT " . implode(', ', $columns) . " FROM $table_name WHERE id = $id";
        return $this->query($sql);
    }

    public function query($sql) {
        return $this->db->get_results($sql);
    }

    public function insert($table, $columns) {
        $table_name = $this->getDbPrefix() . $table;
        return $this->db->insert($table_name, $columns);
    }

    public function update($table, $data, $where) {
        $table_name = $this->getDbPrefix() . $table;
        return $this->db->update($table_name, $data, $where);
    }

    public function delete($table, $where) {
        $table_name = $this->getDbPrefix() . $table;
        return $this->db->delete($table_name, $where);
    }
}