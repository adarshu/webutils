<?php
//DB FUNCTIONS

function getCurTimeForDB()
{
    return date("Y-m-d H:i:s");
}

//logon user
function mysqldb_user_login($user, $password)
{
    if ($dbh = mysql_connect("127.0.0.1", $user, $password)) {
        if ($_MYSQL_DEBUG == "On")
            echo "MYSQL :: User $user successfully logged in to MYSQL system. Can now proceed to connect to user's DB!<br>";
    } else die ('MYSQL :: User $user not logged in to MYSQL system because: ' . mysql_error() . "<br>");
}

//connect to DB
function mysqldb_connect_to_db($db)
{
    if (mysql_select_db($db)) {
        if ($_MYSQL_DEBUG == "On")
            echo "MYSQL :: Successfully connected to MYSQl DB $db!<br>";
    } else die("MYSQL :: Failed to connect to MYSQl DB $db.<br>");
}

//work DB
function mysqldb_connect_full()
{
    global $_MYSQL_USERNAME, $_MYSQL_PASSWORD, $_MYSQL_DATABASE;
    mysqldb_user_login($_MYSQL_USERNAME, $_MYSQL_PASSWORD);
    mysqldb_connect_to_db($_MYSQL_DATABASE);
}

//close DB
function mysqldb_close()
{
    mysql_close();
}

//Add slashes to incoming data
function mysqldb_safeinput2($string)
{
    if (function_exists('mysql_real_escape_string')) {
        return mysql_real_escape_string($string);
    } elseif (function_exists('mysql_escape_string')) {
        return mysql_escape_string($string);
    }
    return addslashes($string);
}

function sqlsafe($value)
{
    return mysqldb_safeinput($value);
}

//Add slashes to incoming data
function mysqldb_safeinput($value)
{
    $magic_quotes_active = get_magic_quotes_gpc();
    $new_enough_php = function_exists("mysql_real_escape_string");
    // i.e PHP >= v4.3.0
    if ($new_enough_php) {
        //undo any magic quote effects so mysql_real_escape_string can do the work
        if ($magic_quotes_active) {
            $value = stripslashes($value);
        }
        $value = mysql_real_escape_string($value);
    } else { // before PHP v4.3.0
        // if magic quotes aren't already on this add slashes manually
        if (!$magic_quotes_active) {
            $value = addslashes($value);
        } //if magic quotes are avtive, then the slashes already exist
    }
    return $value;
}

//query
function mysqldb_query($query)
{
    return mysql_query($query);
}

//num rows
function mysqldb_num_rows($res)
{
    return mysql_num_rows($res);
}

//query result
function mysqldb_result($res, $row, $field)
{
    return mysql_result($res, $row, $field);
}

//query result with row
function mysqldb_query_result($query, $row, $field)
{
    $res = mysqldb_query($query);
    if ($res == NULL || mysqldb_num_rows($res) <= $row)
        return NULL;
    else
        return mysqldb_result($res, $row, $field);
}

//query full
function mysqldb_query_and_close($query)
{
    mysqldb_connect_db_full();
    $res = mysqldb_query($query);
    mysqldb_close();
    return $res;
}

//get fields names from table
function mysqldb_table_fields($table)
{
    $res = mysqldb_query("SHOW COLUMNS FROM $table");
    $arr = array();
    if (mysqldb_num_rows($res) > 0) {
        while ($row = mysql_fetch_assoc($res)) {
            $arr[] = $row['Field'];
        }
    }
    return $arr;
}

//print res
function mysqldb_results_to_string($res)
{
    $str = "<table border=\"1\" style=\"width:800px;\">";
    $rows = mysqldb_num_rows($res);
    $cols = mysql_num_fields($res);
    for ($i = 0; $i < $rows; $i++) {
        $str = $str . "<tr><td>ROW $i:</td>";
        for ($j = 0; $j < $cols; $j++) {
            $fname = mysql_field_name($res, $j);
            $val = mysqldb_result($res, $i, $fname);
            $str = $str . "<td>$fname = [$val]</td>";
        }
        $str = $str . "</tr>";
    }
    $str = $str . "</table>";
    return $str;
}

//print table
function mysqldb_table_to_string($table)
{
    $res = mysqldb_query("select * from $table");
    $str = "<table border=\"1\" style=\"border:2px solid;color:green;table-layout:auto;width:800px;\"><tr><td>TABLE '$table'</td></tr>";
    $rows = mysqldb_num_rows($res);
    $cols = mysql_num_fields($res);

    $farr = mysqldb_table_fields($table);
    $str = $str . "<tr><td>ROW #</td>";
    for ($i = 0; $i < sizeof($farr); $i++) {
        $str = $str . "<td>$farr[$i]</td>";
    }
    $str = $str . "</tr>";

    for ($i = 0; $i < $rows; $i++) {
        $str = $str . "<tr><td>$i</td>";
        for ($j = 0; $j < $cols; $j++) {
            $fname = mysql_field_name($res, $j);
            $val = mysqldb_result($res, $i, $fname);
            $str = $str . "<td>$val</td>";
        }
        $str = $str . "</tr>";
    }
    $str = $str . "</table>";
    return $str;
}

?>
