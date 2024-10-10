<!DOCTYPE html>
<body>
<caption> <br> <strong> Ввод записей</strong>
    <form method="POST", enctype="multipart/form-data">
        <input type="text" name="name" placeholder="name"/>
        <input type="text" name="comment" placeholder="comment"/>
        <input type="file" name="f"/>
        <input type="submit"/>
    </form>
    <br>
    <caption> <br> <strong> Показ записей пользователя</strong>
        <form method="GET">
            <input type="text" name="name" placeholder="choose name"/>
            <input type="submit"/>
        </form>
        <div style="text-align: center;">
            <?php
            $db =new PDO('mysql:dbname=electrobook; host=localhost', 'root');
            if(isset($_FILES['f'])){
                $real_name = $_FILES['f']['name'];
                $f_name = "uploads/" . md5(microtime(true)) . substr(strrchr($_FILES['f']['name'], '.'), 0);
                if(empty($_FILES['f']['name']))
                    $f_name = '';
                //$comment = htmlspecialchars($_POST['comment']);
                //$name = htmlspecialchars($_POST['name']);
                $comment = $_POST['comment'];
                $name = $_POST['name'];
                move_uploaded_file($_FILES['f']['tmp_name'], $f_name);
                $sqlreq = "INSERT INTO table1 (name, comment, path_to_file) VALUES (:name, :comment, :f_name)";
                $q = $db->prepare($sqlreq);
                $res = $q->execute([':name' => $name, ':comment' => $comment, ':f_name' => $f_name]);
                //$res = $db->query("INSERT INTO table1 (name, comment, path_to_file) VALUES ('{$name}', '{$comment}', '{$f_name}')");
            } ?>
            <table border=\"1\">
                <caption> <br>Записная книжка </caption>
                <tr><th>Пользователь</th><th>Комментарий</th><th>Ссылка на файл</th></tr>
                <?php $res = $db->query("SELECT * FROM table1 order by time DESC");
                foreach ($res->fetchAll() as $row) {
                    $a = $row['path_to_file'];
                    $aa = substr(strrchr($a, '.'), 1);
                    if (!empty($a)) {
                        echo "<tr><td>{$row['name']}</td><td>{$row['comment']}</td><td><a href=\"{$row['path_to_file']}\">{$aa}</a></td></tr>";
                    }
                    else{
                        echo "<tr><td>{$row['name']}</td><td>{$row['comment']}</td><td>no file</td></tr>";
                    }
                }
                echo "</table>\n";
                //if(isset($_FILES['f']))
                //    move_uploaded_file($_FILES['f']['tmp_name'], "uploads/".$_FILES['f']['name']);
                if(isset($_GET['name'])){
                    $name2 = $_GET['name'];
                    $sqlreq2 = "SELECT comment, path_to_file FROM table1 WHERE name = :name order by time DESC";
                    $q2 = $db->prepare($sqlreq2);
                    $res2 = $q2->execute([':name' => $name2]);
                    echo"<table border=\"1\"> <caption> <br>Записная книжка для {$name2}</caption>";
                    foreach ($q2->fetchAll() as $row2) {
                        echo "<tr><td>{$row2['comment']}</td><td><a href=\"{$row['path_to_file']}\">attached file with format {$aa}</a></td></tr>";
                    }
                }
                ?>
        </div>
</body>