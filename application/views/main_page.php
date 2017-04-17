
<!DOCTYPE html>
<html>

<head>
     <meta charset="UTF-8">
     <link rel="stylesheet" href="<?php echo base_url('css/styles.css')?>" />
     <link href="https://fonts.googleapis.com/css?family=Dosis" rel="stylesheet">
     <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">
</head>


<body>
    <div id="myContainer">
        <!-- NAVIGATION -->
          <nav style="margin-top:10px;">
                <ul id="menu">
                      <li id="homeBtn"> <a style="text-decoration:none;" href="<?php echo site_url('Website')?>" id="#home">Home</a></li>
                      <?php if(isset($_SESSION['USER'])){echo '<li><a style="text-decoration:none;" id="addpostBtn">Add Post</a></li>'; }?>
                      <?php if(!isset($_SESSION['USER'])){
                            echo '<li><a id="registerAnchor" style="text-decoration:none;" href="' . site_url('Website') . '/register"> Register </a></li>';
                      } ?>

                      <?php if(!isset($_SESSION['USER'])){ echo '<li><a style="text-decoration:none;" id="loginBtn">Login</a></li>'; }?>
                      <?php if(isset($_SESSION['USER'])){
                           echo '<li><a style="text-decoration:none;" href="' . site_url('Website') . '/logout"> Logout </a>'; } ?>
                </ul>
          </nav>

          <div id="user" style="text-align: center; margin-left:20px; padding:20px; font-size: 18px;">
                <?php   if(isset($_SESSION['USER'])){ ?>
                      <a style="text-decoration:none;" id="sort" href="<?php echo site_url('Website')?>/userProfile">
                          <?php echo $_SESSION['USER']; ?></a>
                <?php  } else{ echo "Logged out"; } ?>
          </div>

          <div id="addpost">
            <span> Generate your post </span>
                <form method=POST id="addpostform">
                      <input type="text" id="postName" name="name" placeholder="Subject name" /> </br>
                      <input type="text" id="postDescription" name="description" placeholder="Description" /> </br>
                      <input type="hidden" name="user" placeholder="User" /> </br>
                      <input type="submit" id="send" value="Submit"/>
                </form>
          </div>

          <div style="margin:0 auto; text-align:center; visibility:hidden; display:none;" id="login">
                <form id="loginForm">
                      <input style="width:200px; height:40px;" type="text" name="username" id="username" placeholder="Username" /> <br>
                      <input style="width:200px; height:40px;" type="password" name="userpassword" id="userpassword" placeholder="Password" /> <br>
                </form>
                <button style="width:200px; height:35px; text-align:center; margin:0 auto;" id="loginUserBtn"> Login </button>
          </div>

          <div id="p">
              <div id="postss"> </div>
                  <div id="pagination">
                    <?php
                        // 'POSTS' row count
                        $total = $row_number;
                        // LIMIT POSTS PER PAGE
                        $limit = 5;
                        // NUMBER OF PAGES
                        $pages = ceil($total / $limit);
                        //START FROM
                        if($pages == 0){ $pages = 1; }
                        for ($i=0; $i < $pages; $i++) {
                            printf('<a class="pagination-item status" style="cursor:pointer;" data-page="%d">%d</a>', $i, $i+1);
                        }
                        echo "<br>";
                     ?>
                  </div>
          <!-- END OF #p -->
          </div>

          <script id="postTemplate" type="text/template">
            <div id="posts">
                <a href="<?php echo site_url('Website');?>/getSinglePost/<%= id %>"> <div id="single-post"><%= postName %></div> </a>
                <div class="upvote">
                  <a style="cursor:pointer" class="vote vote-up"><span class="glyphicon glyphicon-arrow-up"></span></a>
                </div>
                <div class="downvote">
                  <a style="cursor:pointer" class="vote vote-down"><span class="glyphicon glyphicon-arrow-down"></span></a>
                </div>

                <div style="clear:both; padding:7px;">
                  <div id="user"><strong>Submitted by : </strong> <%= postUser %> </div>
                  <div id="votes"><span><strong id="votesNumber"> <%= postVotes %> </span></strong>Votes</div>
                  <div id="commentNumber">Submission date : <strong> <%= postDate %> </strong></div>
                </div>
            </div>
          </script>

    </div>

    <!-- SCRIPT LINKS -->
    <script src="<?php echo base_url('js/jquery-3.1.1.min.js')?>"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/underscore.js/1.5.2/underscore-min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/backbone.js/1.0.0/backbone-min.js"></script>


    <script>
    var postName = $("#postName").val();
    var postDescription = $("#postDescription").val();

    var theUrl = "https://w1440760.users.ecs.westminster.ac.uk/CodeIgniter-3.1.0/";

    var Posts = Backbone.Model.extend();
    var PostsList = Backbone.Collection.extend({
        model: Posts,
        url: theUrl + 'index.php/restapi/posts'
    });

    var PostsView = Backbone.View.extend({
        //VIEW IS RENDERED IN #POSTS DIV
        el: "#postss",
        initialize: function(){
            this.collection = new PostsList();
            //BINDS A FUNCTION TO THE EVENT
            //WHENEVER IT FIRES LETS SAY ADD EVENT, IT CALLS THIS.RENDER
            this.listenTo(this.collection, "add", this.render);
            this.listenTo(this.collection, "reset", this.render);
            //GETS ALL JSON DATA
            this.collection.fetch();
            $(".pagination-item").on("click", this.paginationClick.bind(this));
            //PAGE NUM
        },
        render: function() {
          console.log(this.collection);
            $("#postss").children().remove();
            //COLLECTION  HAS MODELS ATTRIBUTES THAT CONTAINS ALL THE RETRIEVED POSTS
            //this.collection.models ACCESS to JavaScript array of models
            _.each(this.collection.models, function(post){
              //_.each loops through the collection array and passes into function
              // newPost is a view and passing the collection array to a model
                  var newPost = new PostView({model: post});

                  //Rendering and appending the view. for example,
                  //newPost.el stored <div> all posts </div>
                  newPost.render();
                  this.$el.append(newPost.el);
            }, this);
            return this;
        },paginationClick: function(event){
            //JQuery currentTarget selector, selects data with with "data-page"
            var page = 1 + $(event.currentTarget).data("page");
            this.collection.fetch({reset: true, data: {page: page}});
            //clears the collection before it adds new data : reset:true
        }
    });

    var PostView = Backbone.View.extend({
        template: _.template($("#postTemplate").html()),
        initialize: function(){
            this.listenTo(this.model, "change", this.render);
        },
        render: function(){
          this.$el.html(this.template(this.model.toJSON()));
          return this;
        },events: {
          "click .vote": "voteClick"
        },
         voteClick: function(event){
            var el = $(event.currentTarget);
            console.log(el);
                if (el.hasClass("vote-up")){
                      var ths = this;
                      $.ajax({
                          method: "GET",
                          url: theUrl + 'index.php/restapi/upvote',
                          //GET REQUEST
                          data: {postId: ths.model.id}
                          ,success: function(data) {
                                ths.model.fetch();
                          }
                      })
                }
                else if (el.hasClass("vote-down")){
                      var ths = this;
                      $.ajax({
                          method: "GET",
                          url: theUrl + 'index.php/restapi/downvote',
                          data: {postId: ths.model.id}
                          ,success: function(data) {
                                ths.model.fetch();
                          }
                      })
                }
        }
    })
    var postsView = new PostsView();
    /**************************************************
    ***************************************************
    **************************************************/
    $(document).ready(function(){

      $( "#loginBtn" ).click(function() {
        $( "#login" ).fadeToggle( "fast");
        $( "#login" ).css({"visibility":"visible",
                            "display":"block"});
        $( "#loginUserBtn" ).css({"visibility":"visible",
                            "display":"block"});
      });


    /**************************************************
    ***************************************************
    **************************************************/
    var userModel = Backbone.Model.extend({
        url: theUrl + 'index.php/restapi/login'
    });

    var ModelView = Backbone.View.extend({
        el: '#login',
        events:{
            "click #loginUserBtn": "login"
        },
        login: function(){
            var username = $("#username").val();
            var password = $("#userpassword").val();
            var user = new userModel();
            user.fetch({ data: {username: username, password: password}});

            this.listenTo(user, "change", function(){
                 if(user.get('userStatus') == 0){
                    console.log("offline");
                 }else{
                    $("#user").html('<a href="<?php echo site_url('Website/userProfile')?>/' +user.get('userId')+ '"> '+user.get('userName')+' </a>');
                    $('<li><a style="text-decoration:none;" id="addpostBtn">Add Post</a></li>').insertAfter("#homeBtn");
                    $("#menu").append('<li><a style="text-decoration:none;" href="<?php echo site_url('Website') ?>/logout"> Logout </a>');
                    $("#registerAnchor").remove();
                    $("#loginBtn").remove();
                    $("#login").hide();

                    $( "#addpostBtn" ).click(function() {
                      $( "#addpost" ).fadeToggle( "fast");
                      $( "#addpost" ).css({"visibility":"visible",
                                          "display":"block"});
                    });
                 }
            });
        }
    });
    var modelView = new ModelView();

    $( "#addpostBtn" ).click(function() {
      $( "#addpost" ).fadeToggle( "fast");
      $( "#addpost" ).css({"visibility":"visible",
                          "display":"block"});
    });


    //END OF DOCUMENT
    });


    </script>


</body>

</html>
