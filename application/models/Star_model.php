<?php

class Star_model extends CI_Model{

  function __construct(){
    parent::__construct();
  }

  function lookup(){
    $max = count($this->names);
    $whopos = rand(0, $max-1);
    $name = $this->names[$whopos];
    return $name;
  }

	function returnMovies(){
	 	return $this->db->get('Movies')->result();
	}

  function insertPosts($n, $d, $u){
      //postID //postName //postDescription //postVotes //postUser //postDate
      $date = date("Y-m-d");
      $data = array(
              'postName' => $n,
              'postDescription' => $d,
              'postVotes' => '0',
              'postUser' => $u,
              'postDate' => $date
      );

      if(isset($n) && isset($d) && isset($u)){
        $this->db->insert('posts', $data);
      }
  }

  function showPosts(){
    return $this->db->get('posts')->result();
  }

  function selectSinglePost($postId){
      return $this->db->get_where('posts', array('postId' => $postId))->result();
      //return $this->db->get('posts')->result();

  }

}
?>
