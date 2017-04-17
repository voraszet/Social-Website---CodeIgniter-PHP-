<?php

class Website_model extends CI_Model{

  function __construct(){
    parent::__construct();

  }

  function insertPosts($n, $d, $u, $userId){
      $date = date("Y-m-d");
      $data = array(
              'postName' => $n,
              'postDescription' => $d,
              'postVotes' => '0',
              'postUser' => $u,
              'postDate' => $date,
              'userId' => $userId
      );

      if(!empty($n) && !empty($d)){
        $this->db->insert('posts', $data);
      }

  }

  function getSinglePost($postId){
      return $this->db->get_where('posts', array('postId' => $postId))->result();
  }

  //SHOW COMMENT COUNT ON SINGLE_POSTS
  function getCommentCountForSinglePost($postId){
      $q = $this->db->get_where('comments', array('commentPostId' => $postId));
      return $q->num_rows();
  }

  function upVote($postId, $userId){
      $data = array(
              'postId' => $postId,
              'userId' => $userId,
              'votesScore' => '2'
      );
      $query = $this->db->get_where('php_votes', array('userId' => $userId));
      $query2 = $this->db->get_where('php_votes', array('postId' => $postId));
      $uId = $query->row('userId');
      $pId = $query2->row('postId');

      $query3 = $this->db->get_where('php_votes', array('userId' => $uId, 'postId' => $pId));
      $user = $query3->row('userId');
      $post = $query3->row('postId');
      // IF USER HAS NOT VOTED, INSERT VOTE
      if(!($user == $userId) && !($post == $postId)){
          $this->db->insert('php_votes', $data);
      }
      $count = $query3->num_rows();
      if($count > 0){
        $this->db->where('userId', $user)->where('postId', $postId);
        $this->db->update('php_votes', array('votesScore' => '2'));
      }
      return $query3->row('votesScore');
  }

  function downVote($postId, $userId){
      $data = array(
              'postId' => $postId,
              'userId' => $userId,
              'votesScore' => '1'
      );
      $query = $this->db->get_where('php_votes', array('userId' => $userId));
      $query2 = $this->db->get_where('php_votes', array('postId' => $postId));
      $uId = $query->row('userId');
      $pId = $query2->row('postId');

      $query3 = $this->db->get_where('php_votes', array('userId' => $uId, 'postId' => $pId));
      $user = $query3->row('userId');
      $post = $query3->row('postId');

      if(!($user == $userId) && !($post == $postId)){
          $this->db->insert('php_votes', $data);
      }
      $count = $query3->num_rows();
      if($count > 0){
        $this->db->where('userId', $user)->where('postId', $postId);
        $this->db->update('php_votes', array('votesScore' => '1'));
      }
  }

  function countPostVotes($userId, $postId){
      $upVotesQuery = $this->db->where('postId', $postId)->where('votesScore', '2')->get('php_votes');
      $upVotes = $upVotesQuery->num_rows();
      $downVotesQuery = $this->db->where('postId', $postId)->where('votesScore', '1')->get('php_votes');
      $downVotes = $downVotesQuery->num_rows();
      return $totalVotes = $upVotes - $downVotes;
  }

  function getUserScore($userId){
      $query = $this->db->get_where('posts', array('userId' => $userId))->result();
      $userScore = 0;
      foreach($query as $q){
          $userScore = $userScore + $q->postVotes;
      }
      return $userScore;
  }

  function getUserScoreComments($userId){
      $query = $this->db->get_where('comments', array('userId' => $userId))->result();
      $userScore = 0;
      foreach($query as $q){
            $userScore = $userScore + $q->commentScore;
      }
      return $userScore;
  }

  function updateVotes($postId, $totalVotes){
      $this->db->where('postId', $postId);
      $this->db->update('posts', array('postVotes' => $totalVotes));
  }

  function getUserPosts($userId){
      return $this->db->get_where('posts', array('userId' => $userId))->result();
  }

  function getUserComments($userId){
      return $this->db->get_where('comments', array('userId' => $userId))->result();
  }

  function getPostVotes($postId){
      $query = $this->db->get_where('posts', array('postId' => $postId));
      return $query->row('postVotes');
  }

  function saveComment($user, $comment, $id, $userId){
      $data = array(
              'commentUser' => $user,
              'commentText' => $comment,
              'commentPostId' => $id,
              'userId' => $userId
      );

      if(!empty($user) && !empty($comment) && !empty($id)){
        $this->db->insert('comments', $data);
      }
  }

  function loadComments($id){
      return $this->db->get_where('comments', array('commentPostId' => $id))->result();
  }

  //SAVE CHILD COMMENTS
  function saveChildComment($id, $user, $comment, $currentPostId, $userId){
    $data = array(
            'commentUser' => $user,
            'commentText' => $comment,
            'parentId' => $id,
            'commentPostId' => $currentPostId,
            'userId' => $userId
    );
    if(!empty($user) && !empty($comment) && !empty($id) && !empty($currentPostId)){
      $this->db->insert('comments', $data);
    }
  }

  function paginate($pageNumber){
      $numOfRows = $this->db->count_all('posts');
      $maxRecords = 5;
      if($pageNumber == 0){ $pageNumber = 1;}
      $offset = ($pageNumber-1) * $maxRecords;
      $this->db->order_by('postVotes', 'asc');
      $this->db->limit($maxRecords, $offset);
      $query = $this->db->get('posts')->result();
      return $query;
  }

  function sortByDate(){
      $this->db->order_by('postDate', 'desc');
      return $this->db->get('posts')->result();
  }

  function rowCount(){
      return $this->db->count_all('posts');
  }

  function registerUser($username, $password1, $password2){

      if(!empty($username) && !empty($password1) && !empty($password2)){
            if($password1 == $password2){
              $hashPw = password_hash($password1, PASSWORD_DEFAULT);
                $data = array(
                        'userName' => $username,
                        'userPassword' => $hashPw
                );
                $this->db->insert('php_users', $data);
            }
      }
  }

  function loginUser($userName, $userPassword){
    $q = $this->db->get_where('php_users', array('userName' => $userName));
    $pw = $q->row('userPassword');
    if(password_verify($userPassword, $pw)){
        return $q->num_rows();
    }
    //$q = $this->db->get_where('php_users', array('userName' => $userName, 'userPassword' => $userPassword));
    //return $q->num_rows();
  }

  function getUserId($userName){
    $this->db->select('userId');
    $query = $this->db->get_where( 'php_users', array('userName' => $userName) );
    return $var = $query->row('userId');
  }

  function upVoteComment($commentId, $userId){
    $data = array(
            'commentId' => $commentId,
            'userId' => $userId,
            'votesScore' => '2'
    );
    $query = $this->db->get_where('php_votes', array('userId' => $userId));
    $query2 = $this->db->get_where('php_votes', array('commentId' => $commentId));
    $uId = $query->row('userId');
    $cId = $query2->row('commentId');

    $query3 = $this->db->get_where('php_votes', array('userId' => $uId, 'commentId' => $cId));
    $user = $query3->row('userId');
    $comment = $query3->row('commentId');
    // IF USER HAS NOT VOTED, INSERT VOTE
    if(!($user == $userId) && !($comment == $commentId)){
        $this->db->insert('php_votes', $data);
    }
    $count = $query3->num_rows();
    if($count > 0){
      $this->db->where('userId', $user)->where('commentId', $commentId);
      $this->db->update('php_votes', array('votesScore' => '2'));
    }
  }

  function downVoteComment($commentId, $userId){
    $data = array(
            'commentId' => $commentId,
            'userId' => $userId,
            'votesScore' => '1'
    );
    $query = $this->db->get_where('php_votes', array('userId' => $userId));
    $query2 = $this->db->get_where('php_votes', array('commentId' => $commentId));
    $uId = $query->row('userId');
    $cId = $query2->row('commentId');

    $query3 = $this->db->get_where('php_votes', array('userId' => $uId, 'commentId' => $cId));
    $user = $query3->row('userId');
    $comment = $query3->row('commentId');
    // IF USER HAS NOT VOTED, INSERT VOTE
    if(!($user == $userId) && !($comment == $commentId)){
        $this->db->insert('php_votes', $data);
    }
    $count = $query3->num_rows();
    if($count > 0){
      $this->db->where('userId', $user)->where('commentId', $commentId);
      $this->db->update('php_votes', array('votesScore' => '1'));
    }
  }

  function countCommentVotes($userId, $commentId){
    $upVotesQuery = $this->db->where('commentId', $commentId)->where('votesScore', '2')->get('php_votes');
    $upVotes = $upVotesQuery->num_rows();

    $downVotesQuery = $this->db->where('commentId', $commentId)->where('votesScore', '1')->get('php_votes');
    $downVotes = $downVotesQuery->num_rows();
    return $totalVotes = $upVotes - $downVotes;
  }

  function updateCommentVotes($commentId, $totalVotes){
      $this->db->where('commentId', $commentId);
      $this->db->update('comments', array('commentScore' => $totalVotes));
  }



}
?>
