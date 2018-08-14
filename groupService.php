<?php
include 'DBconfig.php';  // Db config file
require 'Slim/Slim.php';
\Slim\Slim::registerAutoloader();

$app = new \Slim\Slim();

// API group
$app->group('/api', function () use ($app) {

    // shop group
    $app->group('/shop', function () use ($app) {
        /**
         * GET    - /api/shop/autoshop/products : get all products
         * GET    - /api/shop/autoshop/products/:id : get single product
         * GET    - /api/shop/autoshop/products?pageNo=1&rowsPerPage=5 : pagination for products
         * DELETE - /api/shop/autoshop/products/:id : delete single product
         * PUT    - /api/shop/autoshop/product : update single product
         * POST   - /api/shop/autoshop/product : New product
         */
        // shop type group
        $app->group('/autoshop', function () use ($app) {
            // Get products
            $app->get('/products(/:id)', function ($id = -1) {
                global $app;
                $rowsPerpage = $app->request()->params("rowsPerPage");
                $pageNo = $app->request()->params("pageNo");
                $sql = "SELECT * FROM `products` WHERE  id = :id OR :id = -1 ";

                ////////// apply pagination ///////////////////
                if (isset($rowsPerpage) && isset($pageNo)) {
                    $offset = ($pageNo - 1) * $rowsPerpage;
                    $sql .= 'LIMIT ' . $offset . ',' . $rowsPerpage;
                }
                ////------- pagination -------------------///////////

                try {
                    $db = getDB();
                    $stmt = $db->prepare($sql);
                    $stmt->execute(array(':id' => $id));
                    $products = $stmt->fetchAll(PDO::FETCH_OBJ);
                    $db = null;
                    echo json_encode($products);
                } catch (PDOException $e) {
                    echo "Error !" . $e;
                }
            });

            // Update product
            $app->put('/product', function () {
                global $app;
                $product = json_decode($app->request()->getBody());

                $query = "UPDATE `products` SET `name` = :name, `orignal_price` = :orignal_price,`priceA` = :priceA, 
                          `priceB` = :priceB,`priceC` = :priceC  WHERE `id` = :id ";
                try {
                    $db = getDB();
                    $stmt = $db->prepare($query);

                    $stmt->bindParam("id", $product->id, PDO::PARAM_INT);
                    $stmt->bindParam("name", $product->name, PDO::PARAM_STR);
                    $stmt->bindParam("orignal_price", $product->orignalPrice, PDO::PARAM_INT);
                    $stmt->bindParam("priceA", $product->priceA, PDO::PARAM_INT);
                    $stmt->bindParam("priceB", $product->priceB, PDO::PARAM_INT);
                    $stmt->bindParam("priceC", $product->priceC, PDO::PARAM_INT);

                    $stmt->execute();
                    $db = null;
                } catch (PDOException $e) {
                    echo $e;
                }
            });
            // New product
            $app->post('/product', function () {
                global $app;
                $product = json_decode($app->request()->getBody());

                $query = "INSERT INTO products (name,orignal_price,priceA,priceB,priceC) 
                                   VALUES (:name,:orignal_price,:priceA,:priceB,:priceC)";
                try {
                    $db = getDB();
                    $stmt = $db->prepare($query);

                    $stmt->bindParam("name", $product->name, PDO::PARAM_STR);
                    $stmt->bindParam("orignal_price", $product->orignalPrice, PDO::PARAM_INT);
                    $stmt->bindParam("priceA", $product->priceA, PDO::PARAM_INT);
                    $stmt->bindParam("priceB", $product->priceB, PDO::PARAM_INT);
                    $stmt->bindParam("priceC", $product->priceC, PDO::PARAM_INT);

                    $stmt->execute();
                    $db = null;
                } catch (PDOException $e) {
                    echo $e;
                }
            });

            // Delete product with ID
            $app->delete('/products/:id', function ($id) {
                $query = "DELETE FROM products WHERE id = :id";
                try {
                    $db = getDB();
                    $stmt = $db->prepare($query);
                    $stmt->bindParam("id", $id, PDO::PARAM_INT);
                    $stmt->execute();
                    $db = null;
                } catch (PDOException $e) {
                    echo $e;
                }
            });

            // Get Category
            $app->get('/categories(/:id)', function ($id = -1) {
                global $app;
                $rowsPerpage = $app->request()->params("rowsPerPage");
                $pageNo = $app->request()->params("pageNo");
                $sql = "SELECT * FROM `category` WHERE  id = :id OR :id = -1 ";

                ////////// apply pagination ///////////////////
                if (isset($rowsPerpage) && isset($pageNo)) {
                    $offset = ($pageNo - 1) * $rowsPerpage;
                    $sql .= 'LIMIT ' . $offset . ',' . $rowsPerpage;
                }
                ////------- pagination -------------------///////////

                try {
                    $db = getDB();
                    $stmt = $db->prepare($sql);
                    $stmt->execute(array(':id' => $id));
                    $products = $stmt->fetchAll(PDO::FETCH_OBJ);
                    $db = null;
                    echo json_encode($products);
                } catch (PDOException $e) {
                    echo "Error !" . $e;
                }
            });
            // New Category
            $app->post('/category', function () {
                global $app;
                $product = json_decode($app->request()->getBody());

                $query = "INSERT INTO category (name,isActive)  VALUES (:name,TRUE)";
                try {
                    $db = getDB();
                    $stmt = $db->prepare($query);
                    $stmt->bindParam("name", $product->name, PDO::PARAM_STR);
                    $stmt->execute();
                    $db = null;
                } catch (PDOException $e) {
                    echo $e;
                }
            });

            // Update category
            $app->put('/category', function () {
                global $app;
                $product = json_decode($app->request()->getBody());

                $query = "UPDATE `category` SET `name` = :name, `isActive` = :isActive  WHERE `id` = :id ";
                try {
                    $db = getDB();
                    $stmt = $db->prepare($query);

                    $stmt->bindParam("id", $product->id, PDO::PARAM_INT);
                    $stmt->bindParam("name", $product->name, PDO::PARAM_STR);
                    $stmt->bindParam("isActive", $product->isActive, PDO::PARAM_BOOL);


                    $stmt->execute();
                    $db = null;
                } catch (PDOException $e) {
                    echo $e;
                }
            });

            // Delete category with ID
            $app->delete('/categories/:id', function ($id) {
                $query = "DELETE FROM category WHERE id = :id";
                try {
                    $db = getDB();
                    $stmt = $db->prepare($query);
                    $stmt->bindParam("id", $id, PDO::PARAM_INT);
                    $stmt->execute();
                    $db = null;
                } catch (PDOException $e) {
                    echo $e;
                }
            });

            // Get Users
            $app->get('/users(/:id)', function ($id = -1) {
                global $app;
                $rowsPerpage = $app->request()->params("rowsPerPage");
                $pageNo = $app->request()->params("pageNo");
                $sql = "SELECT * FROM `user` WHERE  id = :id OR :id = -1 ";

                ////////// apply pagination ///////////////////
                if (isset($rowsPerpage) && isset($pageNo)) {
                    $offset = ($pageNo - 1) * $rowsPerpage;
                    $sql .= 'LIMIT ' . $offset . ',' . $rowsPerpage;
                }
                ////------- pagination -------------------///////////

                try {
                    $db = getDB();
                    $stmt = $db->prepare($sql);
                    $stmt->execute(array(':id' => $id));
                    $products = $stmt->fetchAll(PDO::FETCH_OBJ);
                    $db = null;
                    echo json_encode($products);
                } catch (PDOException $e) {
                    echo "Error !" . $e;
                }
            });

            // Get Orders
            $app->get('/order(/:id)', function ($id = -1) {
                global $app;
                $rowsPerpage = $app->request()->params("rowsPerPage");
                $pageNo = $app->request()->params("pageNo");
                $sql = "SELECT * FROM `purchase_order` WHERE  id = :id OR :id = -1 ";

                ////////// apply pagination ///////////////////
                if (isset($rowsPerpage) && isset($pageNo)) {
                    $offset = ($pageNo - 1) * $rowsPerpage;
                    $sql .= 'LIMIT ' . $offset . ',' . $rowsPerpage;
                }
                ////------- pagination -------------------///////////

                try {
                    $db = getDB();
                    $stmt = $db->prepare($sql);
                    $stmt->execute(array(':id' => $id));
                    $products = $stmt->fetchAll(PDO::FETCH_OBJ);
                    $db = null;
                    echo json_encode($products);
                } catch (PDOException $e) {
                    echo "Error !" . $e;
                }
            });
            // New Order
            $app->post('/order', function () {
                global $app;
                $product = json_decode($app->request()->getBody());

                $query = "INSERT INTO category (name,isActive)  VALUES (:name,TRUE)";
                try {
                    $db = getDB();
                    $stmt = $db->prepare($query);
                    $stmt->bindParam("name", $product->name, PDO::PARAM_STR);
                    $stmt->execute();
                    $db = null;
                } catch (PDOException $e) {
                    echo $e;
                }
            });

        });

    });

});

$app->run();