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

      <?php if (!empty($messages)) { ?>
        Messages :<br>
        <ul>
        <?php foreach ($messages as $message) { ?>
        <li> <?php echo $user->author_name.' : '.$message->message;  ?></li>
        <?php }  ?>
        </ul>
      <?php }  ?>
    <?php }  ?>



<!--
    pour ajouter le nom d'utilisateur de l'auteur du message

    - récupérer l'id_author du message
    - retrouver le pseudo relier à l'id author
    - affiché le pseudo (login)
-->
