<?php
use jensostertag\Templify\Templify;
Templify::setConfig("WEBSITE_TITLE", null);
Templify::include("header.php");
?>

<section class="container">
    <h1>Service Status</h1>

    <div class="summarization-status" data-status="OPERATIONAL">
        All services are operational.
    </div>
</section>

<section class="container">
    <h2>Services</h2>

    <div class="services">
        <div class="service">
            <div class="service-details">
                <p class="service-name nomargin">
                    Service 1
                </p>
                <div class="current-status" data-status="OPERATIONAL">
                    Operational
                </div>
            </div>
            <div class="service-status">
                <div class="status-history">
                    <div class="status-history-element" data-status="OPERATIONAL">
                        <div class="tooltip">
                            <p>2024/05/01</p>
                            <i></i>
                        </div>
                    </div>
                    <div class="status-history-element" data-status="OPERATIONAL"></div>
                    <div class="status-history-element" data-status="OPERATIONAL"></div>
                    <div class="status-history-element" data-status="OPERATIONAL"></div>
                    <div class="status-history-element" data-status="OPERATIONAL"></div>
                    <div class="status-history-element" data-status="OPERATIONAL"></div>
                    <div class="status-history-element" data-status="OPERATIONAL"></div>
                    <div class="status-history-element" data-status="OPERATIONAL"></div>
                    <div class="status-history-element" data-status="OPERATIONAL"></div>
                    <div class="status-history-element" data-status="OPERATIONAL"></div>
                    <div class="status-history-element" data-status="OPERATIONAL"></div>
                    <div class="status-history-element" data-status="OPERATIONAL"></div>
                    <div class="status-history-element" data-status="OPERATIONAL"></div>
                    <div class="status-history-element" data-status="OPERATIONAL"></div>
                    <div class="status-history-element" data-status="OPERATIONAL"></div>
                    <div class="status-history-element" data-status="OPERATIONAL"></div>
                    <div class="status-history-element" data-status="OPERATIONAL"></div>
                    <div class="status-history-element" data-status="OPERATIONAL"></div>
                    <div class="status-history-element" data-status="OPERATIONAL"></div>
                    <div class="status-history-element" data-status="OPERATIONAL"></div>
                    <div class="status-history-element" data-status="OPERATIONAL"></div>
                    <div class="status-history-element" data-status="OPERATIONAL"></div>
                    <div class="status-history-element" data-status="OPERATIONAL"></div>
                    <div class="status-history-element" data-status="OPERATIONAL"></div>
                    <div class="status-history-element" data-status="OPERATIONAL"></div>
                    <div class="status-history-element" data-status="OPERATIONAL"></div>
                    <div class="status-history-element" data-status="OPERATIONAL"></div>
                    <div class="status-history-element" data-status="OPERATIONAL"></div>
                    <div class="status-history-element" data-status="OPERATIONAL"></div>
                    <div class="status-history-element" data-status="OPERATIONAL"></div>
                    <div class="status-history-element" data-status="OPERATIONAL"></div>
                    <div class="status-history-element" data-status="OPERATIONAL"></div>
                    <div class="status-history-element" data-status="OPERATIONAL"></div>
                    <div class="status-history-element" data-status="OPERATIONAL"></div>
                    <div class="status-history-element" data-status="OPERATIONAL"></div>
                    <div class="status-history-element" data-status="OPERATIONAL"></div>
                    <div class="status-history-element" data-status="OPERATIONAL"></div>
                    <div class="status-history-element" data-status="OPERATIONAL"></div>
                    <div class="status-history-element" data-status="OPERATIONAL"></div>
                    <div class="status-history-element" data-status="OPERATIONAL"></div>
                    <div class="status-history-element" data-status="OPERATIONAL"></div>
                    <div class="status-history-element" data-status="OPERATIONAL"></div>
                    <div class="status-history-element" data-status="OPERATIONAL"></div>
                    <div class="status-history-element" data-status="OPERATIONAL"></div>
                    <div class="status-history-element" data-status="OPERATIONAL"></div>
                    <div class="status-history-element" data-status="OPERATIONAL"></div>
                    <div class="status-history-element" data-status="OPERATIONAL"></div>
                    <div class="status-history-element" data-status="OPERATIONAL"></div>
                    <div class="status-history-element" data-status="OPERATIONAL"></div>
                    <div class="status-history-element" data-status="OPERATIONAL"></div>
                    <div class="status-history-element" data-status="OPERATIONAL"></div>
                    <div class="status-history-element" data-status="OPERATIONAL"></div>
                    <div class="status-history-element" data-status="OPERATIONAL"></div>
                    <div class="status-history-element" data-status="OPERATIONAL"></div>
                    <div class="status-history-element" data-status="OPERATIONAL"></div>
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
</section>

<?php
Templify::include("footer.php");
?>
