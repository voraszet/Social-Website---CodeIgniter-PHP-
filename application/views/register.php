
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
                <ul>
                      <li> <a style="text-decoration:none;" href="<?php echo site_url('Website')?>" id="#home">Home</a></li>
                      <li><a style="text-decoration:none;" href="<?php echo site_url('Website')?>/register"> Register </a></li>
                </ul>
          </nav>
          <div style="margin: 0 auto; text-align:center; margin-top:100px;">
            <span style="font-family: 'Dosis', sans-serif; font-size:40px;" > Registration</span> <br><br>
                <form method=POST id="addpostform">
                      <input type="text" name="username" id="username" placeholder="Username" /> </br>
                      <input type="password" name="password1" id="password1" placeholder="Password" /> </br>
                      <input type="password" name="password2" id="password2" placeholder="Password" /> </br>
                      <input type="submit" id="send" value="Submit"/>
                </form>
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


      $( "#send" ).click(function() {
        var username = $( "#username" ).val();
        var password1 = $("#password1").val();
        var password2 = $("#password2").val();
            if(( username == null) && (password1 == null) && (password2 == null)){
                alert("You must complete all fields!");
            }else if(password1 !== password2){
                alert("Passwords don't match!");
            }else if((username != null) && (password1 == password2)){
                alert("You have succesfully registered!");
            }
      });


    //END OF DOCUMENT
    });

    </script>


</body>

</html>
