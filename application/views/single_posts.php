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
                    <?php
                    $ID = 0;

                    if(isset($SINGLE_P)){
                        foreach ($SINGLE_P as $sp){
                                echo "<div id='postName'>".$sp->postName."</div>";
                                echo "<span id='date'> Date : ".$sp->postDate."</span>";
                                echo "<div id='userName'>Submitted by <strong>".$sp->postUser."</strong></div><br>";
                                echo "<span id='commentVotes'><strong>".$sp->postVotes."</strong> Post votes <strong style='margin-left:20px'>".$commentCount."</strong> Comments</span>";
                                echo "<br><br>";
                                echo "<span id='descriptionTitle'> Description </span>";
                                echo "<div id='postDescription'>".$sp->postDescription."</div>";
                                $ID = $sp->postId;
                        }
                    }
                    echo "<br>";
                    ?>

                    <?php //USER COMMENT FORM
                    if(isset($_SESSION['USER'])){ ?>
                    <div id="form">
                          <form method=POST id="addComments" action="<?php echo site_url('Website')?>/saveComment/<?php echo $ID ?>">
                                <span style="font-family: 'Dosis', sans-serif; font-size:25px;"> Add comment </span><Br>
                                <input type="hidden" name="username" value="<?php echo $_SESSION['USER']; ?>" placeholder="Username" /></br>
                                <input type="text" name="comment" placeholder="Comment" /></br>
                                <input type="submit" class="btn btn-primary" style="margin-left:3px; cursor:pointer;" value="Submit">
                          </form>
                    </div><?php } ?>

                    <span id="commentTitle"> Comments </span>
                    <div id="comments">
                            <?php

                            if(!empty($parent)){
                                foreach($parent as $p){
                                    $controller->print_parent($p);
                                }
                            }
                            if(empty($parent)){
                                echo "<span style='font-family: Arial; font-size:16px;'> No comments </span>";
                            }
                            ?>
                    </div>

        </div>
  <!-- END OF DIV -->
  </div>




</body>


</html>
