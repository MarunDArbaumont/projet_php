<main>
    <h1>Modifier votre message</h1>  
</main>
<?php if(isset($_GET('id')) && !empty($_GET('id'))){
    $id_message = $_GET('id');
    if($_GET('id') = $_POST('id')){ ?>
        <form>
            <input type="text" name="modify_message"><br>
            <input type="submit" value="Modifier le message" />
        </form>
    <?php }
}else{
    echo 'Aucune question séléctionner';
}?>
