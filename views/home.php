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
        <?php foreach ($messages as $message){ 
        if($_SESSION['id_user'] === $message->author_id){ ?>
          <div>
            <h3> <?php echo $message->author_name ?> </h3>
           <p><?php echo $message->message; ?></p><a href="index.php?action=form_modify_message">modify message</a>
          </div>
          <?php } else{ ?>
            <div>
            <h3><?php echo $message->author_name; ?></h3>
            <p><?php echo $message->message; ?></p> 
        </div>
          <?php } 
        }  
    }} ?>



<!--
    pour ajouter le concept de message

    - ajouter un formulaire qui pointe vers une action du controller
    - creer la table
    - creer la classe fille dans table.class.php
    - enregister le message en BDD dans une action du controller
    - creer une vue dans le dossier 'views' avec le nom de l'action
    - afficher tout les message sur la page home
-->
