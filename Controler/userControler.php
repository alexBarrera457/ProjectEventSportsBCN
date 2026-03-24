<?php
session_start();
If($_SERVER['REQUEST_METHOD'] == "POST"){
    $user = new userControler();
    //vemos que boton es el que presionar el usuario
    if(isset($_POST["login"])){
        echo "<p>login button clicked.</p>";
        $user->login();
    }
    if(isset($_POST["logout"])){
        echo "<p>logout button clicked.</p>";
        $user->logout();
    }
    if(isset($_POST["register"])){
        echo "<p>register button clicked.</p>";
        $user->register();
    }

}



//definimos clase
class userControler
{
    //definimos atributos

    private $conn;
    public function __construct()
    {

        $usuario = "root";
        $password = "";

        $host = "localhost";
        $base_datos = "mi_base_datos";
        //conexion creada
        $this->conn = new mysqli($host, $usuario, $password, $base_datos);


        //verificamos si la sesion es correcta
        // if($this->conn -> conect_error){
        //     die("Error de conexion: " . $this->conn->connect_error);
        // }
        // echo "Conexion realizada";

        //charset establecido
        //$conexion -> set_charset("utf8mb4");
        $this->conn->set_charset("utf8mb4");

        //finalizar conexion

        $this->conn->close();
    }

    //metodos
    public function login() {
        $sql = "Select usuario, password";
        $stmt = $this->conn->prepare($sql);
        $usuario = "root";
        $password = "";
        $stmt ->bind_param("s",$usuario,"i",$password);

        //ejecutar consulta
        $stmt ->execute();

        //resultados
        $resultado = $stmt->get_result();
        while($fila = $resultado ->fetch_assoc()){
            echo"Usuario: " . $fila['usuario'] . 
        }



    }



    public function logout() {}

    public function register() {}





    //conexion con base de datos

}
