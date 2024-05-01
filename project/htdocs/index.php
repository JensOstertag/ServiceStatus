<?php

use jensostertag\Templify\Templify;

$historyData = Status::dao()->getHistoryData();
$summaryStatus = ServiceStatus::OPERATIONAL;
foreach($historyData as $historyDataObject) {
    if($historyDataObject["status"]->value > $summaryStatus->value) {
        $summaryStatus = $historyDataObject["status"];
    }

    if($summaryStatus === ServiceStatus::FULL_OUTAGE) {
        break;
    }
}

Templify::display("index.php", [
    "summaryStatus" => $summaryStatus,
    "historyData" => $historyData
]);
