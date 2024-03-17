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

class Jogador
{
    public $id;
    public $nome;
    public $username;
    public $email;
    public $senha;
    public $dataa;

    
    public function getId() {
        return $this->id;
    }

    public function setId($id) {
        $this->id = $id;
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

    public function getSenha() {
        return $this->senha;
    }

    public function setSenha($senha) {
        $this->senha = $senha;
    }

    public function getDataa() {
        return $this->dataa;
    }

    public function setDataa($dataa) {
        $this->dataa = $dataa;
    }

    
    public function save() {
      global $db;
      if($this->id){
        $stmt = $db->prepare( "UPDATE players SET nome = ?, username = ?, email = ?, senha = ?, data_cadastro = ? WHERE id = ?");
        $stmt->execute([$this->nome, $this->username, $this->email, $this->senha, $this->dataa, $this->id]);
      } else{ 
        $stmt = $db->prepare("INSERT INTO players (nome, username, email, senha, data_cadastro) VALUES (?, ?, ?, ?, ?)");
        $stmt->execute([$this->nome, $this->username, $this->email, $this->senha, $this->dataa]);
      } 
    }


    public function delete() {
      global $db;
      $stmt = $db->prepare("DELETE FROM players WHERE id = ?");
      $stmt->execute([$this->id]);
    }

    public static function getById($id) {
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

    public static function getAll() {
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
class JogadorMapper
{
 

    public function insert(Jogador $jogador)
    {
        global $db;
        
        $stmt = $db->prepare("INSERT INTO players (nome, username, email, senha, data_cadastro) VALUES (?, ?, ?, ?, ?)");
        $stmt->execute([$jogador->getNome(), $jogador->getUsername(), $jogador->getEmail(), $jogador->getSenha(), $jogador->getDataa()]);
    }

    public function update(Jogador $jogador)
    {
      global $db;
      $stmt = $db->prepare( "UPDATE players SET nome = ?, username = ?, email = ?, senha = ?, data_cadastro = ? WHERE id = ?");
      $stmt->execute([$jogador->getNome(), $jogador->getUsername(), $jogador->getEmail(), $jogador->getSenha(), $jogador->getDataa()]);
    }

    public function delete(Jogador $jogador)
    {
      global $db;
      $stmt = $db->prepare("DELETE FROM players WHERE id = ?");
      $stmt->execute([$jogador->getId()]);
    }

    public function getById($id)
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

$jogadorMapper = new JogadorMapper($db);

$jogador = new Jogador();
$jogador->setNome($nomeJogador);
$jogador->setUsername($usernameJogador);
$jogador->setEmail($emailJogador);
$jogador->setSenha($senhaJogador);
$jogador->setDataa(date("Y-m-d"));
