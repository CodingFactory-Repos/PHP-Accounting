<?php
	require APP_ROOT . '/views/inc/head.php';
?>
<body>
    <main>
        <h1>Something has gone wrong | <?= $data['errorCode'] ?></h1>
        <h2><?= $data['headTitle'] ?></h2>
    </main>
</body>
