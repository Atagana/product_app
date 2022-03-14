<?php

$pdo = new PDO("mysql:host=localhost;port=3306;dbname=product_crud", 'root', '');
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);


$id = $_GET['id'] ?? null;

if (!$id) {
    header('Location: index.php');
    exit;
}

$statement = $pdo->prepare("SELECT * FROM products WHERE id = :id");
$statement->bindValue(':id', $id);
$statement->execute();
$result = $statement->fetch(PDO::FETCH_ASSOC);



$errors = [];

$title = $result['title'];
$description = $result['description'];
$price = $result['price'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
$title = $_POST['title'];
$description = $_POST['description'];
$price = $_POST['price'];

if (!$title) {
  $errors[] = 'Product title is required';
}

if (!$price) {
  $errors[] = 'Product price is required';
}

if (!is_dir('images')) {
  mkdir('images');
}

if (empty($errors)) {

  $image = $_FILES['image'] ?? null;
  $imagePath = $result['image'];

  if ($image && $image['tmp_name']) {

    if ($result['image']) {
        unlink($result['image']);
    }

    $same_image_folder = explode('/', $imagePath);
    
    $imagePath = 'images/'.$same_image_folder[1].'/'.$image['name'];

    // mkdir(dirname($imagePath));

    move_uploaded_file($image['tmp_name'], $imagePath);
  }
  
$statement = $pdo->prepare("UPDATE products SET title = :title, 
                            image = :image, description = :description,
                             price = :price WHERE id = :id");


$statement->bindValue(':title', $title);
$statement->bindValue(':image', $imagePath);
$statement->bindValue(':description', $description);
$statement->bindValue(':price', $price);
$statement->bindValue(':id', $id);
$statement->execute();
header('LOCATION: index.php');
}

}

function randomString($n)
{
  $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
  $str = '';
  for ($i=0; $i < $n; $i++) { 
    $index = rand(0, strlen($characters) -1);
    $str .=$characters[$index];
  }

  return $str;
}

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

<a href="index.php" class="btn btn-secondary">Home</a>
<h1>Update Product <b><?php echo $result['title'] ?></b></h1>

<?php if(!empty($errors)): ?>
      <div class="alert alert-danger">
      <?php foreach ($errors as $error): ?>
        <div><?php echo $error ?></div>
      <?php endforeach ?>
        </div>
<?php endif; ?>
<form action="" method="POST" enctype="multipart/form-data">

        <?php if($result['image']): ?>
            <img src="<?php echo $result['image'] ?>" class="update-img">
        <?php endif; ?>

  <div class="mb-3">
    <label>Products image</label>
    <br>
    <input type="file" name="image">
  </div>
  <div class="mb-3">
    <label>Products Title</label>
    <input type="text" class="form-control" name="title" value="<?php echo $title ?>">
  </div>
  <div class="mb-3">
    <label>Products Description</label>
    <textarea class="form-control" name="description" ><?php echo $description ?></textarea>
  </div>
  <div class="mb-3">
    <label>Products Price</label>
    <input type="number" step=".01" class="form-control" name="price" value="<?php echo $price ?>">
  </div>
  <button type="submit" class="btn btn-primary">Submit</button>
</form>
</body>
</html>