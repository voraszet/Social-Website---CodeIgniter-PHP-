<?php
  class Stars extends CI_Controller{

      function __construct(){
        parent::__construct();
	       //$this->load->database();
         //$this->load->helper('url');
      }


      function index()
      {
          $this->load->model("Star_model");
          $data['Movies'] = $this->Star_model->returnMovies();

          $names = $this->input->post('name');
          $description = $this->input->post('description');
          $user = $this->input->post('user');

          //GET POST VALUES AND PASS IT TO MODEL METHOD
          $this->Star_model->insertPosts($names, $description, $user);

          $posts['posts'] = $this->Star_model->showPosts();

          $this->load->view('main_page', $posts);
      }

      function addposts(){
        $this->load->model('Star_model');
        $this->load->view('add_posts.php');
      }

      function getPostId($postId){
        $postId = $this->uri->segment(3);

        $this->load->model("Star_model");
        $SP['SINGLE_P'] = $this->Star_model->selectSinglePost($postId);
        $this->load->view('single_posts.php', $SP);
      }

      function upVote(){
        $this->load->model("Star_model");
      }

      function downVote(){

      }


  }

?>
