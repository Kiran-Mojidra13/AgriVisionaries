<?php
date_default_timezone_set('Asia/Kolkata');
include('database.inc.php');

if (isset($_POST['txt'])) {
    $txt = trim($_POST['txt']); // Trim whitespace

    // Escape input to prevent SQL injection
    $txt = mysqli_real_escape_string($con, $txt);

    // SQL query to find a matching reply
    $sql = "SELECT reply FROM chatbot_hints WHERE question LIKE ?";
    
    // Prepare the SQL statement
    $stmt = mysqli_prepare($con, $sql);
    
    if ($stmt === false) {
        die('Error preparing the SQL query: ' . mysqli_error($con));
    }

    // Bind the parameter
    $search_txt = "%" . $txt . "%";
    mysqli_stmt_bind_param($stmt, "s", $search_txt);

    // Execute the statement
    mysqli_stmt_execute($stmt);

    // Get the result
    $res = mysqli_stmt_get_result($stmt);

    if ($res && mysqli_num_rows($res) > 0) {
        $row = mysqli_fetch_assoc($res);
        $html = $row['reply'];
    } else {
        $html = "Sorry, I couldn't understand your question. Can you please rephrase it?";
    }

    // Escape bot's reply to avoid syntax errors in SQL query
    $html_escaped = mysqli_real_escape_string($con, $html);
    
    // Insert user's message into the `message` table using prepared statement
    $added_on = date('Y-m-d H:i:s');
    $insert_user_sql = "INSERT INTO message (message, added_on, type) VALUES (?, ?, 'user')";
    $stmt_user = mysqli_prepare($con, $insert_user_sql);
    
    if ($stmt_user) {
        mysqli_stmt_bind_param($stmt_user, "ss", $txt, $added_on);
        mysqli_stmt_execute($stmt_user);
        mysqli_stmt_close($stmt_user);
    } else {
        echo "Error inserting user message: " . mysqli_error($con);
    }

    // Insert bot's reply into the `message` table using prepared statement
    $insert_bot_sql = "INSERT INTO message (message, added_on, type) VALUES (?, ?, 'bot')";
    $stmt_bot = mysqli_prepare($con, $insert_bot_sql);
    
    if ($stmt_bot) {
        mysqli_stmt_bind_param($stmt_bot, "ss", $html_escaped, $added_on);
        mysqli_stmt_execute($stmt_bot);
        mysqli_stmt_close($stmt_bot);
    } else {
        echo "Error inserting bot message: " . mysqli_error($con);
    }

    // Output the bot's reply
    echo $html;

    // Close the main prepared statement
    mysqli_stmt_close($stmt);
} else {
    echo "Error: No message received.";
}
?>
