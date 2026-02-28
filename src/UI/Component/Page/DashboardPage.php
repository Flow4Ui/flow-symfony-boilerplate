<?php

declare(strict_types=1);

namespace App\UI\Component\Page;

use Flow\Attributes\Component;
use Flow\Attributes\Router;

#[Router(path: '/app', name: 'dashboard')]
#[Component(
    name: 'DashboardPage',
    template: <<<'HTML'
<template>
    <section>
        <h2 class="section-title">Dashboard</h2>
        <p class="section-subtitle">Start with a tiny Flow action component and jump into users CRUD.</p>

        <CounterWidget />

        <div class="card quick-links">
            <h3>Quick links</h3>
            <RouterLink class="link" to="/app/users">Open User Management</RouterLink>
        </div>
    </section>
</template>
HTML,
)]
class DashboardPage
{
}
