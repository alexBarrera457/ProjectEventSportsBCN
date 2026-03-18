<?php




//definimos clase
class userControler{
    //definimos atributos

    private $conn;
    public function__construct(){


$host = "localhost";
$usuario = "root";
$password = "";
$host = "localhost";
$host = "localhost";
$base_datos = "mi_base_datos";
//conexion creada
$conexion = new mysqli($host, $usuario, $password, $base_datos);


//verificamos si la sesion es correcta
if($conexion -> conect_error){
    die("Error de conexion: " . $conexion->connect_error);
}
echo "Conexion realizada";

//charset establecido
$conexion -> set_charset("utf8mb4");

//finalizar conexion
$conexion -> close();


    //metodos
    public function login(){

    }

    public function logout(){

    }

    public function register(){

    }





//conexion con base de datos

    }
?>
