<?php

$pdo = new PDO("mysql:host=localhost;port=3306;dbname=product_crud", 'root', '');
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

$statement = $pdo->prepare("SELECT * FROM products ORDER BY create_date DESC");
$statement->execute();
$results = $statement->fetchAll(PDO::FETCH_ASSOC);

// echo '<pre>';
// var_dump($results);
// echo '</pre>';

?>


<!doctype html>
<html lang="en">
<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css"
          integrity="sha384-JcKb8q3iqJ61gNV9KGb8thSsNjpSL0n8PARn9HuZOnIxN0hoP+VmmDGMN5t9UJ0Z" crossorigin="anonymous">
    <link rel="stylesheet" href="app.css">
    <title>Products CRUD</title>
</head>
<body>
<h1>Products CRUD</h1>

<p>
    <a href="create.php" type="button" class="btn btn-sm btn-success">Add Product</a>
</p>
<table class="table">
    <thead>
    <tr>
        <th scope="col">Id</th>
        <th scope="col">Image</th>
        <th scope="col">Title</th>
        <th scope="col">Price</th>
        <th scope="col">Create Date</th>
        <th scope="col">Actions</th>
    </tr>
    </thead>
    <tbody>
        <?php foreach ($results as $r => $result) : ?>
            <tr>
            <th scope="row"><?php echo $result['id'] ?></th>
            <td>
                <img src="<?php echo $result['image'] ?>" class="thumb">
            </td>
            <td><?php echo $result ['title'] ?></td>
            <td><?php echo $result ['price'] ?></td>
            <td><?php echo $result ['create_date'] ?></td>

            <td>
                <a href="update.php?id=<?php echo $result['id'] ?>" class="btn btn-sm btn-outline-primary">Edit</a>
                <form style="display: inline-block;" method="post" action="delete.php">
                    <input type="hidden" name="id" value="<?php echo $result['id'] ?>">
                <button type="submit" class="btn btn-sm btn-outline-danger">Delete</button>
                </form>
            </td>
        </tr>
        <?php endforeach ?>
            
    </tbody>
</table>

</body>
</html>