<?php

##########################################################
# File Name: database.php
# Author: Denis Pikusov
# Date Created: 29/01/2005
# Last modification: 09/02/2005
# Description: Module for working with database
##########################################################

if (!function_exists('mysql_connect'))
{
    require_once('mysql_connect/MySQL_Definitions.php');
    require_once('mysql_connect/MySQL.php');
    require_once('mysql_connect/MySQL_Functions.php');
}

class Database {

    var $db_name;
    var $host;
    var $user;
    var $pass;
    var $link;
    var $res_id;
    var $error_msg;
    var $file_log;
    var $query_log;
    var $total_time;

    # Constructor

    function __construct($database_name, $host_name = "localhost", $user_name = "", $password = "") {
        $this->db_name = $database_name;
        $this->host = $host_name;
        $this->user = $user_name;
        $this->pass = $password;
        $this->link = 0;
        $this->res_id = 0;
        $this->error_msg = "";
        $this->file_log = dirname(__FILE__) . "/cache/db_" . $_SERVER['REMOTE_ADDR'];
        $this->query_log = false;
        //$this->query_log = true;
        $this->total_time = 0;
    }

    # Connecting to the database

    function connect() {
        if ($this->query_log) {
            $file = fopen($this->file_log, "w");
            fclose($file);
            $this->total_time = microtime(true);
        }
        if (!$this->link = @mysql_connect($this->host, $this->user, $this->pass, true)) {
            $this->error_msg = "Could not connect to the database on $this->host";
            echo $this->error_msg;
            exit();
            return 0;
        } else {
            if ($this->query_log) {
                $file = fopen($this->file_log, "w");
                fclose($file);
                $this->total_time = microtime(true);
            }
        }

        if (!@mysql_select_db($this->db_name, $this->link)) {
            $this->error_msg = "Could not select the $this->db_name database";
            echo $this->error_msg;
            exit();
            return 0;
        }
        return $this->link;
    }

    # Close the database connection

    function disconnect() {
        if (!mysql_close($this->link)) {
            $this->error_msg = "Could not close the $this->db_name database";
            return 0;
        }
        if ($this->query_log) {
            $this->total_time = microtime(true) - $this->total_time;
            $file = fopen($this->file_log, "a");
            fwrite($file, date("H:i:s") . "_Totaltime__" . $this->total_time . "\n");
            fclose($file);
        }
        return 1;
    }

    # Execute the query or queries array

    function query($q) {
        if ($this->link) {
            if ($this->query_log)
                $start = microtime(true);
            $this->res_id = mysql_query($q, $this->link);
            if ($this->query_log) {
                if (is_file($this->file_log)) {
                    $time = microtime(true) - $start;
                    $file = fopen($this->file_log, "a");
                    fwrite($file, date("H:i:s") . "_\"" . preg_replace('/[\r\n\s]+/', ' ', $q) . "\"_" . $time . "\n");
                    fclose($file);
                }
            }
        } else {
            $this->error_msg = "Could not execute query to $this->db_name database, wrong database link";
            return 0;
        }
        if (!$this->res_id) {
            $this->error_msg = "Could not execute query to $this->db_name database, wrong result id";
            if ($this->query_log) {
                if (is_file($this->file_log)) {
                    $file = fopen($this->file_log, "a");
                    fwrite($file, "ERROR\n");
                    fclose($file);
                }
            }
            return 0;
        }
        return $this->res_id;
    }

    # Returns results array of the query in array of objects

    function results() {
        $result = array();
        if (!$this->res_id) {
            $this->error_msg = "Could not execute query to $this->db_name database, wrong result id";
            return 0;
        }
        while ($row = mysql_fetch_object($this->res_id)) {
            array_push($result, $row);
        }
        return $result;
    }

    # Returns result of the query in array of objects

    function result() {
        $result = array();
        if (!$this->res_id) {
            $this->error_msg = "Could not execute query to $this->db_name database, wrong result id";
            return 0;
        }
        $row = mysql_fetch_object($this->res_id);
        return $row;
    }

    # Returns last inserted id

    function insert_id() {
        return mysql_insert_id($this->link);
    }

    # Returns last inserted id

    function num_rows() {
        return mysql_num_rows($this->res_id);
    }

    # Returns last inserted id

    function affected_rows() {
        return mysql_affected_rows($this->link);
    }

}

?>