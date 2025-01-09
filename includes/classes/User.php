<?php

namespace classes;

use PDO;

class User
{

    //Adds new user to database
    public function createNew($user, $db): bool
    {
        //Sets $query for use in prepare statement with placeholders
        $query = 'INSERT INTO users (name, password, email, image_path) 
        VALUES  (:name, :password, :email, :image_path)';

        //$query is prepared and the resulting statement is placed in $statement
        $statement = $db->prepare($query);

        //Fill placeholders with data from $user
        $statement->bindValue(':name', $user['name']);
        $statement->bindValue(':email', $user['email']);
        $statement->bindValue(':password', $user['password']);
        $statement->bindValue(':image_path', $user['image_path']);

        //Executes the statement and returns it's boolean value
        return $statement->execute();

    }

    public function selectByEmail($db, $email):array|bool {

        $query = 'SELECT * FROM users WHERE email = :email';

        $statement = $db->prepare($query);

        $statement->bindValue(':email', $email);

        $statement->execute();

        return $statement->fetch(PDO::FETCH_ASSOC);

    }

}