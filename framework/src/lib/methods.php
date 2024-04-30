<?php

/**
 * Outputs data on the website
 * @param $data mixed Data that should be displayed
 * @return void
 */
function output(mixed $data): void {
    echo htmlspecialchars($data);
}
