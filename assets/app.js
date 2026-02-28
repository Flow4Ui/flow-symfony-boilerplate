import './styles/app.css';

import { createApp } from 'vue';
import { createFlow } from './flow';

const app = createApp({});

app.use(
    createFlow({
        ...FlowOptions,
    }),
);

app.mount('#app-main');
