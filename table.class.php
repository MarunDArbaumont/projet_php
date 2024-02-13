<?php


abstract class Table
{
	// si l'id est set, alors on recupere les valeurs des autres champs dans la BDD
	public function hydrate()
	{
		global $bdd_host, $bdd_login, $bdd_passwd, $bdd_base;
		$link = mysqli_connect($bdd_host, $bdd_login, $bdd_passwd, $bdd_base);

		$query = 'select * from '.static::$tableName.' where '.static::$primaryKey.
		' = '.$this->{static::$primaryKey};

		$res = mysqli_query($link, $query);
		$line = mysqli_fetch_assoc($res);

		foreach ($line as $fieldname=>$value)
			if ($fieldname != static::$primaryKey)
				$this->{$fieldname} = $value;
	}

	// renvoie une liste de toute les entités de la table en question
	public static function getAll($mode = 'OBJECT') 
	{
		global $bdd_host, $bdd_login, $bdd_passwd, $bdd_base;
		$link = mysqli_connect($bdd_host, $bdd_login, $bdd_passwd, $bdd_base);
		$query = 'select * from '.static::$tableName;

		$res = mysqli_query($link, $query);
		$lines = mysqli_fetch_all($res, MYSQLI_ASSOC);

		$entities_arrays = [];
		$data_objects = [];
		foreach ($lines as $line)
		{
			if ($mode == 'ARRAY') // mode array : renvoie un tableau de tableaux
			{
				// pour chaque film on ajoute dans $films_arrays
				// un tableau avec toutes les données du film
				$entity = [];
				foreach ($line as $fieldname=>$value)
					if ($fieldname != static::$primaryKey)
						$entity[$fieldname] = $value;
				$entities_arrays[] = $entity;
			}
			elseif ($mode == 'OBJECT') //mode objet : tableau d'instances
			{
				// pour chaque entité on ajoute dans $data_objects
				// une instance hydratée de la classe de l'entité en question avec toutes les données de l'entité concernée
				$class = get_called_class();
				$entity = new $class;

				// on set les champs un par un
				foreach ($line as $fieldname=>$value)
					if ($fieldname != static::$primaryKey)
						$entity->$fieldname = $value;

				$data_objects[] = $entity;
			}
		}

		if ($mode == 'ARRAY')
			return $entities_arrays;
		elseif ($mode == 'OBJECT')
			return $data_objects;
	}

		// renvoie une liste de toute les entités de la table en question
	public static function getOne(int $id, $mode = 'OBJECT') 
	{
		global $bdd_host, $bdd_login, $bdd_passwd, $bdd_base;
		$link = mysqli_connect($bdd_host, $bdd_login, $bdd_passwd, $bdd_base);
		$query = 'select * from '.static::$tableName.' where '.static::$primaryKey.'='.$id;

		$res = mysqli_query($link, $query);
		$line = mysqli_fetch_array($res, MYSQLI_ASSOC);

		if ($mode == 'ARRAY') // mode array : renvoie un tableau de tableaux
		{
			// pour chaque film on ajoute dans $films_arrays
			// un tableau avec toutes les données du film
			$entity = [];
			foreach ($line as $fieldname=>$value)
			{
				//if ($fieldname != static::$primaryKey)
					$entity[$fieldname] = $value;
			}
			return $entity;
		}
		elseif ($mode == 'OBJECT') //mode objet : tableau d'instances
		{
			// pour chaque entité on ajoute dans $data_objects
			// une instance hydratée de la classe de l'entité en question avec toutes les données de l'entité concernée
			$class = get_called_class();
			$entity = new $class;

			// on set les champs un par un
			foreach ($line as $fieldname=>$value)
			{
				//if ($fieldname != static::$primaryKey)
					$entity->$fieldname = $value;
			}

			return $entity;
		}
	}

	// enregistre l'instance dans la base de donnée
	public function save()
	{
		global $bdd_host, $bdd_login, $bdd_passwd, $bdd_base;
		$link = mysqli_connect($bdd_host, $bdd_login, $bdd_passwd, $bdd_base);
		$query = '';

		// si $this->{static::$primaryKey} est set alors on genere une requete UPDATE
		if (isset($this->{static::$primaryKey}))
		{
			$query .= 'UPDATE '.static::$tableName.' SET';

 			foreach($this as $field=>$value)
			{
				if ($field != static::$primaryKey)
					$query .= ' '.$field.' =\''.$this->$field.'\',';
			}
			$query = rtrim($query, ',');
 			
			$query .= ' WHERE '.static::$primaryKey.' = '.$this->{static::$primaryKey};
			//echo($query);echo ('<br>');
			$res = mysqli_query($link, $query);
		}
		else // sinon on genere une requete INSERT et on recupere l'id auto-incrémenté
		{
			$query .= 'INSERT INTO '.static::$tableName.' (';
			
			foreach($this as $field=>$value)
			{
				if ($field != static::$primaryKey)
					$query .= $field.',';
			}
			$query = rtrim($query, ',');

			$query .= ') VALUES (';
			
			foreach($this as $field=>$value)
			{
				if ($field != static::$primaryKey)
					$query .= '\''.$value.'\',';
			}
			$query = rtrim($query, ',');

			$query .= ')';
			//echo($query);echo ('<br>');
			$res = mysqli_query($link, $query);
			$pk_val = mysqli_insert_id($link);
			$this->{static::$primaryKey} = $pk_val;
		}
	}
	public function delete()
    {
        global $bdd_host, $bdd_login, $bdd_passwd, $bdd_base;
        $link = mysqli_connect($bdd_host, $bdd_login, $bdd_passwd, $bdd_base);
        $query = 'DELETE FROM '.static::$tableName.' WHERE '.static::$primaryKey.' = '.$this->{static::$primaryKey};
        $res = mysqli_query($link, $query);
    }

}

class Distributeur extends Table
{
	protected static $tableName = 'distributeurs';
	protected static $primaryKey = 'id_distributeur';
}

class Genre extends Table
{
	protected static $tableName = 'genres';
	protected static $primaryKey = 'id_genre';

	// enregistre l'instance dans la base de donnée
	// SAVE SPECIFIQUE A LA CLASSE GENRE
	/*public function save() 
	{
		$link = mysqli_connect('localhost', 'root', '', 'cinema');
		$query = '';

		// si $this->{static::$primaryKey} est set alors on genere une requete UPDATE
		if (isset($this->id_genre))
		{
			$query .= 'UPDATE genres SET nom =\''.$this->nom.'\' WHERE id_genre = '.$this->{static::$primaryKey};
			$res = mysqli_query($link, $query);
		}
		else // sinon on genere une requete INSERT et on recupere l'id auto-incrémenté
		{
			$query .= 'INSERT INTO genres (nom) VALUES (\''.$this->nom.'\')';
			$res = mysqli_query($link, $query);
			$pk_val = mysqli_insert_id($link);
			$this->id_genre = $pk_val;
		}
	}*/
}

class Film extends Table
{
	protected static $tableName = 'films';
	protected static $primaryKey = 'id_film';

	public function hydrate()
	{
		parent::hydrate();

		// /!\ attention cette sous-hydratation n'est pas opti
		// commentez la si votre code est trop long a executer
		if (!empty($this->id_genre))
		{
			$this->genre = new Genre();
			$this->genre->id_genre = $this->id_genre;
			$this->genre->hydrate();
		}
		if (!empty($this->id_distributeur))
		{
			$this->distributeur = new Distributeur();
			$this->distributeur->id_distributeur = $this->id_distributeur;
			$this->distributeur->hydrate();
		}
	}

	public static function getAll($mode = 'OBJECT')
	{
		$films = parent::getAll($mode);
		foreach ($films as $film)
		{
			if (!empty($film->id_genre))
			{
				$film->genre = new Genre();
				$film->genre->id_genre = $film->id_genre;
				$film->genre->hydrate();
			}
			if (!empty($film->id_distributeur))
			{
				$film->distributeur = new Distributeur();
				$film->distributeur->id_distributeur = $film->id_distributeur;
				$film->distributeur->hydrate();
			}
		}

		return $films;
	}
}


// mettez ici vos classes de Data Object

class User extends Table
{
	protected static $tableName = 'users';
	protected static $primaryKey = 'id';

	public static function login($login, $passwd)
	{
		global $bdd_host, $bdd_login, $bdd_passwd, $bdd_base;
		$link = mysqli_connect($bdd_host, $bdd_login, $bdd_passwd, $bdd_base);

		$query = "select * from users where login='$login' and passwd='$passwd'";

		$res = mysqli_query($link, $query);
		$user = mysqli_fetch_array($res, MYSQLI_ASSOC);

		return $user;
	}
}

class Message extends Table
{
	protected static $tableName = 'messages';
	protected static $primaryKey = 'id';
	public static function getOneById($id)
    {
        global $bdd_host, $bdd_login, $bdd_passwd, $bdd_base;
        $link = mysqli_connect($bdd_host, $bdd_login, $bdd_passwd, $bdd_base);

        $id = mysqli_real_escape_string($link, $id);
        $query = "SELECT * FROM messages WHERE id = $id";

        $result = mysqli_query($link, $query);
        if ($result && mysqli_num_rows($result) == 1) {
            return mysqli_fetch_assoc($result);
        } else {
            return null;
        }
    }


}