<?php
require_once('intr-sdk/autoload.php');

$dateFrom = $_GET['dateFrom'];
$dateTo = $_GET['dateTo'];


if($dateFrom && $dateTo){
    echo "Информация по пользователям и их успешным сделкам за период с " . $dateFrom . " по " . $dateTo;
}
else{
    if(!$dateFrom && !$dateTo){
        echo "Информация по пользователям и их успешным сделкам за все время";
    }
    else if(!$dateFrom){
        echo "Информация по пользователям и их успешным сделкам за все время до " . $dateTo;
    }
    else if(!$dateTo){
        echo "Информация по пользователям и их успешным сделкам за все время от " . $dateFrom;
    }
}

$dateFrom = strtotime($dateFrom);
$dateTo = strtotime($dateTo);

if($dateFrom > $dateTo){
    echo " \n";
    echo "Дата от больше, чем дата до. Информации нет.";
}

function getClients() {
    return [
        [
            'id' => 1,
            'name' => 'intrdev',
            'api' => 'key'
        ],
        [
            'id' => 2,
            'name' => 'artedegrass0',
            'api' =>  'jfnufehferhfjerfhgkh',
        ],
    ];
}


function getBudgetFromAllSuccessfullLeads($api_key, $dateFrom, $dateTo){

    Introvert\Configuration::getDefaultConfiguration()->setApiKey('key', $api_key);
    $api = new Introvert\ApiClient();
    $budgetSum = 0;
    $crm_user_id = [];
    $status = [142];
    $id = [];
    $ifmodif = "";
    $count = 10; // int | Количество запрашиваемых элементов
    $offset = 0; // int | смещение, относительно которого нужно вернуть элементы

    try {
        $result = $api->lead->getAll($crm_user_id, $status, $id, $ifmodif, $count, $offset); // делаем первый запрос на $count количество записей
        while($result['count'] == $count){ //проверяем, есть ли еще записи
            foreach($result['result'] as $lead){ //запускаем цикл foreach для каждой сделки в ответе на запрос
                if($lead['date_create'] >= $dateFrom && $lead['date_closed'] <= $dateTo){ // проверяем даты сделки 
                    $budgetSum = $budgetSum + $lead['price']; //прибавляем к бюджету
                }
            }
            $offset = $offset + $count; // смещаем элемент, относительно которого нужно вернуть элементы
            $result = $api->lead->getAll($crm_user_id, $status, $id, $count, $offset); //отправляем еще один запрос
        }
        return $budgetSum; // возвращаем общую сумму сделок
    } catch (Exception $e) {
        if($e->getMessage() == "[401] Передан неверный ключ"){
            return "Недействительный ключ";
        }
        else{
            echo 'Exception when calling LeadApi->getAll: ', $e->getMessage(), PHP_EOL;
        }
    }
}

function getBudgetForClients($dateFrom, $dateTo){
    $clients = getClients();
    $totalBudget = 0;
    $table = "<table><thead><tr><th>ID клиента</th><th>Имя клиента</th><th>Сумма успешных сделок за период</th></tr></thead>";
    foreach($clients as $client){
        $api_key = $client['api'];
        $budget = getBudgetFromAllSuccessfullLeads($api_key, $dateFrom, $dateTo);
        if(is_numeric($budget)){
            $totalBudget = $totalBudget + $budget;
        }
        $table .= "<tr><td>".$client['id']."</td><td>".$client['name']."</td><td>".$budget."</td></tr>";
    }
    $table .= "<tr><td>Сумма по всем клиентам</td><td colspan='2'>".$totalBudget."</td></tr></table>" ;
    echo $table;
}

getBudgetForClients($dateFrom, $dateTo);

include('index.php');

?>
