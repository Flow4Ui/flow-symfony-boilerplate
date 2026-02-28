<?php

declare(strict_types=1);

namespace App\UI\Component\Widget;

use Flow\Attributes\Action;
use Flow\Attributes\Component;
use Flow\Attributes\Property;
use Flow\Component\AbstractComponent;

#[Component(
    name: 'CounterWidget',
    template: <<<'HTML'
<template>
    <div class="card counter-card">
        <h3>Counter Widget</h3>
        <p>Count: <strong>{{ count }}</strong></p>

        <div class="actions-row">
            <button class="btn" @click="decrement">-1</button>
            <button class="btn" @click="increment">+1</button>
            <button class="btn btn-secondary" @click="reset">Reset</button>
        </div>
    </div>
</template>
HTML,
)]
class CounterWidget extends AbstractComponent
{
    #[Property]
    public int $count = 0;

    #[Action]
    public function increment(): void
    {
        $this->count++;
    }

    #[Action]
    public function decrement(): void
    {
        $this->count--;
    }

    #[Action]
    public function reset(): void
    {
        $this->count = 0;
    }
}
