<?php

declare(strict_types=1);

namespace App\UI\Component;

use Flow\Attributes\Component;
use Flow\Component\AbstractComponent;

#[Component(
    name: 'Application',
    template: <<<'HTML'
<template>
    <div class="app-shell">
        <header class="app-header">
            <div class="container">
                <h1>Flow Symfony Boilerplate</h1>
                <p>Reusable starter with Flow components, user management, MySQL and Docker.</p>
            </div>
        </header>

        <main class="container app-main">
            <RouterView />
        </main>
    </div>
</template>
HTML,
)]
class Application extends AbstractComponent
{
}
