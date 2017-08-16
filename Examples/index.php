<html>
<head>
    <title>Examples Page</title>
</head>
<body>
<ul>
    <?php

    $files = scandir(__DIR__);

    $dir = basename(__DIR__);

    foreach ($files as $file) {

        if (0 === stripos(substr($file, -4), '.php')) {
            ?>
            <li>
                <a href="<?php echo "/{$dir}/{$file}" ?>"><?php echo $file ?></a>
            </li>
            <?php
        }
    }

    ?>
</ul>
</body>
</html>