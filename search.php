<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
    "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>Рискованная Работа - Поиск</title>
    <link rel="stylesheet" type="text/css" href="style.css" />
</head>
<body>
<img src="riskyjobs_title.gif" alt="Risky Jobs" />
<img src="riskyjobs_fireman.jpg" alt="Risky Jobs" style="float:right" />
<h3>Рискованная Работа - Поиск</h3>

<?php
// Эта функция создает строку поискового запроса, используя для этого критерии поиска и вид сортировки
function build_query($user_search, $sort) {
    $search_query = "SELECT * FROM riskyjobs";

    // Извлеките ключевые слова для поиска в массив
    $clean_search = str_replace(',', ' ', $user_search);
    $search_words = explode(' ', $clean_search);
    $final_search_words = array();
    if (count($search_words) > 0) {
        foreach ($search_words as $word) {
            if (!empty($word)) {
                $final_search_words[] = $word;
            }
        }
    }

    // Создайте предложение WHERE, используя все ключевые слова для поиска
    $where_list = array();
    if (count($final_search_words) > 0) {
        foreach($final_search_words as $word) {
            $where_list[] = "description LIKE '%$word%'";
        }
    }
    $where_clause = implode(' OR ', $where_list);

    // Добавьте ключевое слово WHERE в поисковый запрос
    if (!empty($where_clause)) {
        $search_query .= " WHERE $where_clause";
    }

    // Добавление к запросу выражение, определяющего порядок поиска
    switch ($sort) {
        // Сортировка по наименованию работ в восходящем алфавитном порядке
        case 1:
            $search_query .= " ORDER BY title";
            break;
        //  Сортировка по наименованию работ в нисходящем алфавитном порядке
        case 2:
            $search_query .= " ORDER BY title DESC";
            break;
        // Сортировка по наименованию штата в восходящем алфавитном порядке
        case 3:
            $search_query .= " ORDER BY state";
            break;
        // Сортировка по наименованию штата в нисходящем алфавитном порядке
        case 4:
            $search_query .= " ORDER BY state DESC";
            break;
        // Сортировка по дате регистрации объявления в восходящем порядке
        case 5:
            $search_query .= " ORDER BY date_posted";
            break;
        // Сортировка по дате регистрации объявления в нисходящем порядке
        case 6:
            $search_query .= " ORDER BY date_posted DESC";
            break;
        default:
            // Данные по порядку отсутствуют, по этому записи выводятся в том порядке, в котором они расположены в таблице
    }

    return $search_query;
}

// Эта функция создает ссылки на заголовки на основе указанного параметра сортировки
function generate_sort_links($user_search, $sort) {
    $sort_links = '';

    switch ($sort) {
        case 1:
            $sort_links .= '<td><a href = "' . $_SERVER['PHP_SELF'] . '?usersearch=' . $user_search . '&sort=2">Наименование работы</a></td><td>Описание</td>';
            $sort_links .= '<td><a href = "' . $_SERVER['PHP_SELF'] . '?usersearch=' . $user_search . '&sort=3">Штата</a></td>';
            $sort_links .= '<td><a href = "' . $_SERVER['PHP_SELF'] . '?usersearch=' . $user_search . '&sort=5">Даты</a></td>';
            break;
        case 3:
            $sort_links .= '<td><a href = "' . $_SERVER['PHP_SELF'] . '?usersearch=' . $user_search . '&sort=1">Наименование работы</a></td><td>Описание</td>';
            $sort_links .= '<td><a href = "' . $_SERVER['PHP_SELF'] . '?usersearch=' . $user_search . '&sort=4">Штата</a></td>';
            $sort_links .= '<td><a href = "' . $_SERVER['PHP_SELF'] . '?usersearch=' . $user_search . '&sort=3">Даты</a></td>';
            break;
        case 5:
            $sort_links .= '<td><a href = "' . $_SERVER['PHP_SELF'] . '?usersearch=' . $user_search . '&sort=1">Наименование работы</a></td><td>Описание</td>';
            $sort_links .= '<td><a href = "' . $_SERVER['PHP_SELF'] . '?usersearch=' . $user_search . '&sort=3">Штата</a></td>';
            $sort_links .= '<td><a href = "' . $_SERVER['PHP_SELF'] . '?usersearch=' . $user_search . '&sort=6">Даты</a></td>';
            break;
        default:
            $sort_links .= '<td><a href = "' . $_SERVER['PHP_SELF'] . '?usersearch=' . $user_search . '&sort=1">Наименование работы</a></td><td>Описание</td>';
            $sort_links .= '<td><a href = "' . $_SERVER['PHP_SELF'] . '?usersearch=' . $user_search . '&sort=3">Штата</a></td>';
            $sort_links .= '<td><a href = "' . $_SERVER['PHP_SELF'] . '?usersearch=' . $user_search . '&sort=5">Даты</a></td>';
    }

    return $sort_links;
}

// Эта функция создает навигационные геперссылки на странице результатов поиска, основываясь на значениях номера текущей странице и общего количества страниц
function generate_page_links($user_search, $sort, $cur_page, $num_pages) {
    $page_links = '';

    // Если это не первая страница - создание гиперссылки "предыдущая страница"
    if ($cur_page > 1) {
        $page_links .= '<a href="' . $_SERVER['PHP_SELF'] . '?usersearch=' . $user_search . '&sort=' . $sort . '&page=' . ($cur_page - 1) . '"><-</a> ';
    }
    else {
        $page_links .= '<- ';
    }

    // Прохождение в цикле всех страниц и создание гиперссылок, указывающих на конкретные страницы
    for ($i = 1; $i <= $num_pages; $i++) {
        if ($cur_page == $i) {
            $page_links .= ' ' . $i;
        }
        else {
            $page_links .= ' <a href="' . $_SERVER['PHP_SELF'] . '?usersearch=' . $user_search . '&sort=' . $sort . '&page=' . $i . '"> ' . $i . '</a>';
        }
    }

    // Если это не последняя страница - создание гиперссылки "следующая страница"
    if ($cur_page < $num_pages) {
        $page_links .= ' <a href="' . $_SERVER['PHP_SELF'] . '?usersearch=' . $user_search . '&sort=' . $sort . '&page=' . ($cur_page + 1) . '">-></a>';
    }
    else {
        $page_links .= ' ->';
    }

    return $page_links;
}

// Извлечение идентификатора вида сортировки и поисковой строки в виде URL с помощью суперглобального массива $_GET
$sort = $_GET['sort'];
$user_search = $_GET['usersearch'];

// Расчет данных, необходимых для разбиения текста результатов поиска на страницы
$cur_page = isset($_GET['page']) ? $_GET['page'] : 1;
$results_per_page = 5;  // количество объявлений на странице
$skip = (($cur_page - 1) * $results_per_page);

// Создание таблицы с результатом поиска
echo '<table border="0" cellpadding="2">';

// Вывод заголовков таблицы результатов поиска
echo '<tr class="heading">';
echo generate_sort_links($user_search, $sort);
echo '</tr>';

// Соединение с базой данных
require_once('connectvars.php');
$dbc = mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);

// Строка запроса для извлечения всех записей, соответствующих критериям поиска
$query = build_query($user_search, $sort);
$result = mysqli_query($dbc, $query);
$total = mysqli_num_rows($result);
$num_pages = ceil($total / $results_per_page);

// Строка запроса для извлечения записей только для текущей страницы
$query =  $query . " LIMIT $skip, $results_per_page";
$result = mysqli_query($dbc, $query);
while ($row = mysqli_fetch_array($result)) {
    echo '<tr class="results">';
    echo '<td valign="top" width="20%">' . $row['title'] . '</td>';
    echo '<td valign="top" width="50%">' . substr($row['description'], 0, 100) . '...</td>';
    echo '<td valign="top" width="10%">' . $row['state'] . '</td>';
    echo '<td valign="top" width="20%">' . substr($row['date_posted'], 0, 10) . '</td>';
    echo '</tr>';
}
echo '</table>';

// Если вся информация не помещается на 1 странице - создание навигационных гиперссылок
if ($num_pages > 1) {
    echo generate_page_links($user_search, $sort, $cur_page, $num_pages);
}

mysqli_close($dbc);
?>

</body>
</html>