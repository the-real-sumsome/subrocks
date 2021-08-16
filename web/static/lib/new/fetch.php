<?php
/**
 * Use this to fetch data for users.
 *
 * Blah blah blah blah.
 *
 * @copyright  FulpTube
 * @since      Class available since FulpTube backend rewrite 5/9/21
 */ 
class user_fetch_utils {
    public $conn;
    function initialize_db_var($conn) {
        $this->conn = $conn;    
    }

    function initialize_server_vars($server) {
        $this->server = $server;    
    }

    function fetch_user_username($username) {
            $stmt = $this->conn->prepare("SELECT * FROM users WHERE username = ?");
            $stmt->bind_param("s", $username);
            $stmt->execute();
        $result = $stmt->get_result();
        $user = $result->fetch_assoc();

        if($result->num_rows === 0) 
            return 0;
        else
            return $user;

        $stmt->close();
    }

    function fetch_friends_accepted($username) {
        $stmt = $this->conn->prepare("SELECT * FROM friends WHERE reciever = ? AND status = 'a'");
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $result = $stmt->get_result();
        $rows = mysqli_num_rows($result); 
        $stmt->close();
    
        $friends = $rows;

        $stmt = $this->conn->prepare("SELECT * FROM friends WHERE sender = ? AND status = 'a'");
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $result = $stmt->get_result();
        $rows = mysqli_num_rows($result); 
        $stmt->close();
    
        return $friends + $rows;
    }

    function fetch_friend_id($id) {
            $stmt = $this->conn->prepare("SELECT * FROM friends WHERE id = ?");
            $stmt->bind_param("s", $id);
            $stmt->execute();
        $result = $stmt->get_result();
        $friend = $result->fetch_assoc();

        if($result->num_rows === 0) 
            return 0;
        else
            return $friend;

        $stmt->close();
    }

    function fetch_profile_comment_id($id) {
            $stmt = $this->conn->prepare("SELECT * FROM profile_comments WHERE id = ?");
            $stmt->bind_param("s", $id);
            $stmt->execute();
        $result = $stmt->get_result();
        $comment = $result->fetch_assoc();

        if($result->num_rows === 0) 
            return 0;
        else
            return $comment;

        $stmt->close();
    }

    function fetch_comment_id($id) {
            $stmt = $this->conn->prepare("SELECT * FROM comments WHERE id = ?");
            $stmt->bind_param("s", $id);
            $stmt->execute();
        $result = $stmt->get_result();
        $comment = $result->fetch_assoc();

        if($result->num_rows === 0) 
            return 0;
        else
            return $comment;

        $stmt->close();
    }

    function fetch_reply_id($id) {
            $stmt = $this->conn->prepare("SELECT * FROM comment_reply WHERE id = ?");
            $stmt->bind_param("s", $id);
            $stmt->execute();
        $result = $stmt->get_result();
        $comment = $result->fetch_assoc();

        if($result->num_rows === 0) 
            return 0;
        else
            return $comment;

        $stmt->close();
    }

    function fetch_pm_id($id) {
            $stmt = $this->conn->prepare("SELECT * FROM pm WHERE id = ?");
            $stmt->bind_param("s", $id);
            $stmt->execute();
        $result = $stmt->get_result();
        $pm = $result->fetch_assoc();

        if($result->num_rows === 0) 
            return 0;
        else
            return $pm;

        $stmt->close();
    }

    function if_blocked($user, $reciever) {
            $stmt = $this->conn->prepare("SELECT `reciever` FROM block WHERE sender = ? AND reciever = ?");
            $stmt->bind_param("ss", $user, $reciever);
            $stmt->execute();
        $result = $stmt->get_result();
        $user = $result->fetch_assoc();
        if($result->num_rows === 1) { return true; } else { return false; }
        $stmt->close();
        
        return $user;
    }    

    function fetch_unread_pms($id) {
        $stmt = $this->conn->prepare("SELECT * FROM pms WHERE touser = ? AND readed = 'n'");
        $stmt->bind_param("s", $id);
        $stmt->execute();
        $result = $stmt->get_result();
        $rows = mysqli_num_rows($result); 
        $stmt->close();
    
        return $rows;
    }

    function fetch_quicklist_videos($id) {
        $stmt = $this->conn->prepare("SELECT video FROM quicklist_videos WHERE author = ?");
        $stmt->bind_param("s", $id);
        $stmt->execute();
        $result = $stmt->get_result();
        $rows = mysqli_num_rows($result); 
        $stmt->close();
    
        return $rows;
    }

    function fetch_friends_number($reciever, $sender) {
        $stmt = $this->conn->prepare("SELECT * FROM friends WHERE sender = ? AND reciever = ?");
        $stmt->bind_param("ss", $sender, $reciever);
        $stmt->execute();
        $result = $stmt->get_result();
        $rows = mysqli_num_rows($result); 
        $stmt->close();
    
        return $rows;
    }

    function fetch_creation_date($username) {
            $stmt = $this->conn->prepare("SELECT created FROM users WHERE username = ?");
            $stmt->bind_param("s", $username);
            $stmt->execute();
        $result = $stmt->get_result();
        $user = $result->fetch_assoc();

        if($result->num_rows === 0) 
            return 0;
        else
            return $user['created'];

        $stmt->close();
    }

    function fetch_user_videos($id) {
        $stmt = $this->conn->prepare("SELECT rid FROM videos WHERE author = ?");
        $stmt->bind_param("s", $id);
        $stmt->execute(); 
        $result = $stmt->get_result();
        $rows = mysqli_num_rows($result); 
        $stmt->close();
    
        return $rows;
    }

    function if_subscribed($user, $reciever) {
        $stmt = $this->conn->prepare("SELECT `reciever` FROM subscribers WHERE sender = ? AND reciever = ?");
        $stmt->bind_param("ss", $user, $reciever);
        $stmt->execute();
        $result = $stmt->get_result();
        $user = $result->fetch_assoc();
        if($result->num_rows === 1) { return true; } else { return false; }
        $stmt->close();
        
        return $user;
    }

    function if_joined_group($user, $groupid) {
        $stmt = $this->conn->prepare("SELECT `togroup` FROM group_members WHERE username = ? AND togroup = ?");
        $stmt->bind_param("ss", $user, $groupid);
        $stmt->execute();
        $result = $stmt->get_result();
        $user = $result->fetch_assoc();
        if($result->num_rows === 1) { return true; } else { return false; }
        $stmt->close();
        
        return $user;
    }

    function if_cooldown($user) {
        $stmt = $this->conn->prepare("SELECT * FROM users WHERE username = ? AND cooldown_comment >= NOW() - INTERVAL 1 MINUTE");
        $stmt->bind_param("s", $user);
        $stmt->execute();
        $result = $stmt->get_result();
        $user = $result->fetch_assoc();
        if($result->num_rows === 1) { return true; } else { return false; }
        $stmt->close();
        
        return $user;
    }

    function if_upload_cooldown($user) {
        $stmt = $this->conn->prepare("SELECT * FROM users WHERE username = ? AND upload_cooldown >= NOW() - INTERVAL 10 MINUTE");
        $stmt->bind_param("s", $user);
        $stmt->execute();
        $result = $stmt->get_result();
        $user = $result->fetch_assoc();
        if($result->num_rows === 1) { return true; } else { return false; }
        $stmt->close();
        
        return $user;
    }

    function video_responded($reciever, $sending) {
        $stmt = $this->conn->prepare("SELECT `video` FROM video_response WHERE video = ? AND toid = ?");
        $stmt->bind_param("ss", $sending, $reciever);
        $stmt->execute();
        $result = $stmt->get_result();
        $user = $result->fetch_assoc();
        if($result->num_rows === 1) { return true; } else { return false; }
        $stmt->close();
        
        return $user;
    }

    function is_admin($username) {
        $stmt = $this->conn->prepare("SELECT * FROM users WHERE username = ? AND status = 'admin'");
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $result = $stmt->get_result();
        if($result->num_rows === 0) { return false; } else { return true; }
        $stmt->close();
    }    

    function if_favorited($user, $reciever) {
        $stmt = $this->conn->prepare("SELECT `reciever` FROM favorite_video WHERE sender = ? AND reciever = ?");
        $stmt->bind_param("ss", $user, $reciever);
        $stmt->execute();
        $result = $stmt->get_result();
        $user = $result->fetch_assoc();
        if($result->num_rows === 1) { return true; } else { return false; }
        $stmt->close();
        
        return $user;
    }

    function fetch_mail($id) {
        $stmt = $this->conn->prepare("SELECT * FROM pms WHERE id = ?");
        $stmt->bind_param("s", $id);
        $stmt->execute();
    $result = $stmt->get_result();
    $mail = $result->fetch_assoc();
    $stmt->close();
    
    return $mail;
    }    

    function get_channel_views(string $rid) {
            $stmt = $this->conn->prepare("SELECT * FROM channel_views WHERE channel = ?");
            $stmt->bind_param("s", $rid);
            $stmt->execute();
        $result = $stmt->get_result();
        return $result->num_rows;

        $stmt->close();
    }


    function if_liked_video($user, $videoid) {
        $stmt = $this->conn->prepare("SELECT `sender` FROM likes WHERE sender = ? AND reciever = ? AND type = 'l'");
        $stmt->bind_param("ss", $user, $videoid);
        $stmt->execute();
        $result = $stmt->get_result();
        $user = $result->fetch_assoc();
        if($result->num_rows === 1) { return true; } else { return false; }
        $stmt->close();
        
        return $user;
    }

    function get_unread_pms($username) {
        $stmt = $this->conn->prepare("SELECT * FROM pms WHERE touser = ? AND readed = 'n'");
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $result = $stmt->get_result();
        $rows = mysqli_num_rows($result); 
        $stmt->close();
    
        return $rows;
    }

    function get_user_from_username($name) {
            $stmt = $this->conn->prepare("SELECT * FROM users WHERE username = ?");
            $stmt->bind_param("s", $name);
            $stmt->execute();
        $result = $stmt->get_result();
        $user = $result->fetch_assoc();
        if($result->num_rows === 0) return('That user does not exist.');
        $stmt->close();

        return $user;
    }

    function fetch_user_favorites(string $username) {
        $stmt = $this->conn->prepare("SELECT reciever FROM favorite_video WHERE sender = ?");
        $stmt->bind_param("s", $username);
        $stmt->execute(); 
        $result = $stmt->get_result();
        $rows = mysqli_num_rows($result); 
        $stmt->close();
    
        return $rows;
    }

    function user_exists($user) {
        $stmt = $this->conn->prepare("SELECT `username` FROM users WHERE username = ?");
        $stmt->bind_param("s", $user);
        $stmt->execute();
    $result = $stmt->get_result();
    $user = $result->fetch_assoc();
    if($result->num_rows === 1) { return true; } else { return false; }
    $stmt->close();
    
    return $user;
    }

    function fetch_group_member_count($id) {
        $stmt = $this->conn->prepare("SELECT * FROM group_members WHERE togroup = ?");
        $stmt->bind_param("s", $id);
        $stmt->execute();
        $result = $stmt->get_result();
        $rows = mysqli_num_rows($result); 
        $stmt->close();
    
        return $rows;
    }


    function fetch_subs_count($username) {
        $stmt = $this->conn->prepare("SELECT * FROM subscribers WHERE reciever = ?");
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $result = $stmt->get_result();
        $rows = mysqli_num_rows($result); 
        $stmt->close();
    
        return $rows;
    }

    function fetch_subscriptions($username) {
        $stmt = $this->conn->prepare("SELECT * FROM subscribers WHERE sender = ?");
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $result = $stmt->get_result();
        $rows = mysqli_num_rows($result); 
        $stmt->close();
    
        return $rows;
    }

    function fetch_thread_replies($username) {
        $stmt = $this->conn->prepare("SELECT * FROM forum_replies WHERE toid = ?");
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $result = $stmt->get_result();
        $rows = mysqli_num_rows($result); 
        $stmt->close();
    
        return $rows;
    }

    function fetch_user_video_banner($username) {
        $stmt = $this->conn->prepare("SELECT subbutton FROM users WHERE username = ?");
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $result = $stmt->get_result();
        while($row = $result->fetch_assoc()) {
            $subbutton = $row['subbutton'];
        }
        
        if(empty($subbutton)) {
            return "";
        } else {
            return $subbutton;
        }
        
        $stmt->close();
    }

    function fetch_user_pfp($username) {
        $stmt = $this->conn->prepare("SELECT pfp FROM users WHERE username = ?");
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $result = $stmt->get_result();
        while($row = $result->fetch_assoc()) {
            $pfp = $row['pfp'];
        }
        
        //fallback for deleted accs
        if(!isset($pfp)) {
            return "default.png";
        } else {
            return $pfp;
        }
        
        $stmt->close();
    }

    function fetch_category_name(string $rid) {
            $stmt = $this->conn->prepare("SELECT * FROM forum_category WHERE title = ?");
            $stmt->bind_param("s", $rid);
            $stmt->execute();
        $result = $stmt->get_result();
        $video = $result->fetch_assoc();

        if($result->num_rows === 0) 
            return 0;
        else
            return $video;

        $stmt->close();
    }

    function fetch_thread_name(string $rid) {
            $stmt = $this->conn->prepare("SELECT * FROM forum_thread WHERE id = ?");
            $stmt->bind_param("s", $rid);
            $stmt->execute();
        $result = $stmt->get_result();
        $thread = $result->fetch_assoc();

        if($result->num_rows === 0) 
            return 0;
        else
            return $thread;

        $stmt->close();
    }

    function fetch_latest_forum_post(string $rid) {
            $stmt = $this->conn->prepare("SELECT * FROM forum_thread WHERE category = ? ORDER BY id DESC LIMIT 1");
            $stmt->bind_param("s", $rid);
            $stmt->execute();
        $result = $stmt->get_result();
        $thread = $result->fetch_assoc();

        if($result->num_rows === 0) 
            return 0;
        else
            return $thread;

        $stmt->close();
    }

    function fetch_latest_forum_post_reply(string $rid) {
            $stmt = $this->conn->prepare("SELECT * FROM forum_replies WHERE toid = ? ORDER BY id DESC LIMIT 1");
            $stmt->bind_param("s", $rid);
            $stmt->execute();
        $result = $stmt->get_result();
        $thread = $result->fetch_assoc();

        if($result->num_rows === 0) 
            return 0;
        else
            return $thread;

        $stmt->close();
    }
}

/**
 * Use this to fetch data for videos.
 *
 * Blah blah blah blah.
 *
 * @copyright  FulpTube
 * @since      Class available since FulpTube backend rewrite 5/9/21
 */ 
class video_fetch_utils {
    public $conn;
    function initialize_db_var($conn) {
        $this->conn = $conn;    
    }

    function initialize_server_vars($server) {
        $this->server = $server;    
    }

    function fetch_history_ammount($username) {
        $stmt = $this->conn->prepare("SELECT * FROM history WHERE author = ?");
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $result = $stmt->get_result();
        $rows = mysqli_num_rows($result); 
        $stmt->close();
    
        return $rows;
    }

    function get_video_stars($id) {
        $stmt = $this->conn->prepare("SELECT * FROM stars WHERE reciever = ?");
        $stmt->bind_param("s", $id);
        $stmt->execute();
        $result = $stmt->get_result();
        $rows = mysqli_num_rows($result); 
        $stmt->close();
    
        return $rows;
    }    

    function get_video_stars_level($id, $level) {
        $stmt = $this->conn->prepare("SELECT * FROM stars WHERE reciever = ? AND type = ?");
        $stmt->bind_param("si", $id, $level);
        $stmt->execute();
        $result = $stmt->get_result();
        $rows = mysqli_num_rows($result); 
        $stmt->close();
    
        return $rows;
    }

    function get_video_likes($id) {
        $stmt = $this->conn->prepare("SELECT * FROM likes WHERE reciever = ? AND type = 'l'");
        $stmt->bind_param("s", $id);
        $stmt->execute();
        $result = $stmt->get_result();
        $rows = mysqli_num_rows($result); 
        $stmt->close();
    
        return $rows;
    }    
    

    function get_video_dislikes($id) {
        $stmt = $this->conn->prepare("SELECT * FROM likes WHERE reciever = ? AND type = 'd'");
        $stmt->bind_param("s", $id);
        $stmt->execute();
        $result = $stmt->get_result();
        $rows = mysqli_num_rows($result); 
        $stmt->close();
    
        return $rows;
    }

    function get_category_threads($id) {
        $stmt = $this->conn->prepare("SELECT * FROM forum_thread WHERE category = ?");
        $stmt->bind_param("s", $id);
        $stmt->execute();
        $result = $stmt->get_result();
        $rows = mysqli_num_rows($result); 
        $stmt->close();
    
        return $rows;
    }


    function get_video_responses($id) {
        $stmt = $this->conn->prepare("SELECT * FROM video_response WHERE toid = ?");
        $stmt->bind_param("s", $id);
        $stmt->execute();
        $result = $stmt->get_result();
        $rows = mysqli_num_rows($result); 
        $stmt->close();
    
        return $rows;
    }

    function get_video_response(string $rid) {
            $stmt = $this->conn->prepare("SELECT * FROM video_response WHERE id = ?");
            $stmt->bind_param("s", $rid);
            $stmt->execute();
        $result = $stmt->get_result();
        $video = $result->fetch_assoc();

        if($result->num_rows === 0) 
            return 0;
        else
            return $video;

        $stmt->close();
    }

    function fetch_videos_from_user($user) {
        $stmt = $this->conn->prepare("SELECT rid FROM videos WHERE author = ?");
        $stmt->bind_param("s", $user);
        $stmt->execute(); 
        $result = $stmt->get_result();
        $rows = mysqli_num_rows($result); 
        $stmt->close();
    
        return $rows;
    }

    function get_comments_from_video($id) {
        $stmt = $this->conn->prepare("SELECT * FROM comments WHERE toid = ?");
        $stmt->bind_param("s", $id);
        $stmt->execute();
        $result = $stmt->get_result();
        $rows = mysqli_num_rows($result); 
        $stmt->close();
    
        return $rows;
    }

    function fetch_comment_likes($id) {
        $stmt = $this->conn->prepare("SELECT * FROM comment_likes WHERE reciever = ? AND type = 'l'");
        $stmt->bind_param("s", $id);
        $stmt->execute();
        $result = $stmt->get_result();
        $rows = mysqli_num_rows($result); 
        $stmt->close();
    
        return $rows;
    }

    function fetch_comment_dislikes($id) {
        $stmt = $this->conn->prepare("SELECT * FROM comment_likes WHERE reciever = ? AND type = 'd'");
        $stmt->bind_param("s", $id);
        $stmt->execute();
        $result = $stmt->get_result();
        $rows = mysqli_num_rows($result); 
        $stmt->close();
    
        return $rows;
    }

    function time_elapsed_string($datetime, $full = false) {
        global $cLang;
        $now = new DateTime;
        $ago = new DateTime($datetime);
        $diff = $now->diff($ago);
    
        $diff->w = floor($diff->d / 7);
        $diff->d -= $diff->w * 7;
    
        if(!isset($cLang)) {
            $string = array(
                'y' => 'year',
                'm' => 'month',
                'w' => 'week',
                'd' => 'day',
                'h' => 'hour',
                'i' => 'minute',
                's' => 'second',
            );
        } else {
            $string = array(
                'y' => $cLang['year'],
                'm' => $cLang['month'],
                'w' => $cLang['week'],
                'd' => $cLang['day'],
                'h' => $cLang['hour'],
                'i' => $cLang['minute'],
                's' => $cLang['second'],
            );
        }
    
        if(!isset($cLang)) {
            foreach ($string as $k => &$v) {
                if ($diff->$k) {
                    $v = $diff->$k . ' ' . $v . ($diff->$k > 1 ? 's' : '');
                } else {
                    unset($string[$k]);
                }
            }
        } else {
            foreach ($string as $k => &$v) {
                if ($diff->$k) {
                    $v = $diff->$k . ' ' . $v . ($diff->$k > 1 ? $cLang['pluralTimeRight'] : '');
                } else {
                    unset($string[$k]);
                }
            }
        }
    
        if(!isset($cLang)) {
            if (!$full) $string = array_slice($string, 0, 1);
            return $string ? implode(', ', $string) . ' ago' : 'just now';
        } else {
            if (!$full) $string = array_slice($string, 0, 1);
            return $string ? implode($cLang['agoLeft'], $string) . " " . " " . $cLang['agoRight'] : $cLang['justNow'];
        }
    }

    function fetch_latest_video(string $author) {
            $stmt = $this->conn->prepare("SELECT * FROM videos WHERE author = ? ORDER BY id DESC LIMIT 1");
            $stmt->bind_param("s", $author);
            $stmt->execute();
        $result = $stmt->get_result();
        $video = $result->fetch_assoc();

        if($result->num_rows === 0) 
            return 0;
        else
            return $video;

        $stmt->close();
    }

    function fetch_rand_featured_vid() {
            $stmt = $this->conn->prepare("SELECT * FROM videos WHERE featured = 'v' ORDER BY rand() DESC LIMIT 1");
            $stmt->execute();
        $result = $stmt->get_result();
        $video = $result->fetch_assoc();

        if($result->num_rows === 0) 
            return 0;
        else
            return $video;

        $stmt->close();
    }

    function fetch_video_rid(string $rid) {
            $stmt = $this->conn->prepare("SELECT * FROM videos WHERE rid = ?");
            $stmt->bind_param("s", $rid);
            $stmt->execute();
        $result = $stmt->get_result();
        $video = $result->fetch_assoc();

        if($result->num_rows === 0) 
            return 0;
        else
            return $video;

        $stmt->close();
    }

    function get_quicklist_video(string $rid) {
            $stmt = $this->conn->prepare("SELECT * FROM quicklist_videos WHERE id = ?");
            $stmt->bind_param("s", $rid);
            $stmt->execute();
        $result = $stmt->get_result();
        $video = $result->fetch_assoc();

        if($result->num_rows === 0) 
            return 0;
        else
            return $video;

        $stmt->close();
    }


    function fetch_playlist_rid(string $rid) {
            $stmt = $this->conn->prepare("SELECT * FROM playlists WHERE rid = ?");
            $stmt->bind_param("s", $rid);
            $stmt->execute();
        $result = $stmt->get_result();
        $playlist = $result->fetch_assoc();

        if($result->num_rows === 0) 
            return 0;
        else
            return $playlist;

        $stmt->close();
    }

    function fetch_group_id(string $id) {
            $stmt = $this->conn->prepare("SELECT * FROM user_groups WHERE id = ?");
            $stmt->bind_param("s", $id);
            $stmt->execute();
        $result = $stmt->get_result();
        $group = $result->fetch_assoc();

        if($result->num_rows === 0) 
            return 0;
        else
            return $group;

        $stmt->close();
    }

    function fetch_group_membership_id(string $id) {
            $stmt = $this->conn->prepare("SELECT * FROM group_members WHERE id = ?");
            $stmt->bind_param("s", $id);
            $stmt->execute();
        $result = $stmt->get_result();
        $group = $result->fetch_assoc();

        if($result->num_rows === 0) 
            return 0;
        else
            return $group;

        $stmt->close();
    }

    function get_video_views(string $rid) {
            $stmt = $this->conn->prepare("SELECT * FROM views WHERE videoid = ?");
            $stmt->bind_param("s", $rid);
            $stmt->execute();
        $result = $stmt->get_result();
        return $result->num_rows;

        $stmt->close();
    }

    function fetch_less_video_rid(string $rid) {
            $stmt = $this->conn->prepare("SELECT rid, title, thumbnail, duration, title, author FROM videos WHERE rid = ?");
            $stmt->bind_param("s", $rid);
            $stmt->execute();
        $result = $stmt->get_result();
        $video = $result->fetch_assoc();

        if($result->num_rows === 0) 
            return 0;
        else
            return $video;

        $stmt->close();
    }

    function fetch_video_views(string $vidid) {
        $stmt = $this->conn->prepare("SELECT * FROM views WHERE videoid = ?");
        $stmt->bind_param("s", $vidid);
        $stmt->execute();
        $result = $stmt->get_result();
        $rows = mysqli_num_rows($result); 
        $stmt->close();
    
        return $rows;
    }

    function video_exists($user) {
        $stmt = $this->conn->prepare("SELECT `rid` FROM videos WHERE rid = ?");
        $stmt->bind_param("s", $user);
        $stmt->execute();
    $result = $stmt->get_result();
    $user = $result->fetch_assoc();
    if($result->num_rows === 1) { return true; } else { return false; }
    $stmt->close();
    
    return $user;
    }

    function group_exists($id) {
        $stmt = $this->conn->prepare("SELECT `id` FROM user_groups WHERE id = ?");
        $stmt->bind_param("s", $id);
        $stmt->execute();
    $result = $stmt->get_result();
    $id = $result->fetch_assoc();
    if($result->num_rows === 1) { return true; } else { return false; }
    $stmt->close();
    
    return $id;
    }

    function fetch_views_from_user($user) {
        $stmt = $this->conn->prepare("SELECT `rid` FROM videos WHERE author = ?");
        $stmt->bind_param("s", $user);
        $stmt->execute();
        $result = $stmt->get_result();
        $views = 0;
        while($row = $result->fetch_assoc()) {
            $views = $views + $this->fetch_video_views($row['rid']);
        }
        return $views;
        $stmt->close();
    }

    function fetch_user_videos(string $id) {
        $stmt = $this->conn->prepare("SELECT rid FROM videos WHERE author = ?");
        $stmt->bind_param("s", $id);
        $stmt->execute(); 
        $result = $stmt->get_result();
        $rows = mysqli_num_rows($result); 
        $stmt->close();
    
        return $rows;
    }

    function timestamp(int $seconds) {
        if ($seconds > 60*60*24) {
            // over a day
            return sprintf("%d:%s:%s:%s",
                floor($seconds/60/60/24),                                 // Days
                str_pad( floor($seconds/60/60%24), 2, "0", STR_PAD_LEFT), // Hours
                str_pad( floor($seconds/60%60),    2, "0", STR_PAD_LEFT), // Minutes
                str_pad(       $seconds%60,        2, "0", STR_PAD_LEFT)  // Seconds
            );
        } else if ($seconds > 60*60) {
            // over an hour
            return sprintf("%d:%s:%s",
                        floor($seconds/60/60),                        // Hours
                str_pad(floor($seconds/60%60), 2, "0", STR_PAD_LEFT), // Minutes
                str_pad(      $seconds%60,     2, "0", STR_PAD_LEFT)  // Seconds
            );
        } else {
            // less than an hour
            return sprintf("%d:%s",
                        floor($seconds/60),                       // Minutes
                str_pad(      $seconds%60,  2, "0", STR_PAD_LEFT) // Seconds
            );
        }
    }

    function parse_title($text) {
        $text = trim($text);
        if(strlen($text) >= 25) {
            $text = substr($text, 0, 25) . "...";
        } 
        $text = htmlspecialchars($text);
        return $text;
    }   

    function parseTitle($text) {
        $text = trim($text);
        if(strlen($text) >= 45) {
            $text = substr($text, 0, 45) . "...";
        } 
        $text = htmlspecialchars($text);
        return $text;
    }   

    function parseTextNoLink($text) {
        $text = trim($text);
        if(strlen($text) >= 90) {
            $text = substr($text, 0, 100) . "...";
        } 
        $text = htmlspecialchars($text);
        $text = str_replace(PHP_EOL, "<br>", $text);
        return $text;
    }    

    function parseDescriptionVideoManager($text) {
        $text = trim($text);
        if(strlen($text) >= 30) {
            $text = substr($text, 0, 30) . "...";
        } 
        $text = htmlspecialchars($text);
        return $text;
    }    

    function parseTextDescription($text) {
        $text = trim($text);
        $text = htmlspecialchars($text);
        $text = preg_replace("/@([a-zA-Z0-9-]+|\\+\\])/", "<a href='/user/$1'>@$1</a>", $text);
        $text = str_replace(PHP_EOL, "<br>", $text);
        return $text;
    }    

    function parseTextComment($text) {
        $text = trim($text);
        $text = htmlspecialchars($text);
        $text = preg_replace("/@([a-zA-Z0-9-]+|\\+\\])/", "<a href='/user/$1'>@$1</a>", $text);
        $text = preg_replace("/((\d{1,2}:)+\d{2})/", "<a onclick=\"setTimePlayer('$1')\">$1</a>", $text);
        $text = str_replace(PHP_EOL, "<br>", $text);
        return $text;
    }    
}
?>