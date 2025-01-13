<?php

namespace classes;

use PDO;

class Post
{

    public function createNew($post, $db): bool
    {
        //Sets $query for use in prepare statement with placeholders
        $query = 'INSERT INTO posts (title, image_path, user_id) 
        VALUES  (:title, :image_path, :user_id)';

        //$query is prepared and the resulting statement is placed in $statement
        $statement = $db->prepare($query);

        //Fill placeholders with data from $post
        $statement->bindValue(':title', $post['title']);
        $statement->bindValue(':image_path', $post['image_path']);
        $statement->bindValue(':user_id', $post['user_id']);

        //Executes the statement and returns it's boolean value
        return $statement->execute();
    }

    public function selectAll($db): array|bool
    {
        $query = 'SELECT posts.*, users.name, users.image_path as user_image_path FROM posts 
        LEFT JOIN users on users.id = posts.user_id';

        $statement = $db->prepare($query);

        $statement->execute();

        return $statement->fetchAll(PDO::FETCH_ASSOC);
    }

    public function plink($db, $amount): void
    {
        $query = "INSERT INTO posts (user_id, title, image_path) 
        VALUES (1, 'Plink', 'df98146ad8699dab05d34fb62ddba7524efe7dea.gif')";

        $statement = $db->prepare($query);

        for ($i = 0; $i < $amount; $i++) {
            $statement->execute();
        }
    }

}