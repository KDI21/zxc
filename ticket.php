<?php
session_start();
if(!isset($_SESSION['login'])){
  echo "Вы не авторизованы и просмотр этой страницы невозможен";
  die;
}
$user = $_SESSION['login'];
$id = $_SESSION['id'];
if ($_SESSION['rights'] == 'usr'){
echo "Вы вошли как $user,$id";
}
else{
  echo "ADMIN";
}
include('connect.php');
include('functions.php');
// echo $_GET['ticket_id'];
// if ((!isset($_GET['ticket_id'])) || (!isset($_GET['tag_id']))){
//   echo "<center><H1>ERROR 404</h1></center>";
//   die;
// }
if(isset($_GET['tag_id']) and isset($_GET['ticket_id'])){
  $tikid = $_GET['ticket_id'];
  $tagid = $_GET['tag_id'];
  $tags = "DELETE FROM `Tickets_Tags` WHERE Tickets_Tags.ticket_id=$tikid and Tickets_Tags.tag_id=$tagid";
  $del_tag = $mysqli->query($tags);
  header("Location: /ticket.php?ticket_id=$tikid");
  die;
}
if(isset($_GET['ticket_id'])){
$i = $_GET['ticket_id'];
$res = $mysqli->query("SELECT ticket_id, name, user_id, project_id, description, assignee_id, status, file_name FROM Tickets WHERE Tickets.ticket_id=$i");
$result = mysqli_fetch_assoc($res);
}

?>
<html>
<head>
  <title>Ticket</title>
  <link rel="stylesheet" href="/ticket.css">
  <script>
  function block(){
    document.getElementById("redd").style.display="block";
    document.getElementById("button").style.display="none";
  };
</script>
</head>
<body>
  <div id=exit>
    <a href="exit.php"><input type="button" value="Выход" id=b_exit></a>
  </div>
<?php
if (isset($_GET['tag_id'])){
  $tagid = $_GET['tag_id'];
  ?>
  <div id=search>
    <a href="/">Назад к проектам</a>
    <?php
    $query = $mysqli->query("SELECT name FROM Tags WHERE Tags.tag_id=$tagid");
    $res_search = mysqli_fetch_assoc($query);
    if(empty($res_search['name'])){
      echo "<center><H1>ERROR 404</h1></center>";
      die;
    }
     ?>
    <center><H4>Поиск по тегу: <?php echo "# ".$res_search['name'];?></H4></center>
    <hr>
    <br>
    <?php
      $tik_id = $mysqli->query("SELECT ticket_id FROM Tickets_Tags WHERE Tickets_Tags.tag_id=$tagid");
      while($res_tik_id = $tik_id->fetch_assoc()){
        $tikid = $res_tik_id['ticket_id'];
        $tik_info =$mysqli->query("SELECT name, user_id FROM Tickets WHERE Tickets.ticket_id=$tikid");
        $res_tik_info = mysqli_fetch_assoc($tik_info);
        $usr_id = $res_tik_info['user_id'];
        $usr_info = $mysqli->query("SELECT login FROM Users WHERE Users.user_id=$usr_id");
        $res_usr_info = mysqli_fetch_assoc($usr_info);
        $login = $res_usr_info['login'];
     ?>
    <div id=tag_tik>
      <div id=name_usr>
        <?php echo $login; ?>
      </div>
      <div id=name_tik>
        <a href="/ticket.php?ticket_id=<?php echo $tikid;?>"><?php echo $res_tik_info['name'];?></a>
      </div>
      <div id=tik_tag>
        <?php
        $tags = $mysqli->query("SELECT tag_id FROM Tickets_Tags WHERE Tickets_Tags.ticket_id=$tikid");
        while ($res_tags = $tags->fetch_assoc()) {
          $tag_id = $res_tags['tag_id'];
          $name_tags = $mysqli->query("SELECT name FROM Tags WHERE Tags.tag_id=$tag_id");
          $res_name_tag = mysqli_fetch_assoc($name_tags);
          $name_tag = $res_name_tag['name'];?>
          <a href="ticket.php?tag_id=<?php echo $tag_id; ?>"><?php echo "#".$name_tag; ?></a>
          <?php
        }
         ?>
      </div>
    </div>
  <?php }?>
  </div>
  <?php
  die;
}
 ?>
  <div id=red>
    <center>
    <H3>Редактировать тикет</H3>
    <?php if ($_SESSION['rights'] == 'adm' || $result['user_id'] == $id){?>
    <p>При редактировании необходимо обязательно заполнить все поля, прикреплять файл не обязательно</p>
    <form action="/edit_tik.php?ticket_id=<?php echo $i;?>" method="post" enctype="multipart/form-data">
      <input name="tik_name" required placeholder="Имя тикета:" autocomplete="off"/><br>
      <input name="description" required placeholder="Описание тикета:" autocomplete="off"/><br>
      <input name="assignee_id" required placeholder="ID получателя" autocomplete="off" /><br>
      <select name="status">
        <option value="Inprogress">In progress</option>
        <option value="New">New</option>
        <option value="Testing">Testing</option>
        <option value="Done">Done</option>
      </select><br>
      <input name="outload" type="file" /><br>
      <input name="id" type="hidden" value="<?php echo $i; ?>"/>
      <input type="submit" value="Отправить" />
    </form>
  <?php }
  else{echo "Нет прав для редактирования";}
  if($result['assignee_id'] == $id){
  ?>
  <form action="/edit_tik.php?ticket_id=<?php echo $i;?>" method="post">
    <select name="status">
      <option value="Inprogress">In progress</option>
      <option value="New">New</option>
      <option value="Testing">Testing</option>
      <option value="Done">Done</option>
    </select>
    <input name="id" type="hidden" value="<?php echo $i; ?>"/>
    <input type="submit" value="Отправить" />
  </form>
<?php }?>
  </center>
  </div>
<div id=div>
<div id=name>
  <p>Имя тикета: <?php echo $result['name'];?></p>
  <p>ID Тикета: <?php echo $result['ticket_id']; ?></p>
  <p>ID Пользователя: <?php echo $result['user_id'];?></p>
  <p>Описание тикета: </H3><p><?php echo $result['description'];?></p>
  <p>Адресат тикета: <?php echo $result['assignee_id'];?></p>
  <p>Статус тикета: <?php echo $result['status']; ?></p>
  <?php
  if ($result['file_name'] == NULL){?>
    <H3>Файл не прикреплён к тикету</H3>

  <?php
}
else{?>
  <H3>Файл: <a href="<?php echo $result['file_name'];?>" download>Скачать</a></H3><?php
}
if(is_allowed($result['user_id'])):
   ?>
   <a href="del.php?ticket_id=<?php echo $i; ?>">Удалить тикет</a><br>
 <?php endif;?>
   <a href="projects.php?project_id=<?php echo $result['project_id'];?>">Назад</a><br><br>
 </div>
 <?php if ($_SESSION['rights'] == 'adm' || $id == $result['user_id']): ?>
   <div id=pole>
     <form action="ticket.php?ticket_id=<?php echo $i; ?>" method="post">
       <input name="tagname" required type="text" required placeholder="Новый тег:" autocomplete="off" id=hhh />
       <input type="hidden" name="tikis" value="<?php echo $i; ?>" />
       <input type="submit" id=enter />
     </form>
     <?php
     if(isset($_POST['tagname'])){
       $tagname = $_POST['tagname'];
       $tikid = $_POST['tikis'];

       $result_tag = $mysqli->query("SELECT EXISTS (SELECT name FROM Tags WHERE name='$tagname')");
       $res = mysqli_fetch_assoc($result_tag);
       foreach ($res as $key => $value) {
         if ($value == 0){
                 $query = "INSERT INTO Tags (name) VALUES ('$tagname')";
                 $res_tag = $mysqli->query($query);

                 $result_search = $mysqli->query("SELECT tag_id FROM Tags where Tags.name = '$tagname'");
                 $res_search = mysqli_fetch_assoc($result_search);

                 $tagid = $res_search['tag_id'];
                 $edit = "INSERT INTO Tickets_Tags (ticket_id, tag_id) VALUES ($tikid, $tagid)";
                 $res_edit = $mysqli->query($edit);
         }
         else{
             $result_search = $mysqli->query("SELECT tag_id FROM Tags where Tags.name = '$tagname'");
             $res_search = mysqli_fetch_assoc($result_search);

             $tagid = $res_search['tag_id'];
             $edit = "INSERT INTO Tickets_Tags (ticket_id, tag_id) VALUES ($tikid, $tagid)";
             $res_edit = $mysqli->query($edit);
         }
       }
       }
      ?>
   </div>
 <?php endif;?>
   <div id=tags>
     <?php
         $tikets_id = $mysqli->query("SELECT tag_id FROM Tickets_Tags WHERE Tickets_Tags.ticket_id=$i");
         while ($res_tik_id = $tikets_id->fetch_assoc()){
         $tagid = $res_tik_id['tag_id'];
         $tags = $mysqli->query("SELECT name FROM Tags WHERE Tags.tag_id=$tagid");
         $res_tagid = mysqli_fetch_assoc($tags);
         ?>
         <div id=tag>
         <a href="ticket.php?tag_id=<?php echo $tagid; ?>" id=atag >#<?php echo $res_tagid['name']; ?> </a>
         <?php if ($_SESSION['rights'] == 'adm' || $id == $result['user_id']): ?>
         <div id=del_tag>
           <a href="del.php?ticket_id=<?php echo $i; ?>&&tag_id=<?php echo $tagid; ?>" id=atag >DELETE</a>
         </div>
       <?php endif;?>
         </div>
       <?php
   }
     ?>
   </div>
<div id=com>
  <div id=comform>
  <form action="/ticket.php?ticket_id=<?php echo $i; ?>" method="post">
  <input name="comment" type="text" autofocus autocomplete="off" placeholder="Комментарий:" id=comm />
  <input type="submit" value="Отправить" id=enter />
</form>
</div>

<?php
if(isset($_POST['comment'])){
if(!empty($_POST['comment'])){
$usr = $id;
$text = $_POST['comment'];
$query = "INSERT INTO Comments (text, user_id, ticket_id) VALUES ('$text', $usr, $i)";
$resulte = $mysqli->query($query);
}
}
$ress= $mysqli->query("SELECT text, user_id, comment_id FROM Comments WHERE Comments.ticket_id=$i ORDER BY `comment_id` desc");
while ($ro = $ress->fetch_assoc()){
  $us = $ro['user_id'];
  $resss= $mysqli->query("SELECT login FROM Users WHERE Users.user_id=$us");
  $ror = $resss->fetch_assoc();
  $del = $ro['comment_id'];
  ?>
</div>

<div id=comment>
<div id=namee >
  <?php if(is_allowed($ro['user_id'])):?>
  <a href="del.php?comment_id=<?php echo $del;?>" id=dell >Удалить</a>
<?php endif;?>
  <H4><?php echo $ror['login'];?></H4>
</div>
<div id=text>
  <div id=redd>
    <?php if(is_allowed($ro['user_id'])):?>
    <form method="post" action="edit_tik.php?ticket_id=<?php echo $i;?>">
      <input name="edit_comm" type="text" autofocus autocomplete="off" placeholder="Редактировать:"/>
      <input name="hidden_comm" type="hidden" value="<?php echo $del; ?>">
    </form>
    <?php endif;?>
  </div>
  <p id=commm><?php echo $ro['text'];?></p>
</div>
  <?php
}
?>
</div>
</div>
</body>
</html>
