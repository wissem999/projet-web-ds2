<?php
class Database {
    private $conn;

    public function __construct($servername, $username, $password, $dbname) {
        $this->conn = new mysqli($servername, $username, $password, $dbname);
        if ($this->conn->connect_error) {
            die("La connexion a échoué : " . $this->conn->connect_error);
        }
    }

    public function executeQuery($sql) {
        return $this->conn->query($sql);
    }

    public function fetchSingle($result) {
        return $result->fetch_assoc();
    }

    public function close() {
        $this->conn->close();
    }

    public function insertComment($name, $comment) {
        $stmt = $this->conn->prepare("INSERT INTO table3 (name, comment) VALUES (?, ?)");
        $stmt->bind_param("ss", $name, $comment);
        $stmt->execute();
        $stmt->close();
    }
}

$database = new Database("localhost", "root", "", "db1");

$email_error = "";

// Check if form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $_POST['name'] ?? "";
    $comment = $_POST['comment'] ?? "";

    if (!empty($name) && !empty($comment)) {
        $database->insertComment($name, $comment);
    } else {
        echo "Erreur : Les champs Nom et Commentaire sont obligatoires.";
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="vendor/twbs/bootstrap/dist/css/bootstrap.min.css" rel="stylesheet">
    <title>Article de Blog</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: black;
            color: #eee;
            margin: 0;
            padding: 0;
        }

        header {
            background-color: black;
            color: #fff;
            padding: 1rem;
            text-align: center;
        }

        header h1 {
            font-size: 2rem;
            margin-bottom: 0.5rem;
        }

        nav ul {
            list-style-type: none;
            padding: 0;
            margin: 0;
        }

        nav ul li {
            display: inline;
            margin-right: 20px;
        }

        nav ul li a {
            color: #fff;
            text-decoration: none;
            font-size: 1.2rem;
            transition: color 0.3s ease;
        }

        nav ul li a:hover {
            color: red;
        }

        main {
            padding: 20px;
        }

        article {
            background-color: #222;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.3);
            font-size: 20px;
        }

        article img {
            width: 50%;
            display: block;
            margin-left: auto;
            margin-right: auto;
            border-radius: 10px;
        }

        .comment-section {
            margin-top: 20px;
            text-align: left;
        }

        .comment-section h3 {
            font-size: 1.5rem;
            margin-bottom: 10px;
            color: red;
        }

        .comment-section form {
            margin-bottom: 20px;
            text-align: left;
        }

        .comment-section form label {
            font-weight: bold;
            color: red;
            display: block;
        }

        .comment-section form input[type="text"],
        .comment-section form textarea {
            width: calc(100% - 40px);
            padding: 8px;
            margin-top: 6px;
            margin-bottom: 10px;
            border: 1px solid #888;
            border-radius: 4px;
            box-sizing: border-box;
            background-color: #444;
            color: #eee;
        }

        .comment-section form input[type="submit"] {
            background-color: red;
            color: white;
            padding: 8px 16px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }

        .comment-section form input[type="submit"]:hover {
            background-color: green;
        }

        .comments {
            border-top: 1px solid #888;
            padding-top: 10px;
        }

        .comment {
            background-color: #555353;
            border: 1px solid #ddd;
            padding: 10px;
            margin-bottom: 10px;
            text-align: left;
        }

        .comment p {
            margin: 0;
        }
    </style>
</head>
<body>
    <header>
        <h1 style="color: red;">Support for Gaza: Gestures of solidarity rise across the world</h1>
    </header>
    <main>
        <article>
            <img src="pales3.avif" alt="Image de l'article">
            <h2 style="color: red;">A surge of solidarity on the summits of Loire-Atlantique</h2>
            <p>On April 13, in Fercé in Loire-Atlantique, activists gathered to raise the Palestinian flag on the highest point in the region. This symbolic action, orchestrated by AFPS44, brought together 58 determined participants despite the police presence. The gesture aims to express concrete support for the population of Gaza, facing a critical humanitarian situation.
            During this event, speakers denounced the blatant injustices suffered by Palestinians, including acts of violence perpetrated by the Israeli army and the settlement policy in the West Bank. They called for urgent international action to end the suffering of the Palestinian people and to guarantee their fundamental rights. This demonstration illustrates the desire of civil society to unite in solidarity with Gaza and to defend the principles of justice and human dignity.
            </p>
            
        </article>
        <br>
        
        <div class="comment-section" id="comment-section">
            <h3>Discussion</h3>

            <article>
                <div id="comments">
                    <?php 
                        $sql = "SELECT name, comment FROM table3"; // Assuming your table name is table3
                        $result = $database->executeQuery($sql);
                        
                        // Check if there are any comments
                        if ($result->num_rows > 0) {
                            // Output each comment
                            while ($row = $database->fetchSingle($result)) {
                                $name = $row['name'];
                                $comment = $row['comment'];
                                echo "<div class='comment'>$name: $comment</div>";
                            }
                        } else {
                            // Output a message if there are no comments
                            echo "<div class='comment no-comments'>pas de comentaire</div>";
                        }
                    ?>
                </div>
            </article>
            <br>
        
            <form id="commentForm" action="#comment-section" method="post">
                <label for="name">Nom:</label><br>
                <input type="text" id="name" name="name" required><br>
                <label for="commentText">Commentaire:</label><br>
                <textarea id="commentText" name="comment" rows="4" cols="50" required></textarea><br>
                <input type="submit" value="Ajouter un commentaire">
            </form>
        </div>
    </main>
    <footer>
       <br>
    </footer>

    <?php
    // Close the database connection
    $database->close();
    ?>

</body>
</html>