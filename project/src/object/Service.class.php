<?php

class Service extends GenericObject {
    public ?string $name = null;
    public ?string $url = null;
    public ?int $partialOutageThreshold = null;
    public ?int $fullOutageThreshold = null;
    public ?int $expectedResponseCode = null;

    public function getName(): ?string {
        return $this->name;
    }

    public function setName(?string $name): void {
        $this->name = $name;
    }

    public function getUrl(): ?string {
        return $this->url;
    }

    public function setUrl(?string $url): void {
        $this->url = $url;
    }

    public function getPartialOutageThreshold(): ?int {
        return $this->partialOutageThreshold;
    }

    public function setPartialOutageThreshold(?int $partialOutageThreshold): void {
        $this->partialOutageThreshold = $partialOutageThreshold;
    }

    public function getFullOutageThreshold(): ?int {
        return $this->fullOutageThreshold;
    }

    public function setFullOutageThreshold(?int $fullOutageThreshold): void {
        $this->fullOutageThreshold = $fullOutageThreshold;
    }

    public function getExpectedResponseCode(): ?int {
        return $this->expectedResponseCode;
    }

    public function setExpectedResponseCode(?int $expectedResponseCode): void {
        $this->expectedResponseCode = $expectedResponseCode;
    }
}
