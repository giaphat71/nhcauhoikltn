<?
$output = "";
$ajax = $_POST['ajax'] ?? "";
try {

} catch (\Exception $e) {
    $output = $e;
}
echo $output;
?>