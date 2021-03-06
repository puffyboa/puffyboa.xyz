<?php

include "DBHandler.php";

$db_folder = "../db";

$handler = new DBHandler("$db_folder/apedia.db");
$handler->init();

session_start();

if ($_GET["id"] == "") {
} else {
    $topic_id = (int)$_GET["id"];
    $topic = $handler->fetchTopicById($topic_id);
}

?>

<html lang="en">
<head>
    <link rel="stylesheet" type="text/css" href="../../assets/css/shared.css">
    <link rel="stylesheet" type="text/css" href="style.css">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>APedia - puffyboa.xyz</title>
    <link rel="shortcut icon" href="../../assets/img/favicon.png" />
</head>

<body>

<div class="back-to-home">
    <a href="../../index.html">puffyboa.xyz</a>
    <a href="index.php">APedia</a>
    <a href=""><?php echo $topic["name"]; ?></a>
</div>

<ul class="nav">
    <?php
    if (isset($_SESSION["loggedin"]) && $_SESSION["loggedin"] === true) {
        $username = $_SESSION["username"];
        $id = $_SESSION["id"];
        echo "<li>Logged in as <a href='user.php?id=$id'>$username</a>. <a href='logout.php'>Log out</a></li>";
    } else {
        echo "<li><a href='login.php'>Log in</a> or <a href='register.php'>Sign up</a></li>";
    }
    ?>
</ul>

<div class="left title">
    <h1><a href="index.php"><span class="A">A</span><span class="P">P</span>edia</a></h1>
</div>

<div id="main">
    <?php

    if ($topic) {

        if (isset($_POST["vote_id"])) {
            if (isset($_SESSION["loggedin"]) && $_SESSION["loggedin"] === true) {
                $vote_id = $_POST["vote_id"];
                $handler->toggleVoteOn($vote_id, $_SESSION["id"]);
                header("location: topic.php?id=$topic_id#$vote_id");
            }
        }

        if (isset($_POST["text"])) {
            $text = htmlspecialchars($_POST["text"]);
            $handler->insertQuestion($text, $topic_id, $_SESSION["id"]);
            header("Refresh:0");
        }

        $name = $topic["name"];
        echo "<h1 class='topic_header'>$name</h1>";


        $questions = $handler->fetchQuestionsByTopic($topic_id);
        $questions = $handler->sortPostsByVotes($questions);
        $len_questions = sizeof($questions);
        $s = $len_questions == 1 ? "Question" : "Questions";
        echo "<p class='num_questions'>$len_questions $s</p>";


        echo "<div class='questions_container'>";

        echo "<div class='topic' id='$topic_id'>";
        if ($_SESSION["loggedin"] === true) {
            echo "<form onfocusout='focusOut(this)' onfocusin='focusIn(this)' class='submit block' method='post'>
<textarea name='text' placeholder='Ask a question' required></textarea>
<input type='submit'>
</form>";
        } else {
            echo "<p class='login_link'><a href='login.php'>Login to <span>Ask a question</span></a></p>";
        }
        echo "</div>";

        foreach ($questions as $q_arr) {
            $qid = $q_arr["id"];
            $text = $q_arr["text"];
            $numAnswers = count($handler->fetchAnswersToQuestion($qid));
            $answerStr = ($numAnswers==1)? "answer": "answers";

            $uid = $q_arr["post_user"];
            $post_user = $handler->fetchUserById($uid);
            $username = $post_user["username"];

            echo "<div class='question' id='$qid'>";
            echo "<div class='question_details'>";
            $handler->createVoteContainerHTML($q_arr);
            echo "<p class='answers_count'><span>$numAnswers</span> $answerStr</p>
<p class='text'><a href='question.php?id=$qid'>$text</a></p>
<p class='post_user'><a href='user.php?id=$uid'>$username</a></p>";
            echo "</div>";
            echo "</div>";
        }
        echo "</div>";
    } else {
        echo "Topic not found.";
    }

    ?>
</div>


<script src="script.js"></script>

</body>
</html>
