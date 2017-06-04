<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
  "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
  <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
  <title>Risky Jobs - Регистрация</title>
  <link rel="stylesheet" type="text/css" href="style.css" />
</head>
<body>
  <img src="riskyjobs_title.gif" alt="Risky Jobs" />
  <img src="riskyjobs_fireman.jpg" alt="Risky Jobs" style="float:right" />
  <h3>Risky Jobs - Регистрация</h3>

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
            // Имя не указано
            echo '<p class="error">Вы забыли ввести имя.</p>';
            $output_form = 'yes';
        }

        if (empty($last_name)) {
            // Фамилия не указана
            echo '<p class="error">Вы не ввели фамилию.</p>';
            $output_form = 'yes';
        }

        if (!preg_match('/^[a-zA-Z0-9][a-zA-Z0-9\._\-&!&=#]*@/', $email)) {
            // Электронный адрес неправильный
            echo '<p class="error">Вы ввели неверный адрес электронной почты. Имя пользователя таким быть не может</p>';
            $output_form = 'yes';
        } else {
        	//удаление всего, кроме имени домена
        	$domain = preg_replace('/^[a-zA-Z0-9][a-zA-Z0-9\._\-&!&=#]*@/', '', $email);

        	//Теперь проверяем, зарегистрирован ли домен с таким именем
        	if (!checkdnsrr($domain)) {
        		echo '<p class="error">Вы ввели неправильный адрес электронной почты. Такого домена не существует.</p>';
        		$output_form = 'yes';
        	}
        }




        if (!preg_match('/^\(?[2-9]\d{2}\)?[-\s]\d{3}-\d{4}$/', $phone)) {
            // Неправильный номер телефона
            echo '<p class="error">Вы ввели неправильный номер телефона.</p>';
            $output_form = 'yes';
        }

        if (empty($job)) {
            // Желаемая должность не заполнена
            echo '<p class="error">Вы забыли ввести желаемую должность.</p>';
            $output_form = 'yes';
        }

        if (empty($resume)) {
            // Резюме не заполнено
            echo '<p class="error">Вы забыли ввести свое резюме.</p>';
            $output_form = 'yes';
        }
    } else {
        $output_form = 'yes';

        $first_name = '';
        $last_name = '';
        $email = '';
        $phone = '';
        $job = '';
        $resume = '';
    }

    if ($output_form == 'yes') {

?>

<form method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>">
   <p>Зарегистрируйся на сайте Risky Jobs и размести свое резюме.</p>
  <table>
    <tr>
      <td><label for="firstname">Имя:</label></td>
      <td><input id="firstname" name="firstname" type="text" value="<?php echo $first_name; ?>"/></td></tr>
    <tr>
      <td><label for="lastname">Фамилия:</label></td>
      <td><input id="lastname" name="lastname" type="text" value="<?php echo $last_name; ?>"/></td></tr>
    <tr>
      <td><label for="email">Email:</label></td>
      <td><input id="email" name="email" type="text" value="<?php echo $email; ?>"/></td></tr>
    <tr>
      <td><label for="phone">Телефон:</label></td>
      <td><input id="phone" name="phone" type="text" value="<?php echo $phone; ?>"/></td></tr>
    <tr>
      <td><label for="job">Желаемая должность:</label></td>
      <td><input id="job" name="job" type="text" value="<?php echo $job; ?>"/></td>
  </tr>
  </table>
  <p>
    <label for="resume">Добавьте сюда свое резюме:</label><br />
    <textarea id="resume" name="resume" rows="4" cols="40"><?php echo $resume; ?></textarea><br />
    <input type="submit" name="submit" value="Submit" />
  </p>
</form>

<?php
    } elseif ($output_form == 'no') {
    echo '<p>' . $first_name . ' ' . $last_name . ', спасибо, что зарегистрировались на нашем сайте!</p>';
    $pattern = '/[\(\)\-\s]/';
    $replacement = '';
    $new_phone = preg_replace($pattern, $replacement, $phone);
    echo '<p>' . 'Номер вашего телефона зарегистрирован как ' . $new_phone . ' </p>';

    // code to insert data into the RiskyJobs database...
    }
?>

</body>
</html>
