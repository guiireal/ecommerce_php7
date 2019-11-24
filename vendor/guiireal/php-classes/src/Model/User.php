<?php

namespace GuiiReal\Model;

use Exception;
use GuiiReal\DB\Sql;
use GuiiReal\Model;

class User extends Model {

    public const SESSION = 'User';

    public static function login(string $login, string $password) {

        $sql = new Sql();
        $results = $sql->select("SELECT * FROM tb_users WHERE deslogin = :LOGIN", array(
            ":LOGIN" => $login
        ));

        if (count($results) === 0) throw new Exception('Usuário inexistente ou senha inválida.');

        $data = $results[0];
        if (password_verify($password, $data['despassword'])) {
            $user = new User();
            $user->setData($data);
            $_SESSION[User::SESSION] = $user->getValues();
        } else throw new Exception('Usuário inexistente ou senha inválida.');

    }

    public static function verifyLogin($inadmin = true) {
        if (
            !isset($_SESSION[User::SESSION])
            || !$_SESSION[User::SESSION]
            || !(int) $_SESSION[User::SESSION]['iduser'] > 0
            || (bool) $_SESSION[User::SESSION]['inadmin'] !== $inadmin
        ) {
            header('Location: /admin/login');
            exit;
        }
    }

    public static function listAll() {
        $sql = new Sql();
        $results = $sql
            ->select("
                SELECT * FROM tb_users AS u 
                INNER JOIN tb_persons AS p ON u.idperson = p.idperson
                ORDER BY p.desperson
            ");
        return $results;
    }

    public function get($iduser) {
        $sql = new Sql();
       $results =  $sql
            ->select("
                SELECT * FROM tb_users AS u 
                INNER JOIN tb_persons p on u.idperson = p.idperson
                WHERE u.iduser = :iduser
            ", [':iduser' => $iduser]);
       $this->setData($results[0]);
    }

    public function update() {
        $sql = new Sql();
        $results = $sql->select("CALL sp_usersupdate_save(:iduser, :desperson, :deslogin, :despassword, :desemail, :nrphone, :inadmin)", array(
            ':iduser' => $this->getiduser(),
            ':desperson' => $this->getdesperson(),
            ':deslogin' => $this->getdeslogin(),
            ':despassword' => $this->getdespassword(),
            ':desemail' => $this->getdesemail(),
            ':nrphone' => $this->nrphone(),
            ':inadmin' => $this->inadmin()
        ));
        $this->setData($results[0]);
    }

    public function save() {
        $sql = new Sql();
        $results = $sql->select("CALL sp_users_save(:desperson, :deslogin, :despassword, :desemail, :nrphone, :inadmin)", array(
            ':desperson' => $this->getdesperson(),
            ':deslogin' => $this->getdeslogin(),
            ':despassword' => $this->getdespassword(),
            ':desemail' => $this->getdesemail(),
            ':nrphone' => $this->nrphone(),
            ':inadmin' => $this->inadmin()
        ));
        $this->setData($results[0]);
    }

    public function delete() {
        $sql = new Sql();
        $sql->query("CALL sp_users_delete(:iduser)", [
            ':iduser' => $this->getiduser()
        ]);
    }

    public static function logout() {
        $_SESSION[User::SESSION] = null;
    }

}