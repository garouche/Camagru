    <?php
    $msg = "";
    if (isset($_GET["id"]) && isset($_GET["key"])){
        include ("./db_connect.php");
        function checkId()
        {
            global $db;

            $req = $db->prepare("SELECT userid FROM users WHERE userid = ?");
            $req->execute(array(htmlspecialchars($_GET["id"])));
            $id = $req->fetch()["userid"];
            if ($id){
                return true;
            }
            else{
                return false;
            }
        }

        function checkKey()
        {
            global $db;


            $req = $db->prepare("SELECT hash FROM users WHERE hash = ?");
            $req->execute(array(htmlspecialchars($_GET["key"])));
            $key = $req->fetch()["hash"];
            if ($key) {
                return true;
            } else {
                return false;
            }
        }
        if (checkId() && checkKey()){
            $req = $db->prepare("UPDATE users SET valid = 1, hash = NULL WHERE userid = ?");
            $req->execute(array(htmlspecialchars($_GET["id"])));
            $msg = "?msg=2";
        }
    }
    header("Location: ./login.php".$msg);
?>