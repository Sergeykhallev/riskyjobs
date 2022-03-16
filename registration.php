<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
    "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>Рискованная Работа - Регистрация</title>
    <link rel="stylesheet" type="text/css" href="style.css" />
</head>
<body>
<img src="riskyjobs_title.gif" alt="Risky Jobs" />
<img src="riskyjobs_fireman.jpg" alt="Risky Jobs" style="float:right" />
<h3>Рискованная Работа - Регистрация</h3>

<?php
if (isset($_POST['submit'])) {
    $first_name = $_POST['firstname'];
    $last_name = $_POST['lastname'];
    $email = $_POST['email'];
    $phone = $_POST['phone'];
    $job = $_POST['job'];
    $resume = $_POST['resume'];
    $output_form = 'no';

    if (empty($first_name)) {
        // Переменная $first_name не отправлена
        echo '<p class="error">Вы забыли ввести свое имя.</p>';
        $output_form = 'yes';
    }

    if (empty($last_name)) {
        // Переменная $last_name не отправлена
        echo '<p class="error">Вы забыли ввести свою фамилию.</p>';
        $output_form = 'yes';
    }

    if (!preg_match('/^[a-zA-Z0-9][a-zA-Z0-9\._\-&!?=#]*@/', $email)) {
        // Значение переменной $email не соответствует регулярному выражению
        // Неправильная часть электронной почты под названием "Локальное имя почты"
        // расположенная справа от символа @

        echo '<p class="error">Вы ввели не правильный адрес электронной почты.</p>';
        $output_form = 'yes';
    }
    else {
        // Удаление адреса электронной почты почти всего, кроме имени домена, т. е. части, расположенной справа от символа @

        $domain = preg_replace('/^[a-zA-Z0-9][a-zA-Z0-9\._\-&!?=#]*@/', '', $email);

        // Теперь проверка того, зарегистрирован домен с таким именем или нет

        if (!checkdnsrr($domain)) {
            echo '<p class="error">Вы ввели не правильный адрес электронной почты.</p>';
            $output_form = 'yes';
        }
    }

    if (!preg_match('/^\(?[2-9]\d{2}\)?[-\s]\d{3}-\d{4}$/', $phone)) {
        // Переменная $phone не отправлена
        echo '<p class="error">Вы забыли ввести номер своего телефона.</p>';
        $output_form = 'yes';
    }

    if (empty($job)) {
        // Переменная $job не отправлена
        echo '<p class="error">Вы забыли ввести желаемую работу.</p>';
        $output_form = 'yes';
    }

    if (empty($resume)) {
        // Переменная $resume не отправлена
        echo '<p class="error">Вы забыли ввести свое резюме.</p>';
        $output_form = 'yes';
    }
}
else {
    $output_form = 'yes';
}

if ($output_form == 'yes') {
    ?>

    <form method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>">
        <p>Зарегистрируйтесь в Рискованной Работе и разместите свое резюме.</p>
        <table>
            <tr>
                <td><label for="firstname">Имя:</label></td>
                <td><input id="firstname" name="firstname" type="text"></td></tr>
            <tr>
                <td><label for="lastname">Фамилия:</label></td>
                <td><input id="lastname" name="lastname" type="text"></td></tr>
            <tr>
                <td><label for="email">Почта:</label></td>
                <td><input id="email" name="email" type="text"></td></tr>
            <tr>
                <td><label for="phone">Номер телефона:</label></td>
                <td><input id="phone" name="phone" type="text"></td></tr>
            <tr>
                <td><label for="job">Желаемая работа:</label></td>
                <td><input id="job" name="job" type="text"/></td>
            </tr>
        </table>
        <p>
            <label for="resume">Вставьте свое резюме сюда:</label><br />
            <textarea id="resume" name="resume" rows="4" cols="40"></textarea><br />
            <input type="submit" name="submit" value="Submit" />
        </p>
    </form>

    <?php
}
else if ($output_form == 'no') {
    echo '<p>' . $first_name . ' ' . $last_name . ', спасибо за регистрацию на Рискованной Работе!<br />';
    $pattern = '/[\(\)\-\s]/';
    $replacement = '';
    $new_phone = preg_replace($pattern, $replacement, $phone);
    echo 'Ваш номер был зарегистрирован как ' . $new_phone . '.</p>';

    // код для вставки данных в базу данных рискованной работы...
}
?>

</body>
</html>