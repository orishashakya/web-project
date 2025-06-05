<?php
 function getDatabaseConnection(){
                $servername = "localhost";
                $username = "root";
                $password = "" ;
                $database = "grocerystoredb";

                //create connection
                $connection = new mysqli($servername,$username,$password,$database);
                if($connection->connect_error){
                    die("Erroe failed to connect to MYSQL:" . $connection->connect_error);
 }
                return $connection;
 }
 ?>