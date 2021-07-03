                               
<?php

    //phpinfo();
  // set up MySQL connexion
    $theme=$_GET["theme"];
   $session=$_GET["session"];       
   $user=$_GET["user"];
   $debug=$_GET["debug"];                        
   include 'config.php';
                        

   $pattern = "/^[a-zA-Z0-9_\ ]*$/i";
   if(preg_match($pattern, $session)==0 ||preg_match($pattern, $user)==0)
   {
    die ("Invlid character !");
   }; 
   // connection
   $mysql = new MySQLi($sql_serveur, $sql_login, $sql_password, $sql_base);
    if (! $mysql)
    {
    die ("Couldn't connect to mySQL server");
    }
    $mysql->set_charset("utf8");
    if($theme=="true")
    {
        $sql = ' SELECT * FROM themes ORDER BY RAND() LIMIT 1';
        //echo $sql;
        $res = $mysql->query($sql);
        $found=false;
        while (NULL !== ($row = $res->fetch_array())) {
            $found=true;
            echo '{ "desc":"'. $row['Description'].'"}';
        }
    }else{

        $sql = 'SELECT * FROM Sessions where SessionID=\''.$session.'\' and User=\''.$user.'\'';
        //echo $sql;
        $res = $mysql->query($sql);
        $found=false;
        while (NULL !== ($row = $res->fetch_array())) {
            $found=true;
            echo $row['Numero'];
        }
        if(!$found)
        {

            $numPossible = array(1,2,3,4,5,6,7,8,9,10);
            $randNum='NA';
            $sql = 'SELECT * FROM Sessions where SessionID=\''.$session.'\'';
            $res = $mysql->query($sql);
            while (NULL !== ($row = $res->fetch_array())) {

                if (in_array( $row['Numero'], $numPossible)) 
                {
                    unset($numPossible[array_search( $row['Numero'],$numPossible)]);
                }
            }
            if($debug=="true")
            {
                echo var_dump($numPossible);
            }
            if(count($numPossible)>0)
            {
                $randNum = $numPossible[array_rand( $numPossible, 1)];
                if($debug=="true")
                {
                    echo var_dump($randNum);
                }
                $sql = 'INSERT INTO Sessions (SessionID, User, Numero)'.' VALUES(\''.$session.'\', \''.$user.'\', \''.$randNum.'\')';
                $mysql->query($sql);
            }
            echo  $randNum;
        }
    }
       
?>