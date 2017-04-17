
<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <!-- <link rel="stylesheet" href="bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="bootstrap/css/bootstrap-theme.min.css"> -->

     <link rel="stylesheet" href="<?php echo base_url('css/styles.css')?>" />
     <link href="https://fonts.googleapis.com/css?family=Dosis" rel="stylesheet">
     <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">

</head>


<body>
    <div id="myContainer">

        <!-- NAVIGATION -->
          <nav style="margin-top:10px;">
                <ul>
                      <li> <a style="text-decoration:none;" href="<?php echo site_url('Website') ?>" id="#home">Home</a></li>
                      <li><a style="text-decoration:none;" id="addpostBtn">Add Post</a></li>
                      <li><a style="text-decoration:none;" id="sort" href="<?php echo site_url('Website')?>/sortByDate">Sort by Date</a></li>
                </ul>
          </nav>

          <div id="addpost">
            <span> Generate your post </span>
                <form method=POST id="addpostform">
                      <input type="text" name="name" placeholder="Subject name" /> </br>
                      <input type="text" name="description" placeholder="Description" /> </br>
                      <input type="text" name="user" placeholder="User" /> </br>
                      <input type="submit" id="send" value="Submit"/>
                </form>
          </div>

          <?php foreach($sortByDate as $post): ?>
              <div id="posts">
                  <a href="<?php echo site_url('Website')?>/getSinglePost/<?php echo $post->postId; ?>"> <div id="single-post"><?php echo $post->postName; ?></div> </a>
                  <!-- <a><div data-id="<?php echo $post->postId ?>" class="upvote">Upvote</div></a> -->
                  <div id="upvote"> <a href="<?php echo site_url('Website')?>/upVote/<?php echo $post->postId ?>"><span class="glyphicon glyphicon-arrow-up"></span> </a> </div>
                  <div id="downvote"> <a href="<?php echo site_url('Website')?>/downVote/<?php echo $post->postId ?>"><span class="glyphicon glyphicon-arrow-down"></span> </a> </div>

                  <div style="clear:both; padding:7px;">
                      <div id="user"><strong>Submitted by : </strong> <?php echo $post->postUser; ?></div>
                      <div id="commentNumber"><span><strong><?php echo $post->postVotes; ?> </span></strong>Votes</div>
                      <div id="votes">Submission date : <strong> <?php echo $post->postDate; ?></strong></div>
                  </div>

              </div>
          <? endforeach; ?>

          <div id="pagination">
                    <?php
                        // 'POSTS' row count
                        $total = $row_number;
                        // LIMIT POSTS PER PAGE
                        $limit = 5;
                        // NUMBER OF PAGES (ceil rounds up the number)
                        $pages = ceil($total / $limit);
                        //START FROM
                        if($pages == 0){ $pages = 1; }

                        echo "<a href='/CodeIgniter-3.1.0/index.php/Website?page=".(1)."'> << </a>";

                        for ($i=0; $i < $pages; $i++) {
                            echo "<a class='status' href='/CodeIgniter-3.1.0/index.php/Website?page=".($i+1)."'>".($i+1)."</a>";
                        }
                        echo "<a href='/CodeIgniter-3.1.0/index.php/Website?page=".$pages."'> >> </a>";

                        echo "<br>";
                     ?>
           </div>



    </div>

    <!-- SCRIPT LINKS -->
    <script src="<?php echo base_url('js/jquery-3.1.1.min.js')?>"></script>

    <script>

    $(document).ready(function(){
      $( "#addpostBtn" ).click(function() {
        $( "#addpost" ).fadeToggle( "fast");
        $( "#addpost" ).css({"visibility":"visible",
                            "display":"block"});

      });


    });

    </script>


</body>

</html>
