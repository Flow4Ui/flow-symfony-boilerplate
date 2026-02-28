<?php

declare(strict_types=1);

namespace App\UI\Component\Page;

use Flow\Attributes\Component;
use Flow\Attributes\Router;

#[Router(path: '/app/users', name: 'users')]
#[Component(
    name: 'UserListPage',
    template: <<<'HTML'
<template>
    <section>
        <h2 class="section-title">User Management</h2>
        <p class="section-subtitle">Simple server-driven CRUD powered by Flow actions.</p>

        <div class="nav-line">
            <RouterLink class="link" to="/app">Back to Dashboard</RouterLink>
        </div>

        <UserManagement />
    </section>
</template>
HTML,
)]
class UserListPage
{
}
