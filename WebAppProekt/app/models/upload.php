<?php
class Upload {
    function upload($POST, $FILES) {
        $DB = new DataBase();
        $_SESSION['error'] = ""; 

        $allowed = ["image/jpeg", "image/png", "image/gif"];

        if (isset($POST['title']) && isset($FILES['file'])) {

            if ($FILES['file']['name'] != "" && $FILES['file']['error'] == 0 && in_array($FILES['file']['type'], $allowed)) {

                $folder = "uploads/";
                if (!file_exists($folder)) {
                    mkdir($folder, 0777, true);
                }

                $destination = $folder . $FILES['file']['name'];

                if (move_uploaded_file($FILES['file']['tmp_name'], $destination)) {

                    $arr['title'] = $POST['title'];
                    $arr['description'] = $POST['description'];
                    $arr['image'] = $destination; 
                    $arr['url_address'] = get_random_string_max(60);
                    $arr['date'] = date("Y-m-d H:i:s");

                    $query = "INSERT INTO images (title, description, url_address, date, image) VALUES (:title, :description, :url_address, :date, :image)";
                    $data = $DB->write($query, $arr);

                    if ($data) {
                        header("Location: " . ROOT . "home");
                        die;
                    } else {
                        echo "Error inserting data into the database.";
                    }

                } else {
                    $_SESSION['error'] = "This file could not be uploaded.";
                }

            } else {
                $_SESSION['error'] = "Invalid file type. Only JPEG, PNG, and GIF are allowed.";
            }

        } else {
            echo "Please provide a title and select a valid file.";
        }
    }
}
