<?php

function url_for($script_path) {
    // add the leading '/' if not present
    if($script_path[0] != '/') {
        $script_path = "/" . $script_path;
    }
    return WWW_ROOT . $script_path;
}

function u($string="") {
    return urlencode($string);
}

function raw_u($string="") {
    return rawurlencode($string);
}

function h($string="") {
    return htmlspecialchars($string);
}

function error_404() {
    header($_SERVER["SERVER_PROTOCOL"] . " 404 Not Found");
    exit();
}

function error_500() {
    header($_SERVER["SERVER_PROTOCOL"] . " 500 Internal Server Error");
    exit();
}

function redirect_to($location) {
    header('Location: ' . $location);
    exit();
}

function is_post_request() {
    return $_SERVER['REQUEST_METHOD'] == 'POST';
}

function is_get_request() {
    return $_SERVER['REQUEST_METHOD'] == 'GET';
}

function display_errors($errors=array()) {
    $output = '';
    if (!empty($errors)) {
        $output .= "<div class=\"alert alert-danger\">";
        $output .= "<strong>Please fix the following errors:</strong>";
        $output .= "<ul>";
        foreach ($errors as $error) {
            $output .= "<li>" . h($error) . "</li>";
        }
        $output .= "</ul>";
        $output .= "</div>";
    }
    return $output;
}

function get_and_clear_session_message() {
    if(isset($_SESSION['message']) && $_SESSION['message'] != '') {
        $msg = $_SESSION['message'];
        unset($_SESSION['message']);
        return $msg;
    }
}

function display_session_message() {
    $msg = get_and_clear_session_message();
    if(!is_blank($msg)) {
        return '<div class="alert alert-success">' . $msg . '</div>';
    }
}

function send_email_notification($pushup) {
    $user = find_user_by_id($pushup['user_id']);

    // Todo: Change to dynamic look-up of email addresses
    $to = 'mwhang@me.com,amjad.usman@gmail.com';

    $subject = 'BFN100: ' .  $user['username'] . ' just pounded out ' . $pushup['amount'] . ' push-ups!';

    $message = 'Date: ' . $pushup['date'] . "\r\n";
    $message .= 'Name: ' . $user['username'] . "\r\n";
    $message .= '# of push-ups: ' . $pushup['amount'] . "\r\n";
    $message .= 'Comment: ' . $pushup['comment'];

    $headers = 'From: BFN100 <bfn100@tinybot.ca>' . "\r\n" .
        'Reply-To: bfn100@tinybot.ca' . "\r\n" .
        'X-Mailer: PHP/' . phpversion();

    // Todo: Change to use PhpMailer or other mail tool that supports SPF verification
    mail($to, $subject, $message, $headers);
}