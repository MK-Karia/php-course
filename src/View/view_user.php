<head>
    <title>New user</title>
</head>
<body style="font-size: 30px">
    <h1>Пользователь:</h1>
    <label>First Name: </label><?= $firstName?><br/>
    <label>Last Name: </label><?= $lastName?><br/>
    <label>Middle Name: </label><?= $middleName?><br/>
    <label>Gender: </label><?= $gender?><br/>
    <label>Birth Date: </label><?= $birthDate?><br/>
    <label>Email: </label><?= $email?><br/>
    <label>Phone: </label><?= $phone?><br/>
    <label>Avatar path: 
        <?php 
            if ($avatarPath != null):
        ?>
    </label><img width=200px src="<?='/../../uploads/' . $avatarPath?>">
        <?php
            endif;
        ?>
    <br><br>
    <a href="update_user.php?id=<?= $userId ?>" ><button>Изменить</button></a>
    
    <a href="delete_user.php?id=<?= $userId ?>" ><button>Удалить</button></a>
</body>