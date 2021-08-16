<?php
/**
 * Use this to delete data for users.
 *
 * Blah blah blah blah.
 *
 * @copyright  FulpTube
 * @since      Class available since FulpTube backend rewrite 5/9/21
 */ 
class user_delete_utils {
    public $conn;
    function initialize_db_var($conn) {
        $this->conn = $conn;    
    }

    function initialize_server_vars($server) {
        $this->server = $server;    
    }

    function remove_comment_like($sender, $reciever) {
        $stmt = $this->conn->prepare("DELETE FROM comment_likes WHERE sender = ? AND reciever = ?");
        $stmt->bind_param("ss", $sender, $reciever);
        $stmt->execute();
        $stmt->close();
    }
}

/**
 * Use this to delete data for videos.
 *
 * Blah blah blah blah.
 *
 * @copyright  FulpTube
 * @since      Class available since FulpTube backend rewrite 5/9/21
 */ 
class video_delete_utils {
    public $conn;
    function initialize_db_var($conn) {
        $this->conn = $conn;    
    }

    function initialize_server_vars($server) {
        $this->server = $server;    
    }

    function remove_video($rid) {
        $stmt = $this->conn->prepare("DELETE FROM videos WHERE rid = ?");
        $stmt->bind_param("s", $rid);
        $stmt->execute();
        $stmt->close();
    }  
    
    function unjoin_group($id) {
        $stmt = $this->conn->prepare("DELETE FROM group_members WHERE rid = ?");
        $stmt->bind_param("s", $id);
        $stmt->execute();
        $stmt->close();
    }  
}
?>