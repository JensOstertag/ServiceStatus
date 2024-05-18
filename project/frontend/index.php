<?php
use jensostertag\Templify\Templify;
Templify::setConfig("WEBSITE_TITLE", null);
Templify::include("header.php");
?>

<section class="container">
    <h1>Service Status</h1>

    <div class="summarization-status" data-status="<?php output($summaryStatus->name); ?>">
        <?php if($summaryStatus === ServiceStatus::OPERATIONAL): ?>
            All services are operational.
        <?php elseif($summaryStatus === ServiceStatus::PARTIAL_OUTAGE): ?>
            Some services are experiencing slight issues.
        <?php elseif($summaryStatus === ServiceStatus::FULL_OUTAGE): ?>
            Some services are experiencing severe issues.
        <?php else: ?>
            All services are operational.
        <?php endif; ?>
    </div>
</section>

<section class="container">
    <h2>Services</h2>

    <div class="services">
        <?php foreach($historyData as $historyDataObject): ?>
            <div class="service">
                <div class="service-details">
                    <p class="service-name nomargin">
                        <?php output($historyDataObject["service"]->getName()); ?>
                    </p>
                    <div class="current-status" data-status="<?php output($historyDataObject["status"]->name); ?>">
                        <?php if($historyDataObject["status"] === ServiceStatus::OPERATIONAL): ?>
                            Operational
                        <?php elseif($historyDataObject["status"] === ServiceStatus::PARTIAL_OUTAGE): ?>
                            Partial outage
                        <?php elseif($historyDataObject["status"] === ServiceStatus::FULL_OUTAGE): ?>
                            Full outage
                        <?php else: ?>
                            Unknown
                        <?php endif; ?>
                    </div>
                </div>
                <div class="service-status">
                    <div class="status-history">
                        <?php foreach($historyDataObject["history"] as $date => $history): ?>
                            <div class="status-history-element" data-status="<?php output($history["worstStatus"]->name); ?>">
                                <div class="tooltip">
                                    <p class="nomargin">
                                        <?php output(DateFormatter::visualDate(DateFormatter::parseTechnicalDate($date))); ?>
                                    </p>
                                    <?php foreach(Status::filterDowntimes($history["statusObjects"]) as $status): ?>
                                        <div class="status-history-incident">
                                            <p class="nomargin">
                                                <?php if($status->getIncident() !== null): ?>
                                                    <?php output($status->getIncident()->getName()); ?>
                                                <?php elseif($status->getOutageType() === ServiceStatus::FULL_OUTAGE->value): ?>
                                                    Full outage
                                                <?php elseif($status->getOutageType() === ServiceStatus::PARTIAL_OUTAGE->value): ?>
                                                    Partial outage
                                                <?php else: ?>
                                                    Unknown
                                                <?php endif; ?>
                                            </p>
                                            <p class="nomargin">
                                                <?php output(DateFormatter::visualTime($status->getStartDate())); ?>
                                                until
                                                <?php if($status->getEndDate() !== null): ?>
                                                    <?php output(DateFormatter::visualTime($status->getEndDate())); ?>
                                                    (<?php output($status->getDuration()); ?>)
                                                <?php else: ?>
                                                    now
                                                <?php endif; ?>
                                            </p>
                                        </div>
                                    <?php endforeach; ?>
                                    <?php if(count(Status::filterDowntimes($history["statusObjects"])) === 0): ?>
                                        <div class="status-history-incident">
                                            <p class="nomargin">
                                                No incidents were reported on this day.
                                            </p>
                                        </div>
                                    <?php endif; ?>
                                    <i></i>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>

                    <div class="status-legend">
                        <span>
                            60 days ago
                        </span>
                        <span>
                            Today
                        </span>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
</section>

<?php
Templify::include("footer.php");
?>
