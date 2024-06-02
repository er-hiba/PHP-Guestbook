<?php
$file_path = 'guestbook.json';

function read_comments($file_path) {
    if (file_exists($file_path)) {
        return json_decode(file_get_contents($file_path), true);
    }
    return [];
}

function save_comments($file_path, $comments) {
    file_put_contents($file_path, json_encode($comments, JSON_PRETTY_PRINT));
}

$comments = read_comments($file_path);

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['submit_comment'])) {
        $name = htmlspecialchars($_POST['name']);
        $email = htmlspecialchars($_POST['mail']);
        $comment = htmlspecialchars($_POST['comments']);

        if (!empty($name) && !empty($email) && !empty($comment)) {
            $new_comment = [
                'name' => $name,
                'email' => $email,
                'comment' => $comment,
                'created_at' => date('Y-m-d H:i:s')
            ];

        array_unshift($comments, $new_comment);

        save_comments($file_path, $comments);
        } else {
            $empty_fields_message = "PLEASE FILL IN ALL FIELDS BEFORE CLICKING SUBMIT!";
        }
    }
}

$latest_comments = array_slice($comments, 0, 5);

?>

<html>

<head>
    <style>
        body{
            background-color: lavenderblush;
        }
        fieldset{
            width: 350px;
            font-weight: bold;
            padding: 15px;
            border: #69246a solid 2px;
        }
        form {
            margin-right: 100px;
        }
        textarea{
            margin-top: 8px;
            margin-bottom: 8px;
        }
        input{
            margin-bottom: 8px;
            width: 270px
        }
        label{
            margin-right: 7px;
        }
        main{
            margin: 30px 0 0 50px;
            padding: 20px;
            display: flex;
        }
        button{
            padding: 10px;
            background-color: #69246a;
            border: 0;
            border-radius: 12px;
            color: white;
            transition: box-shadow 0.3s;
        }

        button:hover{
            box-shadow: 0 8px 16px rgba(105, 36, 106, 08);
        }
    </style>
</head>

<body>
<main>
    <form method="post" action="">
        <?php if (isset($empty_fields_message)): ?>
            <p style="color:red; font-weight: bold; margin: 0 0 30px 0"><?php echo $empty_fields_message; ?></p>
        <?php endif; ?>
        <fieldset>
            <legend>Give your feedback on PHP 8!</legend>
            <label for="name">Name: </label>
            <input type="text" id="name" name="name">
            <br>
            <label for="mail">Email: </label>
            <input type="email" id="mail" name="mail">
            <br>
            <label for="comments">Your comment on the website:</label>
            <br>
            <textarea name="comments" id="comments" cols="44" rows="10"></textarea>
            <br>
            &nbsp; <button type="submit" name="submit_comment">Submit</button> &nbsp;
            <button type="submit" name="view_comments">View Comments</button>
        </fieldset>

    </form>

    <?php if (isset($_POST['view_comments'])): ?>
        <br>
        <?php if (empty($latest_comments)): ?>
        <p>No comments yet.</p>
        <?php else: ?>
            <table border="1" cellspacing="0" cellpadding="5">
                <?php foreach ($latest_comments as $comment): ?>
                    <tr>
                        <td style="width: 180px">From: <?php echo $comment['name']; ?></td>
                        <td style="width: 240px"><?php echo $comment['email']; ?></td>
                        <td style="width: 170px">On: <?php echo $comment['created_at']; ?></td>
                    </tr>
                    <tr>
                        <td colspan="3" style="word-wrap: break-word; max-width: 590px;"><?php echo $comment['comment']; ?></td>
                    </tr>
                <?php endforeach; ?>
            </table>
        <?php endif; ?>
    <?php endif; ?>
    
</main>
</body>

</html>
