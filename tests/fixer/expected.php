<?php

declare(strict_types=1);

final class Rules
{
    /**
     * @return $this
     */
    public function chain(): self
    {
        return $this;
    }

    /**
     * @inheritDoc
     */
    public function inherited(): void {}

    /**
     * @inheritDoc
     */
    public function completelyInherited(): void {}
}

if ($first) {
    runFirst();
} elseif ($second) {
    runSecond();
}
