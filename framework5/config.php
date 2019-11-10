<?php

return array(
    "site" => array(
        "root" => "http://localhost:8088/cursoServidor/framework5",
    ),
    "DB" => array(
        "CONNECTION" => "mysql",
        //El host es db porque estamos utilizando docker (mirar docker-compose.yml)
        "HOST" => "localhost",
        "PORT" => "3306",
        "NAME" => "nba",
        "USERNAME" => "root",
        "PASSWORD" => "",
    )
);