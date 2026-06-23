import './bootstrap';

import Alpine from 'alpinejs';
import collapse from '@alpinejs/collapse';

Alpine.plugin(collapse);

Alpine.store('expandedRows', {})
window.Alpine = Alpine;

Alpine.start();
