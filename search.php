<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
  "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>Risky Jobs - Search</title>
    <link rel="stylesheet" type="text/css" href="style.css" />
</head>
<body>
    <img src="riskyjobs_title.gif" alt="Risky Jobs" />
    <img src="riskyjobs_fireman.jpg" alt="Risky Jobs" style="float:right" />
    <h3>Risky Jobs - Search Results</h3>

<?php
    // Извлечение параметров запроса методом GET

    //$sort = $_GET['sort'];
	function replace_commas($str) {
		$new_str = str_replace(',', ' ', $str);
		return $new_str;
	}
    
	function build_query($user_search, $sort) {
		$search_query = "SELECT * FROM riskyjobs";

		//Извлечение критериев поиска в массив
	    $clean_search = str_replace(',', ' ', $user_search);
		$search_words = explode(' ', $clean_search);
	    $final_search_words = array();
	    if (count($search_words) > 0 ) {
	        foreach ($search_words as $word) {
	            if (!empty($word)) {
	                $final_search_words[] = $word;
	            }
	        }
	    }

	    //Создание условного выражения WHERE с использованием всех критериев поиска
	    $where_list = array();
	    if (count($final_search_words) > 0) {
	        foreach ($final_search_words as $word) {
	            $where_list[] = "description LIKE '%$word%'";
	        }
	    }
	    $where_clause = implode(' OR ', $where_list);

	    //Добавление к запросу условного выражения WHERE
	    if (!empty($where_clause)) {
	        $search_query .= " WHERE $where_clause";
	    }

	    //Добавление к запросу выражения, определяющего порядок сортировки
	    switch ($sort) {
	    	//Сортировка по наименованию работ в восходящем алфавитном порядке от А до Я
	    	case '1':
	    		$search_query .= " ORDER BY title";
	    		break;
	    	//Сортировка по наименованию работ в нисходящем алфавитном порядке от Я до А
	    	case '2':
	    		$search_query .= " ORDER BY title DESC";
	    		break;
	    	//Сортировка по наименованию штата в восходящем алфавитном порядке от А до Я
	    	case '3':
	    		$search_query .= " ORDER BY state";
	    		break;
	    	//Сортировка по наименованию штата в нисходящем алфавитном порядке от Я до А
	    	case '4':
	    		$search_query .= " ORDER BY state DESC";
	    		break;
	    	//Сортировка по дате размещения в восходящем порядке
	    	case '5':
	    		$search_query .= " ORDER BY date_posted";
	    		break;
	    	//Сортировка по дате размещения в нисходящем порядке
	    	case '6':
	    		$search_query .= " ORDER BY date_posted DESC";
	    		break;
	    	default:
	    		//Данные по порядку сортировки отсутствуют,
	    		//поэтому записи выводятся в том порядке, в котором они расположены в таблице
	    		break;
	    }
    
	    return($search_query);
	}

	function generate_sort_links($user_search, $sort) {
		$sort_links = '';
		switch ($sort) {
			case '1':
				$sort_links .= '<td><a href = "' . $_SERVER['PHP_SELF'] . '?usersearch=' . $user_search . '&sort=2">Наименование работы</a></td><td>Описание</td>';
				$sort_links .= '<td><a href = "' . $_SERVER['PHP_SELF'] . '?usersearch=' . $user_search . '&sort=3">Штат</a></td>';
				$sort_links .= '<td><a href = "' . $_SERVER['PHP_SELF'] . '?usersearch=' . $user_search . '&sort=5">Дата</a></td>';
				break;
			case '3':
				$sort_links .= '<td><a href = "' . $_SERVER['PHP_SELF'] . '?usersearch=' . $user_search . '&sort=1">Наименование работы</a></td><td>Описание</td>';
				$sort_links .= '<td><a href = "' . $_SERVER['PHP_SELF'] . '?usersearch=' . $user_search . '&sort=4">Штат</a></td>';
				$sort_links .= '<td><a href = "' . $_SERVER['PHP_SELF'] . '?usersearch=' . $user_search . '&sort=6">Дата</a></td>';
				break;
			case '5':
				$sort_links .= '<td><a href = "' . $_SERVER['PHP_SELF'] . '?usersearch=' . $user_search . '&sort=1">Наименование работы</a></td><td>Описание</td>';
				$sort_links .= '<td><a href = "' . $_SERVER['PHP_SELF'] . '?usersearch=' . $user_search . '&sort=3">Штат</a></td>';
				$sort_links .= '<td><a href = "' . $_SERVER['PHP_SELF'] . '?usersearch=' . $user_search . '&sort=6">Дата</a></td>';
				break;
			
			default:
				$sort_links .= '<td><a href = "' . $_SERVER['PHP_SELF'] . '?usersearch=' . $user_search . '&sort=1">Наименование работы</a></td><td>Описание</td>';
				$sort_links .= '<td><a href = "' . $_SERVER['PHP_SELF'] . '?usersearch=' . $user_search . '&sort=3">Штат</a></td>';
				$sort_links .= '<td><a href = "' . $_SERVER['PHP_SELF'] . '?usersearch=' . $user_search . '&sort=5">Дата</a></td>';
		}
		return $sort_links;
	}

	
	//Эта функция создает навигационные гиперссылки на странице результатов поиска, 
	//основываясь на значениях номера текущей страницы и общего количества страниц
	function generate_page_links ($user_search, $sort, $cur_page, $num_pages) {
		$page_links = '';
		//Если это не первая страница - создание гиперссылки "предыдущая страница" (<<)
		if ($cur_page > 1) {
			$page_links .= '<a href="' . $_SERVER['PHP_SELF'] . 
			'?usersearch=' . $user_search . '&sort=' . $sort . '&page' . ($cur_page - 1) . '"><-</a>';
		} else {
			$page_links .= '<-';
		}

		//Прохождение в цикле всех страниц и создание гиперссылок,
		//указыващих на конкретные страницы
		for ($i = 1; $i <= $num_pages; $i++) {
			if ($cur_page == $i) {
				$page_links .= ' ' . $i;
			} else {
				$page_links .= '<a href="' . $_SERVER['PHP_SELF'] . '?usersearch=' . 
				$user_search . '&sort=' . $sort . '&page=' . $i . '"> ' . $i . '</a>';
			}
		}

		//Если это не последняя страница - создание гиперссылки "следующая страница" (>>)
		if ($cur_page < $num_pages) {
			$page_links .= '<a href="' . $_SERVER['PHP_SELF'] . 
			'?usersearch=' . $user_search . '&sort=' . $sort . '&page' . ($cur_page + 1) . '">-></a>';
		} else {
			$page_links .=  ' ->';
		}
		return $page_links;
	}
    
    require_once('connectvars.php');
    
    $user_search = $_GET['usersearch'];
    $sort = $_GET['sort'];

    //расчет данных для разбиения текста результатов поиска на страницы
    $cur_page = isset($_GET['page'])?$_GET['page']:1;
    $results_per_page = 7; #количество объявлений на странице
    $skip = (($cur_page-1) * $results_per_page); #вычисление номера первой записи, с которой будет начат вывод обЪявлений на этой странице

	// Соединение с БД
	$dbc = mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);
	$query = build_query($user_search, $sort);
    $result = mysqli_query($dbc, $query);
    $total = mysqli_num_rows($result); #вычисляем количество строк
    $num_pages = ceil($total/$results_per_page); #вычисляем общее количество страниц, которое потребуется для выведения на экран содержимого БД

    //строка запроса для извлечения записей только для текущей страницы
    $query = $query . " LIMIT $skip, $results_per_page";

    //запрос к базе, который извлекает из только нужный на данной странице кусок записей
    $result = mysqli_query($dbc, $query);

    // Начало генерации таблицы результатов
    echo '<table border="0" cellpadding="2">';

    // Создаем заголовки
    echo '<tr class="heading">';
    echo generate_sort_links($user_search, $sort);
    echo '</tr>';


    // Запрос к БД
    // $query = $search_query;
    // $result = mysqli_query($dbc, $query);
    while ($row = mysqli_fetch_array($result)) {
        echo '<tr class="results">';
        echo '<td valign="top" width="20%">' . $row['title'] . '</td>';
        echo '<td valign="top" width="50%">' . substr($row['description'], 0, 100) . '...</td>';
        echo '<td valign="top" width="10%">' . $row['state'] . '</td>';
        echo '<td valign="top" width="20%">' . substr($row['date_posted'], 0, 10) . '</td>';
        echo '</tr>';
    } 
    echo '</table>';

    //Если вся информация не помещается на одной странице - создаем навигационные гиперссылки
    if ($num_pages > 1) {
    	echo generate_page_links($user_search, $sort, $cur_page, $num_pages);
    }


    mysqli_close($dbc);
?>

</body>
</html>
