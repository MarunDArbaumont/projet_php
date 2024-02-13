    <main>
        <h1>Welcome to My Website</h1>  
    </main>
    <?php if (!isset($_SESSION['id_user'])) { ?>
      <a href="index.php?action=form_login">login</a><br>
      <a href="index.php?action=form_register">register</a><br>
    <?php } else { // dans ce ELSE on a un utilisateur connecte ?> 
      Hello <?php echo $connected_user->login; ?>
      (connected with user #<?php echo $connected_user->id; ?>) 
      <a href="index.php?action=logout">logout</a><br><br>
      <form method="post" action="index.php?action=post_message">
        message <input type="text" name="message"><br>
        <input type="submit" value="post message" />
      </form>
      <br><br>

      <?php if (!empty($message)) { ?>
        Messages :<br>
        <?php foreach ($message as $message) { 
          if($_SESSION['id_user'] === $message->author_id){ ?>
          <div>
            <h3> <?php echo $message->author_name ?> </h3>
           <p><?php echo $message->message; ?></p><a href="index.php?action=form_modify_message&id=<?php echo $message->id ?>">modify message</a>
           <a href="index.php?action=delete&id=<?php echo $message->id; ?>">Delete</a>
          </div>
          <?php } else{ ?>
            <div>
            <h3><?php echo $message->author_name; ?></h3>
            <p><?php echo $message->message; ?></p> 
            <a href="index.php?action=delete&id=<?php echo $message->id; ?>">Delete</a>
        </div>
          <?php } 
        }  
    }} ?>




    <!-- pour ajouter le concept de modification de message

    - Savoir que le message appartient à l'utilisateur connecté
    - Afficher la possibilité de modifier le message      
    - Envoyer vers une page de modification de message      
    - re poster le message ou juste le modifier dans la bdd
  -->


  <!-- variable pour l'id de l'user $_SESSION['id_user'] 
if($_SESSION['id_user'] === $message->author_id){ ?>
  <p><?php echo $message->message; ?></p><a href="index.php?action=form_modify_message">modify message</a>
}else{
  <h3><?php echo $_SESSION['id_user']; ?></h3>
            <p><?php echo $message->message; ?></p> 
        </div>
}
-->