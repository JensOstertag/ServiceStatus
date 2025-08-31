<?php

class Service extends \struktal\ORM\GenericObject {
    public ?string $name = null;
    public ?string $slug = null;
    public ?bool $visible = null;
    public ?int $order = null;

    public static function slugify(string $name): string {
        $slugified = strtolower(str_replace(["ä", "ö", "ü", "ß"], ["ae", "oe", "ue", "ss"], $name));
        return preg_replace("/[^a-zA-Z0-9]/", "-", $slugified);
    }

    public function preDelete(): void {
        // TODO
    }

    public function getName(): ?string {
        return $this->name;
    }

    public function setName(?string $name): void {
        $this->name = $name;
    }

    public function getSlug(): ?string {
        return $this->slug;
    }

    public function setSlug(?string $slug): void {
        $this->slug = $slug;
    }

    public function getVisible(): ?bool {
        return $this->visible;
    }

    public function setVisible(?bool $visible): void {
        $this->visible = $visible;
    }

    public function getOrder(): ?int {
        return $this->order;
    }

    public function setOrder(?int $order): void {
        $this->order = $order;
    }
}
