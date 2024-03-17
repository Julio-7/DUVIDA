<?php
include_once 'dataBase.php';


if(isset($_POST['submit']))
{
    $idJogador = $_POST['id'];
    $nomeJogador = $_POST['name'];
    $usernameJogador = $_POST['username'];
    $emailJogador = $_POST['email'];
    $senhaJogador = $_POST['password'];
    $dataJogador = $_POST['dateCreate'];
}

// $stmt   = $db->prepare('SELECT * FROM players;');
// $stmt->execute();
// $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

// print_r($result);
class ActiveRecords
{
  private $id;
  private $nome;
  private $username;
  private $email;
  private $senha;
  private $dataa; //colocando dois 'a' porque esta dando conflinto com 'data' do banco de dados

  public function __construct($id,  $nome,  $username,  $email,  $senha,  $dataa)
  {
    $this->id = $id;
    $this->nome = $nome;
    $this->username = $username;
    $this->email = $email;
    $this->senha = $senha;
    $this->dataa = $dataa;
  }
  public function getNome() {
    return $this->nome;
  }
  
  public function setNome($nome) {
    $this->nome = $nome;
  }

  public function getUsername() {
    return $this->username;
  }
  
  public function setUsername($username) {
    $this->username = $username;
  }

  public function getEmail() {
    return $this->email;
  }
  
  public function setEmail($email) {
    $this->email = $email;
  }

  public function getData() {
    return $this->dataa;
  }
  
  public function setData($dataa) {
    $this->dataa = $dataa;
  }

  public function save()
  {
    global $db;
    if($this->id){
      $stmt = $db->prepare( "UPDATE players SET nome = ?, username = ?, email = ?, senha = ?, data_cadastro = ? WHERE id = ?");
      $stmt->execute([$this->nome, $this->username, $this->email, $this->senha, $this->dataa, $this->id]);
    } else{
      $stmt = $db->prepare("INSERT INTO players (nome, username, email, senha, data_cadastro) VALUES (?, ?, ?, ?, ?)");
      $stmt->execute([$this->nome, $this->username, $this->email, $this->senha, $this->dataa]);
    }    
  }

  public function delete()
  {
    global $db;
    $stmt = $db->prepare("DELETE FROM players WHERE id = ?");
    $stmt->execute([$this->id]);
  }

  public static function getByID($id)
  {
    global $db;
    $stmt = $db->prepare("SELECT * FROM players WHERE id = ?");
    $stmt->execute([$id]);
    $result = $stmt->fetch(PDO::FETCH_ASSOC); 

    if($result){
      return new ActiveRecords($result['id'], $result['nome'], $result['username'], $result['email'], $result['senha'], $result['data_cadastro']); 
    } else{
        return null;
    }
  }

  public function getAll()
  {
    global $db;
    $stmt = $db->prepare('SELECT * FROM players ');
    $stmt->execute();
    $players = $stmt->fetchAll(PDO::FETCH_ASSOC);
    $linhasDeUsuarios = '';
    
    foreach ($players as $player) {
        $linhasDeUsuarios .= '<tr>';
        $linhasDeUsuarios .= "<td>" . $player['nome'] . "</td>";
        $linhasDeUsuarios .= "<td>" . $player['username'] . "</td>";
        $linhasDeUsuarios .= "<td>" . $player['email'] . "</td>";
        $linhasDeUsuarios .= "<td>" . $player['senha'] . "</td>";
        $linhasDeUsuarios .= "</tr>";
        $linhas = $linhasDeUsuarios;
      }
    $template = file_get_contents('listaJogadores.html');
    $template = str_replace("{LINHAS}", $linhas, $template);
    echo($template);
 
}
}


$player = new ActiveRecords(null, $nomeJogador, $usernameJogador, $emailJogador, $senhaJogador, $dataJogador = date('Y-m-d'));// colocando null  no id, pois ja esta como auto increment no banco
$player->save();

$player->getAll();


//DELETANDO
$player3 = ActiveRecords::getByID(39);
$player3->delete();

//COMANDO EDITAR, porem por enquanto mexendo diretamente pela classe
$player2 = ActiveRecords::getByID(3);
$player2->setEmail("Mike@gmail");
$player2->save();
