<?php require_once('common.php');
if($_COOKIE['userinfo']) {
  $userinfo = unserialize(base64_decode($_COOKIE["userinfo"]));
}
?>
<form id="user-form">
<div class="modal-body">
<?php
$score = $_GET['s'];
echo "<p>Congratulations! You scored $score points</p>";
?>
<a href="
https://twitter.com/intent/tweet?
text=<?php echo urlencode("My score at #UrbanOpticon was $score! Test your knowledge of #London at $BASE_URL #ldn")?>"
class="btn"
>
<img src="images/tweet.png" class="icon"/>
Tweet it!
</a>
<a href="
https://www.facebook.com/dialog/feed?
app_id=297302786992554&
link=<?php echo $BASE_URL;?>&
picture=http://fbrell.com/f8.jpg&
name=<?php echo urlencode("UrbanOpticon");?>&
caption=<?php echo urlencode("How well do you know London?");?>&
description=<?php echo urlencode("My score at UrbanOpticon was $score! Think you can beat me?");?>&
redirect_uri=http://www.urbanopticon.org/
"
target="_blank"
class="btn">
<img src="images/facebook.png" class="icon"/>
Share on Facebook</a>

<p>Can you tell us a little bit about yourself? All data is optional and will be used for research statistics only.</p>

<div class="clearfix">
  <label for="username">Username</label>
  <div class="input">
    <input class="xlarge" id="username" name="username" size="30" type="text" value="<?php echo $userinfo['username']?>"/>
  </div>
</div>
<div class="clearfix">
  <label for="twitter">Twitter</label>
  <div class="input">
  <div class="input-prepend">
    <span class="add-on">@</span>
    <input class="xlarge" id="twitter" name="twitter" size="16" type="text" value="<?php echo $userinfo['twitter']?>"/>
  </div>
  </div>
</div>
<div class="clearfix">
  <label for="email">E-mail</label>
  <div class="input">
    <input class="xlarge" id="email" name="email" size="30" type="text" value="<?php echo $userinfo['email']?>"/>
  </div>
</div>
<div class="clearfix">
<div class="row">
<div class="span5">
  <label for="gender">Gender</label>
  <div class="input">
    <select class="medium" name="gender" id="gender">
      <option <?php if($userinfo['gender']=='Undisclosed') echo 'selected=\"selected\"';?>>Undisclosed</option>
      <option <?php if($userinfo['gender']=='Female') echo 'selected=\"selected\"';?>>Female</option>
      <option <?php if($userinfo['gender']=='Male') echo 'selected=\"selected\"';?>>Male</option>
    </select>
  </div>
</div>
<div class="span4" id="age-div">
  <label for="age">Age</label>
  <div class="input">
    <input id="age" name="age" size="3" type="text" value="<?php echo $userinfo['age']?>"/>
  </div>
</div>
</div>
</div>
<div class="clearfix">
  <label for="postcode">Postcode</label>
  <div class="input">
    <input class="xlarge" id="postcode" name="postcode" size="30" type="text" value="<?php echo $userinfo['postcode']?>"]/>
  </div>
</div>
<div class="clearfix">
  <label for="ethnic">Ethnic Group</label>
  <div class="input">
    <input class="xlarge" id="ethnic" name="ethnic" size="30" type="text" value="<?php echo $userinfo['ethnic']?>"]/>
  </div>
</div>
<div class="clearfix">
  <label for="occupation">Occupation</label>
  <div class="input">
    <input class="xlarge" id="occupation" name="occupation" size="30" type="text" value="<?php echo $userinfo['occupation']?>"]/>
  </div>
</div>
</div>
<div class="modal-footer">
<input type="submit" id="submit-user" class="btn primary" value="Ok" />
</div>
</form>
