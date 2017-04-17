<?php
  class Movies extends CI_Controller{


    function __construct(){
      parent::__construct();
    }


    function searchByYear(){
      $this->load->model("Movies_model");
      $getInfo = $this->Movies_model->loadJohn();
      $this->load->view('movie_display', array('title' => $getInfo));
    }

  }


?>
