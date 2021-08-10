<?php
/**
 * Use this to insert data for users.
 *
 * Blah blah blah blah.
 *
 * @copyright  FulpTube
 * @since      Class available since FulpTube backend rewrite 5/9/21
 */ 
class user_insert_utils {
    public $conn;
    function initialize_db_var($conn) {
        $this->conn = $conn;    
    }

    function initialize_server_vars($server) {
        $this->server = $server;    
    }

    function register($username, $email, $hashedpassword) {
        $stmt = $this->conn->prepare("INSERT INTO users (username, email, password) VALUES (?, ?, ?)");
        $stmt->bind_param("sss", $username, $email, $hashedpassword);
        $stmt->execute();
        $stmt->close();
        return true;
    }
    
    function validateCaptcha($privatekey, $response) {
        $responseData = json_decode(file_get_contents('https://www.google.com/recaptcha/api/siteverify?secret='.$privatekey.'&response='.$response));
        return $responseData->success;
    }
    
    function send_message($to, $subject, $message, $owner) {
        $stmt = $this->conn->prepare("INSERT INTO pms (touser, subject, message, owner) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("ssss", $to, $subject, $message, $owner);
        $stmt->execute();
        $stmt->close();
        return true;
    }

    function send_logs($message) {
        $stmt = $this->conn->prepare("INSERT INTO admin_logs (log) VALUES (?)");
        $stmt->bind_param("s", $message);
        $stmt->execute();
        $stmt->close();
        return true;
    }


    function remove_like_video($sender, $reciever) {
        $stmt = $this->conn->prepare("DELETE FROM likes WHERE sender = ? AND reciever = ?");
        $stmt->bind_param("ss", $sender, $reciever);
        $stmt->execute();
        $stmt->close();
    }    

    function add_dislike_video($sender, $reciever) {
        $stmt = $this->conn->prepare("INSERT INTO likes (sender, reciever, type) VALUES (?, ?, 'd')");
        $stmt->bind_param("ss", $sender, $reciever);
        $stmt->execute();
        $stmt->close();
    }    

    function remove_star_video($sender, $reciever) {
        $stmt = $this->conn->prepare("DELETE FROM stars WHERE sender = ? AND reciever = ?");
        $stmt->bind_param("ss", $sender, $reciever);
        $stmt->execute();
        $stmt->close();
    }    

    function add_comment_dislike($sender, $reciever) {
        $stmt = $this->conn->prepare("INSERT INTO comment_likes (sender, reciever, type) VALUES (?, ?, 'd')");
        $stmt->bind_param("ss", $sender, $reciever);
        $stmt->execute();
        $stmt->close();
    }
}

/**
 * Use this to insert data for videos.
 *
 * Blah blah blah blah.
 *
 * @copyright  FulpTube
 * @since      Class available since FulpTube backend rewrite 5/9/21
 */ 
class video_insert_utils {
    public $conn;
    function initialize_db_var($conn) {
        $this->conn = $conn;    
    }

    function initialize_server_vars($server) {
        $this->server = $server;    
    }

    function check_view($vidid, $user) {
        $stmt = $this->conn->prepare("SELECT * FROM views WHERE viewer = ? AND videoid = ?");
        $stmt->bind_param("ss", $user, $vidid);
        $stmt->execute();
        $result = $stmt->get_result();
        if($result->num_rows === 0) {
            $this->add_view($vidid, $user);
        }
        $stmt->close();
    }
    
    function add_view($vidid, $user) {
        $stmt = $this->conn->prepare("INSERT INTO views (viewer, videoid) VALUES (?, ?)");
        $stmt->bind_param("ss", $user, $vidid);
        $stmt->execute();
        $stmt->close();
    }    

    function check_view_channel($vidid, $user) {
        $stmt = $this->conn->prepare("SELECT * FROM channel_views WHERE viewer = ? AND channel = ?");
        $stmt->bind_param("ss", $user, $vidid);
        $stmt->execute();
        $result = $stmt->get_result();
        if($result->num_rows === 0) {
            $this->add_view_channel($vidid, $user);
        }
        $stmt->close();
    }
    
    function add_view_channel($vidid, $user) {
        $stmt = $this->conn->prepare("INSERT INTO channel_views (viewer, channel) VALUES (?, ?)");
        $stmt->bind_param("ss", $user, $vidid);
        $stmt->execute();
        $stmt->close();
    }    

    function add_to_history($vidid, $user) {
        $stmt = $this->conn->prepare("INSERT INTO history (video, author) VALUES (?, ?)");
        $stmt->bind_param("ss", $vidid, $user);
        $stmt->execute();
        $stmt->close();
    }
}
?>