<?php
  class Website extends CI_Controller{

  function __construct(){
    parent::__construct();
    $this->load->model('Website_model');
    $this->load->library('session');
  }

  function index()
  {
      //RETRIEVING DATA FROM FORM FOR POSTS
      $postName = $this->input->post('name');
      $description = $this->input->post('description');
      //$user = $this->input->post('user');
      //SINGLE COMMENT ID
      $pageNum = $this->input->get('page');
      //LOGIN
      if($this->input->server('REQUEST_METHOD') == 'POST'){
        $userName = $this->input->post('userName');
        $userPassword = $this->input->post('userPassword');
        $userLoggedIn = $this->Website_model->loginUser($userName, $userPassword);
        //echo json_encode($userLoggedIn);
      }
      //GET POST VALUES AND PASS IT TO MODEL METHOD
      if(isset($_SESSION['USER'])){
        $userName = $_SESSION['USER'];
        $userId = $this->Website_model->getUserId($userName);
        $this->Website_model->insertPosts($postName, $description, $userName, $userId);
      }


      $data['row_number'] = $this->Website_model->rowCount();
      $data['posts'] = $this->Website_model->paginate($pageNum);
      $this->load->view('main_page', $data);
  }

  function getAllPosts(){
      $pageNum = $this->input->get('page');
      $query = $this->Website_model->paginate($pageNum);

      foreach($query as $row){
          $data[] = array(
              "postName" => $row->postName,
              "postVotes" => $row->postVotes,
              "postUser" => $row->postUser,
              "postDate" => $row->postDate,
              "id" => $row->postId
          );
      }
      echo json_encode($data, JSON_PRETTY_PRINT);
  }

  function getPost($postId){
      $query = $this->Website_model->getSinglePost($postId);
      foreach($query as $row){
          $data = array(
            "postName" => $row->postName,
            "postVotes" => $row->postVotes,
            "postUser" => $row->postUser,
            "postDate" => $row->postDate,
            "id" => $row->postId
          );
      }
      echo json_encode($data, JSON_PRETTY_PRINT);
  }

  function loginFunction(){
        $userName = $this->input->get("username");
        $userPassword = $this->input->get("password");

        $userLoggedIn = $this->Website_model->loginUser($userName, $userPassword);
        if($userLoggedIn > 0){
            $this->session->set_userdata('USER', $userName);
            $userId = $this->Website_model->getUserId($_SESSION['USER']);

            $data = array("userStatus" => $userLoggedIn,
                          "userName" => $userName,
                          "userPassword" => $userPassword,
                          "userId" => $userId
            );
            echo json_encode($data, JSON_PRETTY_PRINT);
        }else{
            $data = array("userStatus" => '0');
            echo json_encode($data, JSON_PRETTY_PRINT);
        }
  }

  function userProfile(){
      $userSessionId = $this->Website_model->getUserId($_SESSION['USER']);
      $userScore = $this->Website_model->getUserScore($userSessionId);
      $userScoreForComments = $this->Website_model->getUserScoreComments($userSessionId);
      $userPosts = $this->Website_model->getUserPosts($userSessionId);
      $userComments = $this->Website_model->getUserComments($userSessionId);
      $userName = $_SESSION['USER'];

      $userScore = $userScore + $userScoreForComments;

      $data = array("userScore" => $userScore,
                    "userId" => $userSessionId,
                    "userName" => $userName,
                    "userPosts" => $userPosts,
                    "userComments" => $userComments);
      $this->load->view('user_profile.php', $data);
  }

  function logout(){
    if(isset($_SESSION['USER'])){
        unset($_SESSION['USER']);
    }
    redirect('Website');
  }

  function loadSinglePostsAndComments(){
      $postId = $this->uri->segment(3);

      $this->threading($this->Website_model->loadComments($postId));

      $data = array(
          'SINGLE_P' => $this->Website_model->getSinglePost($postId),
          'parent' => $this->parents,
          'controller' => $this,
          'commentCount' => $this->Website_model->getCommentCountForSinglePost($postId)
      );
      $this->load->view('single_posts.php', $data);
  }

  function upVote(){
      //$postId = $this->uri->segment(3);
      $postId = $this->input->get('postId');
      if(isset($_SESSION['USER'])){
          $userId = $this->Website_model->getUserId($_SESSION['USER']);
          $this->Website_model->upVote($postId, $userId);
          $totalVotes = $this->Website_model->countPostVotes($userId, $postId);
          $this->Website_model->updateVotes($postId, $totalVotes);
          $totalVotes = $this->Website_model->getPostVotes($postId);
          $userVoted = $this->Website_model->upVote($postId, $userId);
      }
  }

  function downVote(){
      $postId = $this->input->get('postId');
      if(isset($_SESSION['USER'])){
          $userId = $this->Website_model->getUserId($_SESSION['USER']);
          $this->Website_model->downVote($postId, $userId);
          $totalVotes = $this->Website_model->countPostVotes($userId, $postId);
          $this->Website_model->updateVotes($postId, $totalVotes);
          $totalVotes = $this->Website_model->getPostVotes($postId);
          $userVoted = $this->Website_model->upVote($postId, $userId);

          $data = array("totalVotes" => $totalVotes);

          if(isset($_SESSION['USER'])){
            $data = array(
                        "totalVotes" => $totalVotes,
                        "userStatus" => '1',
                        "userVoted" => $userVoted
            );
              echo json_encode($data);
          }else{
            $data = array(
                        "userStatus" => '0',
                        "postId" => $postId,
                        "userVoted" => $userVoted
            );
              echo json_encode($data);
          }
          //echo json_encode($data);
      }
  }
  function sortByDate(){
      $data['sortByDate'] = $this->Website_model->sortByDate();
      $data['row_number'] = $this->Website_model->rowCount();
      $this->load->view('sortbydate_page', $data);
  }
  function register(){
      $username = $this->input->post('username');
      $password1 = $this->input->post('password1');
      $password2 = $this->input->post('password2');
      if(isset($username) && isset($password1) && ($password2)){
          $this->Website_model->registerUser($username, $password1, $password2);
      }
      $this->load->view('register');
  }

  function getSinglePost(){
      // ACCESSING THE ID OF SINGLE COMMENT
      $commentId = $this->input->post('comment_id');
      $username = $this->input->post('childUser');
      $actualComment = $this->input->post('childComment');
      $currentPostId = $this->uri->segment(3);
      //GETTING THE SESSION WHEN SELECTING A POST
      $this->session->set_flashdata('postId', $currentPostId);
      //FOR THREADING
      $this->saveChildComment($commentId, $username, $actualComment, $currentPostId);
      $this->loadSinglePostsAndComments();
  }

  // THREADING
  function saveChildComment($id, $user, $comment, $currentPostId){
    if(isset($_SESSION['USER'])){
      $userId = $this->Website_model->getUserId($_SESSION['USER']);
      $this->Website_model->saveChildComment($id, $user, $comment, $currentPostId, $userId);
    }

  }

  // INSERT COMMENT INTO A DATABSE
  function saveComment(){
      $postId = $this->uri->segment(3);
      $userId = $this->Website_model->getUserId($_SESSION['USER']);
      $username = $this->input->post('username');
      $comment = $this->input->post('comment');
      $this->Website_model->saveComment($username, $comment, $postId, $userId);


      $this->loadSinglePostsAndComments();

      //REDIRECTING TO ANOTHER CONTROLLER METHOD
      redirect("Website/getSinglePost/$postId");
  }

  function threading($comments){
    $this->parents = array();
    $this->children = array();

    foreach ($comments as $comment)
    {
            if ($comment->parentId == 0)
            {
                $this->parents[$comment->commentId][] = $comment;
            }
            else
            {
                $this->children[$comment->parentId][] = $comment;
            }
    }
  }

  function print_parent($comment, $depth = 0){
      foreach($comment as $com){
      $this->print_comments($com, $depth);
          if(isset($this->children[$com->commentId]))
          {
              $this->print_parent($this->children[$com->commentId], $depth + 1);
          }
      }
  }

  function print_comments($comment, $depth)
  {
  $spacing = $depth;
  $spacing = $spacing * 45;

    if(isset($_SESSION['USER'])){
      $userName = $_SESSION['USER'];
        //echo "<span style='padding-left:".$spacing."px'> ";
        echo "<form method=POST id='abc' style='margin-left:".$spacing."px; border: solid 1px #AAA; padding:10px; border-radius: 10px 10px 10px 10px; width:50%;'>";
        echo "<strong style='color:#0081c1; font-family:Arial; font-size:18px;'>";
        echo $comment->commentUser;
        echo "</strong> <br>";
        echo "\n";
        echo "<span style='font-family: Arial; font-size:16px;'>".$comment->commentText."</span>";
        echo "\n";
        //echo "<a href='" .site_url('Website/upVoteComment/').$comment->commentPostId."'>
        //      <span class='glyphicon glyphicon-arrow-up'></span></a>";
        echo "<a style='text-decoration:none;' href='" .site_url('Website/upVoteComment/').$comment->commentId."'>
              <span class='glyphicon glyphicon-arrow-up'></span></a>";

        echo "<a style='text-decoration:none; color:orange;' href='" .site_url('Website/downVoteComment/').$comment->commentId."'>
              <span class='glyphicon glyphicon-arrow-down'></span></a>";

        echo "<input type='hidden' name='commentId' value='.$comment->commentId.' />";
        echo "\n";
        echo "<br>";
        echo "Votes : ".$comment->commentScore;
        echo "<Br>";
        //VOTE
        echo    "<button class='btn btn-primary' type='submit' name='comment_id' value='$comment->commentId'> Reply </button>";
        echo "\n";
        //BOTTOM LINE
        echo    "<input style='width:100px' type='hidden' name='childUser' value='$userName' placeholder='Username1'/>";
        //
        echo "\n";
        echo    "<input type='text' name='childComment' placeholder='Reply to comment'/>";
        echo    "<input type='hidden' name='currentPostId' value='$comment->commentPostId'/>";
        echo "</form>";
        echo "<Br>";
    } else{
      echo "<form method=POST id='abc' style='margin-left:".$spacing."px; border: solid 1px #AAA; padding:10px; border-radius: 10px 10px 10px 10px; width:50%;'>";
      echo "<strong style='color:#0081c1; font-family:Arial; font-size:18px;'>";
      echo $comment->commentUser;
      echo "</strong> <br>";
      echo "\n";
      echo "<span style='font-family: Arial; font-size:16px;'>".$comment->commentText."</span>";
      echo "\n";
      echo "\n";
      echo "<br>";
      echo "Votes : ".$comment->commentScore;
      echo "<Br>";
      //VOTE
      echo "\n";
      //BOTTOM LINE
      //
      echo "</form>";
      echo "<Br>";

    }
  }

  function upVoteComment(){
      $commentId = $this->uri->segment('3');
      $postId = $this->session->flashdata('postId');
      $userId = $this->Website_model->getUserId($_SESSION['USER']);
      $this->Website_model->upVoteComment($commentId, $userId);
      $totalVotes = $this->Website_model->countCommentVotes($userId, $commentId);
      $this->Website_model->updateCommentVotes($commentId, $totalVotes);

      redirect("Website/getSinglePost/$postId");
  }

  function downVoteComment(){
      $commentId = $this->uri->segment('3');
      $postId = $this->session->flashdata('postId');
      $userId = $this->Website_model->getUserId($_SESSION['USER']);
      $this->Website_model->downVoteComment($commentId, $userId);
      $totalVotes = $this->Website_model->countCommentVotes($userId, $commentId);
      $this->Website_model->updateCommentVotes($commentId, $totalVotes);
      redirect("Website/getSinglePost/$postId");
  }


  }

?>
