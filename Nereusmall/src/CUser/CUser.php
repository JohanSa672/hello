<?php

class CUser {
	
	/**
	*	Members
	*/
	

	/**
	*	Constructor
	*/	
	
	public function __construct(){
	
	}

	
	// Check if user is authenticated.
	public function userAuthenticated(){
		$acronym=isset($_SESSION['user']) ? $_SESSION['user']->acronym : null;

		if($acronym) {
			$output="Du är inloggad som: $acronym ({$_SESSION['user']->name})";	
		}
		else {
			$output="Du är INTE inloggad.";	
		}
		return $output;
	}
	
	// Check if user and password is okey
	public function checkUserPassword($login,$acronym,$password,$pdo){
		
			$sql="SELECT acronym, name FROM User WHERE acronym=? AND password=md5(concat(?,salt))";
			$sth=$pdo->prepare($sql);
			$sth->execute(array($acronym, $password));
			$res=$sth->fetchAll();
			if(isset($res[0])) {
				$_SESSION['user']=$res[0];	
			}
			header('Location: login.php');
	}
	
	// Connect an owner to the content
	public function connectContentToOwner($db,$title){
		
		$params=null;
		$owner=isset($_SESSION['user'])? $_SESSION['user']->acronym :null;
		$paramsus=null;
		$paramsus=array($owner);
		$sqlus="SELECT id FROM user WHERE acronym=?";
		$id=$db->ExecuteSelectQueryAndFetchAll($sqlus, $paramsus);
		foreach($id as $value){
			$id2=$value;
		}
		$id3=$id2->id;
		/*echo "owner:";
		var_dump($owner);
		echo "id:";
		var_dump($id3);*/
		$params=array($id3,$title);
		$sql="UPDATE content SET owner =? WHERE title = ?";
		$a=$db->ExecuteQuery($sql, $params);
		
/*		if(!$a){
			$sql="SELECT *
			FROM content AS C, user AS U
			WHERE C.owner = U.id";
			SELECT C.id, C.slug, C.url, C.type, C.title, C.data, C.filter, C.owner, C.updatedperson,C.published, C.created, C.updated, C.deleted, U.acronym, U.name, U.password, U.salt
FROM content AS C, user AS U
WHERE C.owner = U.id
*/					
	}
	
	public function getOwner($owner,$db){
		$sql="SELECT name FROM user WHERE id=?";
		$params=array($owner);
		$b=$db->ExecuteSelectQueryAndFetchAll($sql, $params);
		$c=$b[0];
		$name=$c->name;
		return $name;
	}
	
	//Get id of owner
	public function getOwnerId($db){
		$acronym=$_SESSION['user']->acronym;
		$sql="SELECT id FROM user WHERE acronym=?";
		$params=array($acronym);
		$b=$db->ExecuteSelectQueryAndFetchAll($sql, $params);
		$idob=$b[0];
		$id=$idob->id;
		return $id;
	}
	
	// Logout the user
	public function logOut($logout){
		unset($_SESSION['user']);
		header('Location: logout.php');
	}

}