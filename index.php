<?php
$search_query = "SELECT * FROM riskyjobs";
$where_clause = '';
$where_list = array();
$user_search = "Матадор победитель быков";
$search_words = explode(' ', $user_search);

echo '<pre>';
print_r($search_words);
echo '</pre>';
echo '<hr>';

// foreach ($search_words as $word) {
// 	$where_clause .= " description LIKE '%$word%' OR ";
// }

// echo '<pre>';
// print_r($where_clause);
// echo '</pre>';
// echo '<hr>';

// $where_clause = "description LIKE " . implode(' OR description LIKE ', $search_words);

// echo '<pre>';
// print_r($where_clause);
// echo '</pre>';
// echo '<hr>';


// if (!empty($where_clause)) {
// 	$search_query .= " WHERE $where_clause";
// }

// echo '<pre>';
// print_r($search_query);
// echo '</pre>';
// echo '<hr>';
// echo '<hr>';



foreach ($search_words as $word) {
	$where_list[] = "description LIKE '%$word%'";
}

$where_clause = implode(' OR ', $where_list);

if (!empty($where_clause)) {
	$search_query .= " WHERE $where_clause";
}

echo '<pre>';
print_r($search_query);
echo '</pre>';
echo '<hr>';


?>