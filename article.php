
<?php 

include './includes/config.php';

?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Блог IT_Минималиста!</title>

  <!-- Bootstrap Grid -->
  <link rel="stylesheet" type="text/css" href="/media/assets/bootstrap-grid-only/css/grid12.css">

  <!-- Google Fonts -->
  <link href="https://fonts.googleapis.com/css?family=Roboto:300,400,500,700" rel="stylesheet">

  <!-- Custom -->
  <link rel="stylesheet" type="text/css" href="/media/css/style.css">
</head>
<body>

  <div id="wrapper">

   <?php include "includes/header.php"; ?>

   <?php 
$article = mysqli_query($connect, "SELECT * FROM `articles` WHERE `id` = " . (int)$_GET['id']);

if(mysqli_num_rows($article) <=0 ) 
{
     ?>
         <div id="content">
      <div class="container">
        <div class="row">
          <section class="content__left col-md-8">
            <div class="block">
              <h3>Статья не найдена</h3>
              <div class="block__content">
                <div class="full-text">
Запрашиваемая статья не существует
                </div>
              </div>
            </div>
          </section>

          <section class="content__right col-md-4">
            <?php include "includes/sidebar.php"; ?>
           </section>
        </div>
      </div>
    </div>
     <?php
    } else
    {
      $art = mysqli_fetch_assoc($article);
      mysqli_query($connect, "UPDATE `articles` SET `views` = `views` + 1 WHERE `id` = " . (int)$art['id']);
      ?>
         <div id="content">
      <div class="container">
        <div class="row">
          <section class="content__left col-md-8">
            <div class="block">
              <a><?php echo $art['views']; ?> просмотров</a>
              <h3><?php echo $art['title']; ?></h3>
              <div class="block__content">
                <img src="./static/images/<?php echo $art['image']; ?>">
                <div class="full-text"><?php echo $art['text']; ?></div>
              </div>
            </div>
         



                <div class="block">
                  <a href="#comment-add-form">Добавить комментарий</a>
              <h3>Комментарии</h3>
              <div class="block__content">
                <div class="articles articles__vertical">
<?php 
$comments = mysqli_query($connect, "SELECT * FROM `comments` WHERE `articles_id` = " . (int) $art['id'] . " ORDER BY `id` DESC");

if(mysqli_num_rows($comments) <=0 )
{
  echo "Нет комментариев!";
}

  while( $comment = mysqli_fetch_assoc($comments))
  {
   ?>
     <article class="article">
            <div class="article__image" style="background-image: url(https://www.gravatar.com/avatar/<?php echo md5 ($comment['email']); ?>);"></div>
                <div class="article__info">
                  <a href="/article.php?id=<?php echo $comment['articles_id'] ?>"><?php echo $comment['author']; ?></a>
                  <div class="article__info__meta"></div>
                  <div class="article__info__preview"><?php echo $comment['text']; ?></div>
                </div>
              </article>
           <?php   
          }
          ?>
            </div>
          </div>
        </div>




  <div id="comment-add-form" class="block">
  <h3>Добавить комментарий</h3>
  <div class="block__content">
  <form class="form" method="POST" action="/article.php?id=<?php echo $art['id']; ?>#comment-add-form">
  <?php 
  if (isset($_POST['do_post']) ) 
  {
     $errors = array();

     if ($_POST['name'] == '') 
     {
       $errors[] = 'Введите имя';
     }
      if ($_POST['nickname'] == '') 
     {
       $errors[] = 'Введите Ваш Никнейм';
     }
      if ($_POST['email'] == '') 
     {
       $errors[] = 'Введите Email';
     }
      if ($_POST['text'] == '') 
     {
       $errors[] = 'Введите комментарий';
     }
     if (empty($errors)) 
     {

    // добавить комментарий
    $author = $_POST['name'];
      mysqli_query($connect, "INSERT INTO `comments` (`id`,`author`, `text`, `pubdate`, `articles_id`, `nickname`, `email`) VALUES (NULL, '$author', '".$_POST['text']."', NOW(), '".$art['id']."',  '".$_POST['nickname']."', '".$_POST['email']."')");

      echo '<span style="color: green; font-weight: bold;">Комментарий успешно добавлен</span>';
     }else
     {
      // вывести ошибку
      echo '<span style="color: red; font-weight: bold;">' . $errors['0'] .'</span>';
     }
  }
   ?>



                  <div class="form_group">
                    <div class="row">
                      <div class="col-md-4">
                        <input type="text" name="name" class="form_control" placeholder="Имя" value="<?php echo $_POST['name']; ?>">
                      </div>
                      <div class="col-md-4">
                        <input type="text" name="nickname" class="form_control" placeholder="Никнейм" value="<?php echo $_POST['nickname']; ?>">
                      </div>
                      <div class="col-md-4">
                        <input type="text" name="email" class="form_control" placeholder="Email" value="<?php echo $_POST['email']; ?>">
                      </div>
                    </div>
                  </div>
                  <br>
                  <div class="form_group">
                    <textarea class="form_control" name="text" placeholder="Текст комментария ..."><?php echo $_POST['text']; ?></textarea>
                  </div>
                  <br>
                   <div class="form_group">
                    <input type="submit" name="do post" value="Добавить комментарий" class="form_control">
                  </div>
                </form>
              </div>
            </div>

          </section>
          <section class="content__right col-md-4">
            <?php include "includes/sidebar.php"; ?>

           </section>
        </div>
      </div>
    </div>
      <?php
     }
    ?>



<?php  include "includes/footer.php";  ?>

  </div>

</body>
</html>