<div id='dbstatus' class='dbsucc'>
  <i class="fa fa-database" aria-hidden="true"></i>
</div>
<div class='loginFrame'>
  <div class='main'>

    <canvas id='logo' height='100' width='400'></canvas>
    <h3 id='fistPageText'>Enter domain username</h3>
    <!-- <form method = 'post' action = 'index'> -->
    <span id='errorHolder'></span>
    <span class='fieldname'> Username </span>
    <input class='loginInput' type='text' maxlength="16" name='user' value="<?php echo $data->user; ?>">
    <span id="loginChecker"></span>
    <br>
    <!-- <span class = 'fieldname'> Password </span>
                    <input class = 'loginInput' type="text" name="pass" maxlength="16" value="$pass">
                    <br> -->
    <button class='submitLogin'>Log in</button>
    <!-- </form> -->
    <br>
  </div>
</div>

<?php echo $data->page; ?>
