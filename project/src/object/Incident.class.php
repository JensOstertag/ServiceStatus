<?php

class Incident extends GenericObject {
    public ?string $name = null;
    public ?string $description = null;
    public ?string $date = null;

    public function getName(): ?string {
        return $this->name;
    }

    public function setName(?string $name): void {
        $this->name = $name;
    }

    public function getDescription(): ?string {
        return $this->description;
    }

    public function setDescription(?string $description): void {
        $this->description = $description;
    }

    public function getDate(): ?string {
        return $this->date;
    }

    public function setDate(?string $date): void {
        $this->date = $date;
    }
}
