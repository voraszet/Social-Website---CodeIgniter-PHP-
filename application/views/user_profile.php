<!DOCTYPE html>
<html>
<head>
  <link rel="stylesheet" href="<?php echo base_url('css/styles.css')?>" />
  <link href="https://fonts.googleapis.com/css?family=Dosis" rel="stylesheet">
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
</head>

<body>

  <nav style="margin-top:30px;">
        <ul>
              <li><a style="text-decoration:none;" href="<?php echo site_url('Website')?>">Home</a></li>
        </ul>
  </nav>

  <div id="container">
      <div id="commentPage">
              <span id="commentTitle"> User Score : <?php echo $userScore; ?> </span><br>
              <span id="commentTitle"> User Name : <?php echo $userName; ?> </span><br><br>

              <span id="commentTitle"> User posts </span>
              <div style="font-size:15px; margin-left:20px;">
                  <?php foreach($userPosts as $p){
                      echo "<br>";
                      echo "Post Name : ";
                      echo $p->postName."<br>";
                      echo "Post Description : ";
                      echo $p->postDescription."<br><br>";
                  }  ?>
              </div>
              <?php $count = 0; ?>
              <span id="commentTitle"> User comments </span>
              <div style="font-size:15px; margin-left:20px;">
                  <?php foreach($userComments as $comments){
                      echo "<br>";
                      $count = $count + 1;
                      echo $count;
                      echo " : ";
                      echo $comments->commentText."<br>";
                  } ?>
              </div>
      </div>






  <!-- END OF DIV -->
  </div>




</body>


</html>
