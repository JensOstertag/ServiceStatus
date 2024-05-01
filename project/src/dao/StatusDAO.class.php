<?php

class StatusDAO extends GenericObjectDAO {
    /**
     * Get a list of status objects to construct the status history
     * @param int $days The amount of days to look back
     * @return array An array for each service with a list of status objects of te past $days days
     */
    public function getStatusObjects(int $days = 60): array {
        $sql = "SELECT `Status`.*, `Service`.`priority` FROM ";
        $sql .= "`Status` INNER JOIN `Service` ON `Status`.`serviceId` = `Service`.`id` WHERE ";
        $sql .= "`latest` = 1 OR ";
        $sql .= "(`invalidatedAt` IS NOT NULL AND `invalidatedAt` >= NOW() - INTERVAL :days1 DAY) OR ";
        $sql .= "(`Service`.`created` >= NOW() - INTERVAL :days2 DAY) ";
        $sql .= "ORDER BY `priority` DESC, `serviceId`, `created` DESC";

        $stmt = Database::getConnection()->prepare($sql);
        $stmt->bindValue(":days1", $days, PDO::PARAM_INT);
        $stmt->bindValue(":days2", $days, PDO::PARAM_INT);
        $stmt->execute();

        $result = [];
        while($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            if(!array_key_exists($row["serviceId"], $result)) {
                $result[$row["serviceId"]] = [];
            }
            $statusObject = new Status();
            $statusObject->fromArray($row);
            $result[$row["serviceId"]][] = $statusObject;
        }

        return $result;
    }

    public function getHistoryData(int $days = 60) {
        $statusObjectsByService = $this->getStatusObjects($days);
        $services = Service::dao()->getObjects();

        $result = [];
        foreach($statusObjectsByService as $serviceId => $statusObjects) {
            $currentDay = new DateTime();
            $currentDay->modify("-{$days} days midnight");

            $targetDay = (new DateTimeImmutable())->modify("midnight");

            // Sort the status objects by creation date
            usort($statusObjects, function($a, $b) {
                return $a->getCreated() <=> $b->getCreated();
            });
            // The latest status object is always passed into the next day iteration, so that there is a defined last status
            // There are two cases:
            // If the first status object lies before the history start date, the object will be passed into the first iteration
            // If the first status object lies after the history start date, no object should be passed into the first iteration so that the status is UNKNOWN
            $latestStatusObject = null;
            if(count($statusObjects) > 0 && $statusObjects[0]->getCreated() < $currentDay) {
                $latestStatusObject = $statusObjects[0];
            }

            $historyData = [];
            while($currentDay <= $targetDay) {
                $endOfDay = clone $currentDay;
                $endOfDay->modify("23:59:59");

                // Find status objects for the current day
                $statusObjectsForDay = array_filter($statusObjects, function($statusObject) use ($currentDay, $endOfDay) {
                    return $statusObject->getCreated() >= $currentDay && $statusObject->getCreated() <= $endOfDay;
                });
                // Add the latest status object to the beginning of the array
                if($latestStatusObject !== null) {
                    array_unshift($statusObjectsForDay, $latestStatusObject);
                }

                // Find the worst status for the current day
                $status = ServiceStatus::UNKNOWN->value;
                foreach($statusObjectsForDay as $statusObject) {
                    if($statusObject->getOutageType() > $status) {
                        $status = $statusObject->getOutageType();
                    }
                }

                // Save the last status object of the current day to be passed as the first status object into the next iteration
                if(count($statusObjectsForDay) > 0) {
                    $latestStatusObject = $statusObjectsForDay[count($statusObjectsForDay) - 1];
                }

                // Find the last status of the current day
                $lastStatus = ServiceStatus::UNKNOWN->value;
                if($latestStatusObject !== null) {
                    $lastStatus = $latestStatusObject->getOutageType();
                }

                $historyData[DateFormatter::technicalDate($currentDay)] = [
                    "worstStatus" => ServiceStatus::from($status),
                    "lastStatus" => ServiceStatus::from($lastStatus),
                    "statusObjects" => $statusObjectsForDay
                ];

                $currentDay->modify("+1 day");
            }

            // Find the service
            $service = array_filter($services, function($service) use ($serviceId) {
                return $service->getId() === $serviceId;
            });
            if(count($service) !== 1) {
                continue;
            }

            // Find the last status of the last day
            $currentStatus = ServiceStatus::UNKNOWN;
            if(count($historyData) > 0) {
                $currentStatus = end($historyData)["lastStatus"];
            }

            $result[$serviceId] = [
                "service" => reset($service),
                "status" => $currentStatus,
                "history" => $historyData
            ];
        }

        return $result;
    }
}
