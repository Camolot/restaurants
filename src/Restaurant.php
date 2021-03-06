<?php

    class Restaurant
    {
        private $name;
        private $id;
        private $cuisine_id;
        private $description;
        private $address;

        function __construct($name, $id=null, $cuisine_id, $description, $address)
        {
            $this->name = $name;
            $this->id = $id;
            $this->cuisine_id = $cuisine_id;
            $this->description = $description;
            $this->address = $address;
        }

        function setName($new_name)
        {
            $this->name = (string) $new_name;
        }

        function getName()
        {
            return $this->name;
        }

        function getId()
        {
            return $this->id;
        }

        function setId($new_id)
        {
            $this->id = $new_id;
        }

        function getCuisineId()
        {
            return $this->cuisine_id;
        }

        function setDescription($new_description)
        {
            $this->description = (string) $new_description;
        }

        function getDescription()
        {
            return $this->description;
        }

        function setAddress($new_address)
        {
            $this->address = (string) $new_address;
        }

        function getAddress()
        {
            return $this->address;
        }

        function getReviews()
        {
            $reviews = array();
            $returned_reviews = $GLOBALS['DB']->query("SELECT * FROM reviews WHERE restaurant_id={$this->getId()};");
            foreach($returned_reviews as $review) {
                $username = $review['username'];
                $date = $review['date'];
                $rating = $review['rating'];
                $comment = $review['comment'];
                $restaurant_id = $review['restaurant_id'];
                $id = $review['id'];
                $new_review = new Review($username, $date, $rating, $comment, $restaurant_id, $id);
                array_push($reviews, $new_review);
            }
            return $reviews;
        }

        function save()
        {
            $GLOBALS['DB']->exec("INSERT INTO restaurants (name, cuisine_id, description, address) VALUES ('{$this->getName()}', {$this->getCuisineId()}, '{$this->getDescription()}', '{$this->getAddress()}');");
            $this->id = $GLOBALS['DB']->lastInsertId();
        }

        static function getAll()
        {
            $returned_restaurants = $GLOBALS['DB']->query("SELECT * FROM restaurants ORDER BY name;");
            $restaurants = array();
            foreach($returned_restaurants as $restaurant) {
                $name = $restaurant['name'];
                $id = $restaurant['id'];
                $cuisine_id = $restaurant['cuisine_id'];
                $description = $restaurant['description'];
                $address = $restaurant['address'];
                $new_restaurant = new Restaurant($name, $id, $cuisine_id, $description, $address);
                array_push($restaurants, $new_restaurant);
            }
            return $restaurants;
        }

        static function deleteAll()
        {
            $GLOBALS['DB']->exec("DELETE FROM restaurants;");
        }

        static function find($search_id)
        {
            $found_restaurant = NULL;
            $restaurants = Restaurant::getAll();
            foreach($restaurants as $restaurant) {
                $restaurant_id = $restaurant->getId();
                if ($restaurant_id == $search_id) {
                $found_restaurant = $restaurant;
                }
            }
            return $found_restaurant;
        }

        static function search($search_name)
        {
            $found_restaurants = array();
            $restaurants = Restaurant::getAll();
            foreach($restaurants as $restaurant) {
                $restaurant_name = $restaurant->getName();
                if($restaurant_name == $search_name) {
                    array_push($found_restaurants, $restaurant);
                }
            }
            return $found_restaurants;
        }
    }
?>
