<?php
/**
 * Use this to set the config of stuff and do stuff.
 *
 * Blah blah blah blah.
 *
 * @copyright  FulpTube
 * @since      Class available since FulpTube backend rewrite 5/9/21
 */ 
class config_setup {
    public $conn;
    public $server;
    public $current_page;

    function initialize_db_var($conn) {
        $this->conn = $conn;    
    }

    function initialize_page_compass($current_page) {
        $this->current_page = $current_page;    
    }

    function initialize_server_vars($server) {
        $this->server = $server;    
    }

    function return_conn() {
        return $this->conn;
    }

    function return_current_page() {
        return $this->current_page;
    }

    function return_server_vars() {
        return $this->server;
    }

    function update_login_time($username) {
        $stmt = $this->conn->prepare("UPDATE users SET lastlogin = now() WHERE username = ?");
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $stmt->close();
    }
}
?>