<?php
/**
 * Use this to update data for users.
 *
 * Blah blah blah blah.
 *
 * @copyright  FulpTube
 * @since      Class available since FulpTube backend rewrite 5/9/21
 */ 
class user_update_utils {
    public $conn;
    function initialize_db_var($conn) {
        $this->conn = $conn;    
    }

    function initialize_server_vars($server) {
        $this->server = $server;    
    } 

    function update_comment_cooldown_time($username) {
        $stmt = $this->conn->prepare("UPDATE users SET cooldown_comment = now() WHERE username = ?");
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $stmt->close();
    }

    function update_upload_cooldown_time($username) {
        $stmt = $this->conn->prepare("UPDATE users SET upload_cooldown = now() WHERE username = ?");
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $stmt->close();
    }

    function update_user_bio($username, $bio) {
        $stmt = $this->conn->prepare("UPDATE users SET bio = ? WHERE username = ?");
        $stmt->bind_param("ss", $bio, $username);
        $stmt->execute();
        $stmt->close();
        
        return true;
    }

    function update_user_country($username, $country) {
        $stmt = $this->conn->prepare("UPDATE users SET country = ? WHERE username = ?");
        $stmt->bind_param("ss", $country, $username);
        $stmt->execute();
        $stmt->close();
        
        return true;
    }

    function update_user_website($username, $website) {
        $stmt = $this->conn->prepare("UPDATE users SET website = ? WHERE username = ?");
        $stmt->bind_param("ss", $website, $username);
        $stmt->execute();
        $stmt->close();
        
        return true;
    }

    function update_user_header($username, $header) {
        $stmt = $this->conn->prepare("UPDATE users SET custom_header = ? WHERE username = ?");
        $stmt->bind_param("ss", $header, $username);
        $stmt->execute();
        $stmt->close();
        
        return true;
    }

    function update_user_text($username, $text) {
        $stmt = $this->conn->prepare("UPDATE users SET custom_text = ? WHERE username = ?");
        $stmt->bind_param("ss", $text, $username);
        $stmt->execute();
        $stmt->close();
        
        return true;
    }

    function update_user_genre($username, $genre) {
        $stmt = $this->conn->prepare("UPDATE users SET genre = ? WHERE username = ?");
        $stmt->bind_param("ss", $genre, $username);
        $stmt->execute();
        $stmt->close();
        
        return true;
    }

    function update_user_css($username, $css) {
        $stmt = $this->conn->prepare("UPDATE users SET css = ? WHERE username = ?");
        $stmt->bind_param("ss", $css, $username);
        $stmt->execute();
        $stmt->close();
        
        return true;
    }

    function update_user_channels($username, $bio) {
        $stmt = $this->conn->prepare("UPDATE users SET featured_channels = ? WHERE username = ?");
        $stmt->bind_param("ss", $bio, $username);
        $stmt->execute();
        $stmt->close();
        
        return true;
    }

    function update_user_transparency($username, $percentage) {
        $stmt = $this->conn->prepare("UPDATE users SET transparency = ? WHERE username = ?");
        $stmt->bind_param("ss", $percentage, $username);
        $stmt->execute();
        $stmt->close();
        
        return true;
    }

    function update_user_margin_top($username, $type) {
        $stmt = $this->conn->prepare("UPDATE users SET bannerdp = ? WHERE username = ?");
        $stmt->bind_param("ss", $type, $username);
        $stmt->execute();
    $stmt->close();
    
    return true;
    }    

    function update_user_featured_video($username, $bio) {
        $stmt = $this->conn->prepare("UPDATE users SET featured = ? WHERE username = ?");
        $stmt->bind_param("ss", $bio, $username);
        $stmt->execute();
        $stmt->close();

        return true;
    }

    function update_user_featured_title($username, $bio) {
        $stmt = $this->conn->prepare("UPDATE users SET customchannelfeatured = ? WHERE username = ?");
        $stmt->bind_param("ss", $bio, $username);
        $stmt->execute();
    $stmt->close();
    
    return true;
    }

    function update_user_banner_display($username, $type) {
        $stmt = $this->conn->prepare("UPDATE users SET bannerdp = ? WHERE username = ?");
        $stmt->bind_param("ss", $type, $username);
        $stmt->execute();
    $stmt->close();
    
    return true;
    }

    function update_user_primary_color($username, $type) {
        $stmt = $this->conn->prepare("UPDATE users SET primary_color = ? WHERE username = ?");
        $stmt->bind_param("ss", $type, $username);
        $stmt->execute();
    $stmt->close();
    
    return true;
    }

    function update_user_border_color($username, $color) {
        $stmt = $this->conn->prepare("UPDATE users SET border_color = ? WHERE username = ?");
        $stmt->bind_param("ss", $color, $username);
        $stmt->execute();
    $stmt->close();
    
    return true; 
    }

    function update_user_secondary_color($username, $type) {
        $stmt = $this->conn->prepare("UPDATE users SET secondary_color = ? WHERE username = ?");
        $stmt->bind_param("ss", $type, $username);
        $stmt->execute();
    $stmt->close();
    
    return true;
    }

    function update_user_third_color($type, $username) {
        $stmt = $this->conn->prepare("UPDATE users SET third_color = ? WHERE username = ?");
        $stmt->bind_param("ss", $type, $username);
        $stmt->execute();
    $stmt->close();
    
    return true;
    }

    function update_user_bg_option($type, $username) {
        $stmt = $this->conn->prepare("UPDATE users SET 2012_bgoption = ? WHERE username = ?");
        $stmt->bind_param("ss", $type, $username);
        $stmt->execute();
    $stmt->close();
    
    return true;
    }

    function update_user_bg_color($color, $username) {
        $stmt = $this->conn->prepare("UPDATE users SET 2012_bgcolor = ? WHERE username = ?");
        $stmt->bind_param("ss", $color, $username);
        $stmt->execute();
    $stmt->close();
    
    return true;
    }

    function update_user_bg($color, $username) {
        $stmt = $this->conn->prepare("UPDATE users SET 2012_bg = ? WHERE username = ?");
        $stmt->bind_param("ss", $color, $username);
        $stmt->execute();
    $stmt->close();
    
    return true;
    }

    function update_user_bg_option_09($type, $username) {
        $stmt = $this->conn->prepare("UPDATE users SET 2009_bgoption = ? WHERE username = ?");
        $stmt->bind_param("ss", $type, $username);
        $stmt->execute();
    $stmt->close();
    
    return true;
    }

    function update_user_bg_color_09($color, $username) {
        $stmt = $this->conn->prepare("UPDATE users SET 2009_bgcolor = ? WHERE username = ?");
        $stmt->bind_param("ss", $color, $username);
        $stmt->execute();
    $stmt->close();
    
    return true;
    }

    function update_user_bg_09($color, $username) {
        $stmt = $this->conn->prepare("UPDATE users SET 2009_bg = ? WHERE username = ?");
        $stmt->bind_param("ss", $color, $username);
        $stmt->execute();
    $stmt->close();
    
    return true;
    }

    function update_user_banner($color, $username) {
        $stmt = $this->conn->prepare("UPDATE users SET banner = ? WHERE username = ?");
        $stmt->bind_param("ss", $color, $username);
        $stmt->execute();
    $stmt->close();
    
    return true;
    }

    function update_user_video_banner($color, $username) {
        $stmt = $this->conn->prepare("UPDATE users SET subbutton = ? WHERE username = ?");
        $stmt->bind_param("ss", $color, $username);
        $stmt->execute();
    $stmt->close();
    
    return true;
    }



    function update_user_text_color($username, $type) {
        $stmt = $this->conn->prepare("UPDATE users SET text_color = ? WHERE username = ?");
        $stmt->bind_param("ss", $type, $username);
        $stmt->execute();
    $stmt->close();
    
    return true;
    }

    function update_user_primary_text_color($username, $type) {
        $stmt = $this->conn->prepare("UPDATE users SET primary_color_text = ? WHERE username = ?");
        $stmt->bind_param("ss", $type, $username);
        $stmt->execute();
    $stmt->close();
    
    return true;
    }
}

/**
 * Use this to update data for videos.
 *
 * Blah blah blah blah.
 *
 * @copyright  FulpTube
 * @since      Class available since FulpTube backend rewrite 5/9/21
 */ 
class video_update_utils {
    public $conn;
    function initialize_db_var($conn) {
        $this->conn = $conn;    
    }

    function initialize_server_vars($server) {
        $this->server = $server;    
    }

    function update_video_commenting($id, $type) {
        $stmt = $this->conn->prepare("UPDATE videos SET commenting = ? WHERE rid = ?");
        $stmt->bind_param("ss", $type, $id);
        $stmt->execute();
        $stmt->close();
    }
}
?>