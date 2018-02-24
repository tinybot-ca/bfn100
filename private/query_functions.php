<?php

// subjects

function find_all_subjects($options=[]) {
    global $db;

    $visible = $options['visible'] ?? false;

    $sql = "SELECT * FROM subjects ";
    if ($visible) {
        $sql .= "WHERE visible=true ";
    }
    $sql .= "ORDER BY position ASC";
    //echo $sql . "<br />";
    $result = mysqli_query($db, $sql);
    confirm_result_set($result);
    return $result;
}

function find_subject_by_id($id, $options=[]) {
    global $db;

    $visible = $options['visible'] ?? false;

    $sql = "SELECT * FROM subjects ";
    $sql .= "WHERE id='" . db_escape($db, $id) . "' ";
    if ($visible) {
        $sql .= "AND visible=true";
    }
    //echo "<br />SQL Code: " . $sql;
    $result = mysqli_query($db, $sql);
    confirm_result_set($result);
    $subject = mysqli_fetch_assoc($result);
    mysqli_free_result($result);
    return $subject; // returns an assoc. array
}

function find_subject_name_by_id($id) {
    global $db;

    $sql = "SELECT * FROM subjects ";
    $sql .= "WHERE id='" . db_escape($db, $id) . "'";
    //echo "<br />SQL Code: " . $sql;
    $result = mysqli_query($db, $sql);
    confirm_result_set($result);
    $subject = mysqli_fetch_assoc($result);
    mysqli_free_result($result);
    return $subject['menu_name']; // returns an assoc. array
}

function insert_subject($subject) {
    global $db;

    $errors = validate_subject($subject);
    if (!empty($errors)) {
        return $errors;
    }

    $sql = "INSERT INTO subjects ";
    $sql .= "(menu_name, position, visible) ";
    $sql .= "VALUES (";
    $sql .= "'" . db_escape($db, $subject['menu_name']) . "', ";
    $sql .= "'" . db_escape($db, $subject['position']) . "', ";
    $sql .= "'" . db_escape($db, $subject['visible']) . "'";
    $sql .= ")";
    $result = mysqli_query($db, $sql);
    // For INSERT statements, $result is true/false
    if ($result) {
        return true;
    } else {
        // INSERT failed
        echo mysqli_error($db);
        db_disconnect($db);
        exit();
    }
}

function edit_subject($subject) {
    global $db;

    $errors = validate_subject($subject);
    if (!empty($errors)) {
        return $errors;
    }

    $sql = "UPDATE subjects SET ";
    $sql .= "menu_name='" . db_escape($db, $subject['menu_name']) . "',";
    $sql .= "position='" . db_escape($db, $subject['position']) . "',";
    $sql .= "visible='" . db_escape($db, $subject['visible']) . "' ";
    $sql .= "WHERE id='" . db_escape($db, $subject['id']) ."' ";
    $sql .= "LIMIT 1";

    $result = mysqli_query($db, $sql);
    // For UPDATE statements, $result is true/false
    if ($result) {
        return true;
    } else {
        // UPDATE failed
        echo mysqli_error($db);
        db_disconnect($db);
        exit();
    }
}

function delete_subject($id) {
    global $db;

    // Check if there are any pages that have this subject ID assigned to it
    $errors = [];
    $pages = find_all_pages();
    while ($page = mysqli_fetch_assoc($pages)) {
        if ($page['subject_id'] == $id) {
            $errors[] = "Noooo! This subject has children!";
            return $errors;
        }
    }

    $sql = "DELETE FROM subjects ";
    $sql .= "WHERE id='" . db_escape($db, $id) . "' ";
    $sql .= "LIMIT 1";

    $result = mysqli_query($db, $sql);
    if ($result) {
        return true;
    } else {
        // DELETE failed
        echo mysqli_error($db);
        db_disconnect($db);
        exit();
    }
}

function validate_subject($subject) {
    $errors = [];

    // menu name
    if (is_blank($subject['menu_name'])) {
        $errors[] = "Name cannot be blank.";
    } elseif (!has_length($subject['menu_name'], ['min' => 2, 'max' => 255])) {
        $errors[] = "Name must be between 2 and 255 characters.";
    }

    // position
    // Make sure we are working with an integer
    $position_int = (int)$subject['position'];
    if ($position_int <= 0) {
        $errors[] = "Position must be greater than zero.";
    }
    if ($position_int > 999) {
        $errors[] = "Position must be less than 999.";
    }

    // visible
    // Make sure we are working with a string
    $visible_str = (string)$subject['visible'];
    if (!has_inclusion_of($visible_str, ["0", "1"])) {
        $errors[] = "Visible must be true or false.";
    }

    return $errors;
}

// pages

function find_all_pages() {
    global $db;

    $sql = "SELECT * FROM pages ";
    $sql .= "ORDER BY subject_id ASC, position ASC";
    //echo $sql . "<br />";
    $result = mysqli_query($db, $sql);
    confirm_result_set($result);
    return $result;
}

function find_page_by_id($id, $options=[]) {
    global $db;

    $visible = $options['visible'] ?? false;

    $sql = "SELECT * FROM pages ";
    $sql .= "WHERE id='" . db_escape($db, $id) . "' ";
    if ($visible) {
        $sql .= "AND visible=true";
    }
    //echo "<br />SQL Code: " . $sql;
    $result = mysqli_query($db, $sql);
    confirm_result_set($result);
    $page = mysqli_fetch_assoc($result);
    mysqli_free_result($result);
    return $page; // returns an assoc. array
}

function find_pages_by_subject_id($subject_id, $options=[]) {
    global $db;

    $visible = $options['visible'] ?? false;

    $sql = "SELECT * FROM pages ";
    $sql .= "WHERE subject_id='" . db_escape($db, $subject_id) . "' ";
    if ($visible) {
        $sql .= "AND visible=true ";
    }
    $sql .= "ORDER BY position ASC";
    //echo "<br />SQL Code: " . $sql;
    $result = mysqli_query($db, $sql);
    confirm_result_set($result);
    return $result; // returns an assoc. array
}

function count_pages_by_subject_id($subject_id, $options=[]) {
    global $db;

    $visible = $options['visible'] ?? false;

    $sql = "SELECT COUNT(id) FROM pages ";
    $sql .= "WHERE subject_id='" . db_escape($db, $subject_id) . "' ";
    if ($visible) {
        $sql .= "AND visible=true ";
    }
    $sql .= "ORDER BY position ASC";
    //echo "<br />SQL Code: " . $sql;
    $result = mysqli_query($db, $sql);
    confirm_result_set($result);

    $row = mysqli_fetch_row($result);
    mysqli_free_result($result);
    $count = $row[0];

    return $count;
}

function insert_page($page) {
    global $db;

    $errors = validate_page($page);
    if (!empty($errors)) {
        return $errors;
    }

    $sql = "INSERT INTO pages ";
    $sql .= "(menu_name, position, subject_id, visible, content) ";
    $sql .= "VALUES (";
    $sql .= "'" . db_escape($db, $page['menu_name']) . "', ";
    $sql .= "'" . db_escape($db, $page['position']) . "', ";
    $sql .= "'" . db_escape($db, $page['subject_id']) . "', ";
    $sql .= "'" . db_escape($db, $page['visible']) . "', ";
    $sql .= "'" . db_escape($db, $page['content']) . "'";
    $sql .= ")";
    $result = mysqli_query($db, $sql);
    // For INSERT statements, $result is true/false
    if ($result) {
        return true;
    } else {
        // INSERT failed
        echo mysqli_error($db);
        db_disconnect($db);
        exit();
    }
}

function edit_page($page) {
    global $db;

    $errors = validate_page($page);
    if (!empty($errors)) {
        return $errors;
    }

    $sql = "UPDATE pages SET ";
    $sql .= "menu_name='" . db_escape($db, $page['menu_name']) . "',";
    $sql .= "subject_id='" . db_escape($db, $page['subject_id']) . "',";
    $sql .= "position='" . db_escape($db, $page['position']) . "',";
    $sql .= "visible='" . db_escape($db, $page['visible']) . "', ";
    $sql .= "content='" . db_escape($db, $page['content']) . "' ";
    $sql .= "WHERE id='" . db_escape($db, $page['id']) ."' ";
    $sql .= "LIMIT 1";

    $result = mysqli_query($db, $sql);
    // For UPDATE statements, $result is true/false
    if ($result) {
        return true;
    } else {
        // UPDATE failed
        echo mysqli_error($db);
        db_disconnect($db);
        exit();
    }
}

function delete_page($id) {
    global $db;

    $sql = "DELETE FROM pages ";
    $sql .= "WHERE id='" . db_escape($db, $id) . "' ";
    $sql .= "LIMIT 1";

    $result = mysqli_query($db, $sql);
    if ($result) {
        return true;
    } else {
        // DELETE failed
        echo mysqli_error($db);
        db_disconnect($db);
        exit();
    }
}

function validate_page($page) {
    $errors = [];

    // menu name
    if (is_blank($page['menu_name'])) {
        $errors[] = "Name cannot be blank.";
    } elseif (!has_length($page['menu_name'], ['min'=> 2, 'max' => 255])) {
        $errors[] = "Name must be between 2 and 255 characters.";
    } elseif (!has_unique_page_menu_name($page)) {
        $errors[] = "Name must be unique.";
    }

    // subject_id
    $subject_id_int = (int)$page['subject_id'];
    if ($subject_id_int <= 0) {
        $errors[] = "Subject ID must be greater than zero.";
    }
    if ($subject_id_int > 999) {
        $errors[] = "Subject ID must be less than 999.";
    }

    // position
    $position_int = (int)$page['position'];
    if ($position_int <= 0) {
        $errors[] = "Position must be greater than zero.";
    }
    if ($position_int > 999) {
        $errors[] = "Position must be less than 999.";
    }

    // visible
    $visible_str = (string)$page['visible'];
    if (!has_inclusion_of($visible_str, ["0", "1"])) {
        $errors = "Visible must be true or false";
    }

    // content

    return $errors;
}

function has_unique_page_menu_name($page_proposed) {
    $page_proposed['id'] = $page_proposed['id'] ?? '';
    $page_current = find_page_by_id($page_proposed['id']);

    $pages = find_all_pages();
    while ($page = mysqli_fetch_assoc($pages)) {
        if ($page['menu_name'] == $page_proposed['menu_name']) {
            if ($page_proposed['menu_name'] == $page_current['menu_name']) {
                return true;
            }
            return false;
        }
    }
    return true;
}

// admins

function find_all_admins() {
    global $db;

    $sql = "SELECT * FROM admins ";
    $sql .= "ORDER BY first_name ASC, last_name ASC";
    $result = mysqli_query($db, $sql);
    confirm_result_set($result);
    return $result;
}

function find_admin_by_id($id) {
    global $db;

    $sql = "SELECT * FROM admins ";
    $sql .= "WHERE id='" . db_escape($db, $id) . "' ";
    $result = mysqli_query($db, $sql);
    confirm_result_set($result);
    $admin = mysqli_fetch_assoc($result);
    mysqli_free_result($result);
    return $admin; // returns an assoc. array
}

function find_admin_by_username($username) {
    global $db;

    $sql = "SELECT * FROM admins ";
    $sql .= "WHERE username='" . db_escape($db, $username) . "' ";
    $result = mysqli_query($db, $sql);
    confirm_result_set($result);
    $admin = mysqli_fetch_assoc($result);
    mysqli_free_result($result);
    return $admin; // returns an assoc. array
}

function insert_admin($admin) {
    global $db;

    $errors = validate_admin($admin);
    if (!empty($errors)) {
        return $errors;
    }

    $hashed_password = password_hash($admin['password'], PASSWORD_BCRYPT);

    $sql = "INSERT INTO admins ";
    $sql .= "(first_name, last_name, email, username, hashed_password) ";
    $sql .= "VALUES (";
    $sql .= "'" . db_escape($db, $admin['first_name']) . "', ";
    $sql .= "'" . db_escape($db, $admin['last_name']) . "', ";
    $sql .= "'" . db_escape($db, $admin['email']) . "', ";
    $sql .= "'" . db_escape($db, $admin['username']) . "', ";
    $sql .= "'" . db_escape($db, $hashed_password) . "'";
    $sql .= ")";
    $result = mysqli_query($db, $sql);
    // For INSERT statements, $result is true/false
    if ($result) {
        return true;
    } else {
        // INSERT failed
        echo mysqli_error($db);
        db_disconnect($db);
        exit();
    }
}

function update_admin($admin) {
    global $db;

    $password_sent = !is_blank($admin['password']);

    $errors = validate_admin($admin, ['password_required' => $password_sent]);
    if (!empty($errors)) {
        return $errors;
    }

    $hashed_password = password_hash($admin['password'], PASSWORD_BCRYPT);

    $sql = "UPDATE admins SET ";
    $sql .= "first_name='" . db_escape($db, $admin['first_name']) . "', ";
    $sql .= "last_name='" . db_escape($db, $admin['last_name']) . "', ";
    $sql .= "email='" . db_escape($db, $admin['email']) . "', ";
    if($password_sent) {
        $sql .= "hashed_password='" . db_escape($db, $hashed_password) . "', ";
    }
    $sql .= "username='" . db_escape($db, $admin['username']) . "' ";
    $sql .= "WHERE id='" . db_escape($db, $admin['id']) ."' ";
    $sql .= "LIMIT 1";

    $result = mysqli_query($db, $sql);
    // For UPDATE statements, $result is true/false
    if ($result) {
        return true;
    } else {
        // UPDATE failed
        echo mysqli_error($db);
        db_disconnect($db);
        exit();
    }
}

function delete_admin($id) {
    global $db;

    $sql = "DELETE FROM admins ";
    $sql .= "WHERE id='" . db_escape($db, $id) . "' ";
    $sql .= "LIMIT 1";

    $result = mysqli_query($db, $sql);
    if ($result) {
        return true;
    } else {
        // DELETE failed
        echo mysqli_error($db);
        db_disconnect($db);
        exit();
    }
}

function validate_admin($admin, $options=[]) {

    $password_required = $options['password_required'] ?? true;

    $errors = [];
    // first name 2-255 chars
    if (is_blank($admin['first_name'])) {
        $errors[] = "First name cannot be blank.";
    } elseif (!has_length($admin['first_name'], ['min'=> 2, 'max' => 255])) {
        $errors[] = "First name must be between 2 and 255 characters.";
    }

    // last name 2-255 chars
    if (is_blank($admin['last_name'])) {
        $errors[] = "Last name cannot be blank.";
    } elseif (!has_length($admin['last_name'], ['min'=> 2, 'max' => 255])) {
        $errors[] = "Last name must be between 2 and 255 characters.";
    }

    // email not blank, max 255, valid format
    if (is_blank($admin['email'])) {
        $errors[] = "Email cannot be blank.";
    } elseif (!has_length($admin['email'], ['max' => 255])) {
        $errors[] = "Email must be less than 255 characters.";
    } elseif (!has_valid_email_format($admin['email'])) {
        $errors[] = "Email must be a valid format.";
    }

    // username 8-255 chars, unique
    if (is_blank($admin['username'])) {
        $errors[] = "Username cannot be blank.";
    } elseif (!has_length($admin['username'], ['min'=> 8, 'max' => 255])) {
        $errors[] = "Username must be between 8 and 255 characters.";
    } elseif (!has_unique_admin_name($admin)) {
        $errors[] = "Username must be unique.";
    }

    // password 12+ chars, 1 upp, 1 low, 1 num, 1 sym
    if($password_required) {
        if (is_blank($admin['password'])) {
            $errors[] = "Password cannot be blank.";
        } elseif (!has_length($admin['password'], ['min'=> 12, 'max' => 255])) {
            $errors[] = "Password must be between 12 and 255 characters.";
        } elseif (!preg_match('/[A-Z]/', $admin['password'])) {
            $errors[] = "Password must contain at least 1 uppercase letter.";
        } elseif (!preg_match('/[a-z]/', $admin['password'])) {
            $errors[] = "Password must contain at least 1 lowercase letter.";
        } elseif (!preg_match('/[0-9]/', $admin['password'])) {
            $errors[] = "Password must contain at least 1 number.";
        } elseif (!preg_match('/[^A-Za-z0-9\s]/', $admin['password'])) {
            $errors[] = "Password must contain at least 1 symbol.";
        }

        // confirm password not blank, matches password
        if ($admin['password'] != $admin['confirm_password']) {
            $errors[] = "Passwords do not match!";
        }
    }



    return $errors;
}

function has_unique_admin_name($admin_proposed) {
    // check to see if we are dealing with a new record or editing an existing one
    $admin_proposed['id'] = $admin_proposed['id'] ?? '';
    $admin_current = find_admin_by_id($admin_proposed['id']);

    $admins = find_all_admins();
    while ($admin = mysqli_fetch_assoc($admins)) {
        if ($admin['username'] == $admin_proposed['username']) {
            // if the username being edited belongs to the current admin being edited, then don't block
            if ($admin_proposed['username'] == $admin_current['username']) {
                return true;
            }
            return false;
        }
    }
    return true;
}

// push-ups

function find_all_pushups() {
    global $db;

    $sql = "SELECT pushups.date, users.username, pushups.amount, pushups.comment, pushups.user_id FROM pushups ";
    $sql .= "INNER JOIN users ON pushups.user_id = users.id ";
    $sql .= "ORDER BY date DESC ";
    $sql .= "LIMIT 6";
    $result = mysqli_query($db, $sql);
    confirm_result_set($result);
    return $result;
}

function find_all_pushups_by_user_id($id) {
  global $db;

  $sql = "SELECT pushups.date, users.username, pushups.amount, pushups.comment, pushups.id FROM pushups ";
  $sql .= "INNER JOIN users ON pushups.user_id = users.id ";
  $sql .= "WHERE user_id='" . db_escape($db, $id) . "' ";
  $sql .= "ORDER BY date DESC";
  $result = mysqli_query($db, $sql);
  confirm_result_set($result);
  return $result;
}

function insert_pushup($pushup) {
    global $db;

    $errors = validate_pushup($pushup);
    if (!empty($errors)) {
        return $errors;
    }

    $sql = "INSERT INTO pushups ";
    $sql .= "(user_id, date, amount, comment) ";
    $sql .= "VALUES (";
    $sql .= "'" . db_escape($db, $pushup['user_id']) . "', ";
    $sql .= "'" . db_escape($db, $pushup['date']) . "', ";
    $sql .= "'" . db_escape($db, $pushup['amount']) . "', ";
    $sql .= "'" . db_escape($db, $pushup['comment']) . "'";
    $sql .= ")";
    $result = mysqli_query($db, $sql);
    // For INSERT statements, $result is true/false
    if ($result) {
        return true;
    } else {
        // INSERT failed
        echo mysqli_error($db);
        db_disconnect($db);
        exit();
    }
}

function validate_pushup($pushup) {

    $errors = [];
    // date can't be blank
    if (is_blank($pushup['date'])) {
        $errors[] = "Date cannot be blank.";
    // check if any pushup records exist for the provided date
    } elseif (find_pushups_by_date(find_user_by_id($pushup['user_id']), $pushup['date'])) {
        $errors[] = "You already have push-ups recorded for this date.";
    }

    // amount can't be blank
    if (is_blank($pushup['amount'])) {
        $errors[] = "# of push-ups cannot be blank.";
    } elseif (!($pushup['amount'] > 0 && $pushup['amount'] <= 100)) {
        $errors[] = "# of push-ups must be between 0 and 100.";
    }

    // comment must be less than 255
    if (!has_length($pushup['comment'], ['max' => 255])) {
        $errors[] = "Comment must be less than 255 characters.";
    }

    return $errors;
}

function find_pushups_by_date($user, $date) {
    global $db;

    $sql = "SELECT COUNT(id) FROM pushups ";
    $sql .= "WHERE user_id = '" . db_escape($db, $user['id']) . "' ";
    $sql .= "AND date = '" . db_escape($db, $date) . "'";
    $result = mysqli_query($db, $sql);
    confirm_result_set($result);

    $row = mysqli_fetch_row($result);
    mysqli_free_result($result);
    $count = $row[0];

    if ($count > 0) {
        return true;
    } else {
        return false;
    }
}

function find_pushup_by_id($id) {
  global $db;

  $sql = "SELECT pushups.date, users.username AS username, pushups.amount, pushups.comment, pushups.id, pushups.user_id ";
  $sql .= "FROM pushups ";
  $sql .= "INNER JOIN users ON pushups.user_id = users.id ";
  $sql .= "WHERE pushups.id='" . db_escape($db, $id) . "' ";
  $sql .= "LIMIT 1";
  $result = mysqli_query($db, $sql);
  confirm_result_set($result);
  $pushup = mysqli_fetch_assoc($result);
  return $pushup;
}


// users

function find_all_users() {
    global $db;

    $sql = "SELECT * FROM users ";
    $sql .= "ORDER BY id ASC";
    $result = mysqli_query($db, $sql);
    confirm_result_set($result);
    return $result;
}

function find_user_by_id($id) {
    global $db;

    $sql = "SELECT * FROM users ";
    $sql .= "WHERE id='" . db_escape($db, $id) . "' ";
    $result = mysqli_query($db, $sql);
    confirm_result_set($result);
    $user = mysqli_fetch_assoc($result);
    mysqli_free_result($result);
    return $user; // returns an assoc. array
}

function update_user($user) {
    global $db;

    $password_sent = !is_blank($user['password']);

    $errors = validate_user($user, ['password_required' => $password_sent]);
    if (!empty($errors)) {
        return $errors;
    }

    $hashed_password = password_hash($user['password'], PASSWORD_BCRYPT);

    $sql = "UPDATE users SET ";
    $sql .= "first_name='" . db_escape($db, $user['first_name']) . "', ";
    $sql .= "last_name='" . db_escape($db, $user['last_name']) . "', ";
    $sql .= "email='" . db_escape($db, $user['email']) . "', ";
    if($password_sent) {
        $sql .= "hashed_password='" . db_escape($db, $hashed_password) . "', ";
    }
    $sql .= "username='" . db_escape($db, $user['username']) . "' ";
    $sql .= "WHERE id='" . db_escape($db, $user['id']) ."' ";
    $sql .= "LIMIT 1";

    $result = mysqli_query($db, $sql);
    // For UPDATE statements, $result is true/false
    if ($result) {
        return true;
    } else {
        // UPDATE failed
        echo mysqli_error($db);
        db_disconnect($db);
        exit();
    }
}

function validate_user($user, $options=[]) {

    $password_required = $options['password_required'] ?? true;

    $errors = [];
    // first name 2-255 chars
    if (is_blank($user['first_name'])) {
        $errors[] = "First name cannot be blank.";
    } elseif (!has_length($user['first_name'], ['min'=> 2, 'max' => 255])) {
        $errors[] = "First name must be between 2 and 255 characters.";
    }

    // last name 2-255 chars
    if (is_blank($user['last_name'])) {
        $errors[] = "Last name cannot be blank.";
    } elseif (!has_length($user['last_name'], ['min'=> 2, 'max' => 255])) {
        $errors[] = "Last name must be between 2 and 255 characters.";
    }

    // email not blank, max 255, valid format
    if (is_blank($user['email'])) {
        $errors[] = "Email cannot be blank.";
    } elseif (!has_length($user['email'], ['max' => 255])) {
        $errors[] = "Email must be less than 255 characters.";
    } elseif (!has_valid_email_format($user['email'])) {
        $errors[] = "Email must be a valid format.";
    }

    // username 3-255 chars, unique
    if (is_blank($user['username'])) {
        $errors[] = "Username cannot be blank.";
    } elseif (!has_length($user['username'], ['min'=> 3, 'max' => 255])) {
        $errors[] = "Username must be between 3 and 255 characters.";
    } elseif (!has_unique_user_name($user)) {
        $errors[] = "Username must be unique. (Someone already has that username.)";
    }

    // password 12+ chars, 1 upp, 1 low, 1 num, 1 sym
    if($password_required) {
        if (is_blank($user['password'])) {
            $errors[] = "Password cannot be blank.";
        } elseif (!has_length($user['password'], ['min'=> 8, 'max' => 255])) {
            $errors[] = "Password must be between 8 and 255 characters.";
        } elseif (!preg_match('/[A-Z]/', $user['password'])) {
            $errors[] = "Password must contain at least 1 uppercase letter.";
        } elseif (!preg_match('/[a-z]/', $user['password'])) {
            $errors[] = "Password must contain at least 1 lowercase letter.";
        } elseif (!preg_match('/[0-9]/', $user['password'])) {
            $errors[] = "Password must contain at least 1 number.";
        } elseif (!preg_match('/[^A-Za-z0-9\s]/', $user['password'])) {
            $errors[] = "Password must contain at least 1 symbol.";
        }

        // confirm password not blank, matches password
        if ($user['password'] != $user['confirm_password']) {
            $errors[] = "Passwords do not match!";
        }
    }



    return $errors;
}

function has_unique_user_name($user_proposed) {
    // check to see if we are dealing with a new record or editing an existing one
    $user_proposed['id'] = $user_proposed['id'] ?? '';
    $user_current = find_user_by_id($user_proposed['id']);

    $users = find_all_users();
    while ($user = mysqli_fetch_assoc($users)) {
        if ($user['username'] == $user_proposed['username']) {
            // if the username being edited belongs to the current admin being edited, then don't block
            if ($user_proposed['username'] == $user_current['username']) {
                return true;
            }
            return false;
        }
    }
    return true;
}

function insert_user($user) {
    global $db;

    $errors = validate_user($user);
    if (!empty($errors)) {
        return $errors;
    }

    $hashed_password = password_hash($user['password'], PASSWORD_BCRYPT);

    $sql = "INSERT INTO users ";
    $sql .= "(first_name, last_name, email, username, status, hashed_password) ";
    $sql .= "VALUES (";
    $sql .= "'" . db_escape($db, $user['first_name']) . "', ";
    $sql .= "'" . db_escape($db, $user['last_name']) . "', ";
    $sql .= "'" . db_escape($db, $user['email']) . "', ";
    $sql .= "'" . db_escape($db, $user['username']) . "', ";
    $sql .= "'active', ";
    $sql .= "'" . db_escape($db, $hashed_password) . "'";
    $sql .= ")";
    $result = mysqli_query($db, $sql);
    // For INSERT statements, $result is true/false
    if ($result) {
        return true;
    } else {
        // INSERT failed
        echo mysqli_error($db);
        db_disconnect($db);
        exit();
    }
}

function find_user_by_username($username) {
    global $db;

    $sql = "SELECT * FROM users ";
    $sql .= "WHERE username='" . db_escape($db, $username) . "' ";
    $result = mysqli_query($db, $sql);
    confirm_result_set($result);
    $user = mysqli_fetch_assoc($result);
    mysqli_free_result($result);
    return $user; // returns an assoc. array
}

function is_admin($id) {
  global $db;

  $sql = "SELECT is_admin FROM users ";
  $sql .= "WHERE id='" . db_escape($db, $id) . "' ";
  $result = mysqli_query($db, $sql);
  confirm_result_set($result);
  $user = mysqli_fetch_assoc($result);
  mysqli_free_result($result);
  if($user['is_admin'] == true) {
    return true;
  } else {
    return false;
  }
}

function is_user_profile_owner($profile_id, $session_id) {

  if($profile_id == $session_id) {
    return true;
  } else {
    return false;
  }
}

// charts

function chart_total_by_year() {
    global $db;

    $sql = "SELECT YEAR(DATE) AS year, users.username, SUM(amount) AS total FROM pushups ";
    $sql .= "INNER JOIN users ON pushups.user_id = users.id ";
    $sql .= "GROUP BY year, users.username ";
    $sql .= "ORDER BY year DESC, users.username ASC";
    $result = mysqli_query($db, $sql);
    confirm_result_set($result);
    return $result;
}

function chart_total_overall() {
  global $db;

  $sql = "SELECT users.username, SUM(amount) AS total FROM pushups ";
  $sql .= "INNER JOIN users ON pushups.user_id = users.id ";
  $sql .= "GROUP BY users.username ";
  $sql .= "ORDER BY users.username ASC";
  $result = mysqli_query($db, $sql);
  confirm_result_set($result);
  return $result;
}

function chart_total_by_month() {
    global $db;

    $sql = "SELECT CONCAT(YEAR(date),'-',MONTH(date)) AS myDate, MONTH(date) AS month, users.username, SUM(amount) AS total FROM pushups ";
    $sql .= "INNER JOIN users ON pushups.user_id = users.id ";
    $sql .= "GROUP BY myDate, users.username ";
    $sql .= "ORDER BY date ASC";
    $result = mysqli_query($db, $sql);
    confirm_result_set($result);
    return $result;
}