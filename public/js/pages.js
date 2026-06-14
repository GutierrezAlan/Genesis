/**
 * PÁGINAS
 * Lógica para renderizar cada página
 */

class Pages {
    /**
     * Página de inicio - Lista de productos
     */
    static async renderHome() {
        if (!router.isOnPage('home')) return;

        const container = document.getElementById('productsGrid');
        Components.showLoading(container);

        try {
            const response = await api.getProducts();
            const products = response.data || response;

            container.innerHTML = products
                .map(product => Components.renderProductCard(product))
                .join('');
        } catch (error) {
            Components.showError(container, 'Error al cargar productos');
            console.error(error);
        }
    }

    /**
     * Página de detalle de producto
     */
    static async renderProductDetail(params) {
        if (!router.isOnPage('productDetail')) return;

        const container = document.getElementById('productDetailContainer');
        Components.showLoading(container);

        try {
            const response = await api.getProductDetail(params.id);
            const product = response.data || response;

            const inStock = product.stock > 0;

            container.innerHTML = `
                <div class="product-detail">
                    <div class="product-detail-image">
                        ${product.image_url ? `<img src="${product.image_url}" alt="${product.titulo}">` : '📦'}
                    </div>
                    <div class="product-detail-info">
                        <h1>${Utils.escapeHTML(product.titulo)}</h1>
                        <p class="category">${product.category || 'Sin categoría'}</p>
                        <p class="description">${Utils.escapeHTML(product.description || 'Sin descripción')}</p>
                        <p class="price">${Utils.formatMoney(product.price)}</p>
                        <p class="stock ${inStock ? 'in-stock' : 'out-of-stock'}">
                            ${inStock ? `En stock: ${product.stock} unidades` : 'Agotado'}
                        </p>
                        ${inStock ? `
                            <div class="product-detail-info quantity-selector">
                                <button onclick="document.getElementById('quantityInput').value = Math.max(1, parseInt(document.getElementById('quantityInput').value) - 1)">-</button>
                                <input type="number" id="quantityInput" min="1" max="${product.stock}" value="1">
                                <button onclick="document.getElementById('quantityInput').value = Math.min(${product.stock}, parseInt(document.getElementById('quantityInput').value) + 1)">+</button>
                            </div>
                            <button class="btn btn-primary btn-lg" onclick="app.addToCartFromDetail(${product.id})">
                                Agregar al Carrito
                            </button>
                        ` : `
                            <button class="btn btn-secondary" disabled>No disponible</button>
                        `}
                    </div>
                </div>
            `;
        } catch (error) {
            Components.showError(container, 'Error al cargar producto');
            console.error(error);
        }
    }

    /**
     * Página de carrito
     */
    static renderCart() {
        if (!router.isOnPage('cart')) return;

        const container = document.getElementById('cartContainer');
        const items = cart.getItems();

        if (items.length === 0) {
            container.innerHTML = `
                <div class="empty-cart">
                    <h2>Tu carrito está vacío</h2>
                    <p>¡Agrega productos para empezar a comprar!</p>
                    <a href="#/" class="btn btn-primary">Ir a Comprar</a>
                </div>
            `;
            return;
        }

        container.innerHTML = `
            <div class="carrito-content">
                <div class="carrito-items">
                    ${items.map(item => Components.renderCartItem(item)).join('')}
                </div>
                <div class="carrito-summary">
                    <h2>Resumen</h2>
                    <div class="total">
                        <strong>Total:</strong>
                        <span>${Utils.formatMoney(cart.getTotalPrice())}</span>
                    </div>
                    <div class="carrito-actions">
                        ${auth.isAuthenticated()
                            ? `<a href="#/checkout" class="btn btn-primary">Proceder al Checkout</a>`
                            : `<a href="#/login" class="btn btn-primary">Iniciar Sesión para Comprar</a>`
                        }
                        <button onclick="cart.clear(); app.pages.renderCart()" class="btn btn-secondary">
                            Vaciar Carrito
                        </button>
                    </div>
                    <a href="#/" class="btn btn-secondary">Continuar Comprando</a>
                </div>
            </div>
        `;
    }

    /**
     * Página de checkout
     */
    static renderCheckout() {
        if (!router.isOnPage('checkout')) return;

        const container = document.getElementById('checkoutContainer');
        const items = cart.getItems();

        if (!auth.isAuthenticated()) {
            container.innerHTML = `
                <div class="no-auth">
                    <h2>Debes iniciar sesión</h2>
                    <p>Para proceder con la compra, inicia sesión primero</p>
                    <a href="#/login" class="btn btn-primary">Ir al Login</a>
                </div>
            `;
            return;
        }

        if (items.length === 0) {
            container.innerHTML = `
                <div class="empty-cart">
                    <h2>Tu carrito está vacío</h2>
                    <p>No hay productos para procesar</p>
                    <a href="#/" class="btn btn-primary">Continuar comprando</a>
                </div>
            `;
            return;
        }

        container.innerHTML = `
            <div class="checkout-container">
                <div class="checkout-summary">
                    <h2>Resumen de Compra</h2>
                    <div class="checkout-items">
                        ${items.map(item => `
                            <div class="checkout-item">
                                <span>${Utils.escapeHTML(item.titulo)} x${item.cantidad}</span>
                                <span>${Utils.formatMoney(item.price * item.cantidad)}</span>
                            </div>
                        `).join('')}
                    </div>
                    <div class="checkout-total">
                        <span>Total a pagar:</span>
                        <span>${Utils.formatMoney(cart.getTotalPrice())}</span>
                    </div>
                </div>

                <div class="auth-container" style="max-width: 600px; margin: 20px auto;">
                    <h3>Confirmar Compra</h3>
                    <p>Cliente: <strong>${auth.getUserName()}</strong></p>
                    <div class="checkout-actions">
                        <button class="btn btn-primary" onclick="app.processOrder()">
                            Confirmar Orden
                        </button>
                        <a href="#/carrito" class="btn btn-secondary">Volver al Carrito</a>
                    </div>
                </div>
            </div>
        `;
    }

    /**
     * Página de mis órdenes
     */
    static async renderMyOrders() {
        if (!router.isOnPage('orders')) return;

        if (!auth.isAuthenticated()) {
            document.getElementById('ordersContainer').innerHTML = `
                <div class="no-auth">
                    <h2>Debes iniciar sesión</h2>
                    <a href="#/login" class="btn btn-primary">Ir al Login</a>
                </div>
            `;
            return;
        }

        const container = document.getElementById('ordersContainer');
        Components.showLoading(container);

        try {
            const response = await api.getUserOrders();
            const orders = response.data || response;

            if (!orders || orders.length === 0) {
                container.innerHTML = `
                    <div class="no-orders">
                        <h2>No tienes órdenes</h2>
                        <p>¡Comienza a comprar ahora!</p>
                        <a href="#/" class="btn btn-primary">Ver Productos</a>
                    </div>
                `;
                return;
            }

            container.innerHTML = `
                <div class="orders-list">
                    ${orders.map(order => Components.renderOrderCard(order)).join('')}
                </div>
            `;
        } catch (error) {
            Components.showError(container, 'Error al cargar órdenes');
            console.error(error);
        }
    }

    /**
     * Página de login
     */
    static renderLogin() {
        if (!router.isOnPage('login')) return;

        if (auth.isAuthenticated()) {
            router.navigate('/');
            return;
        }

        const form = document.getElementById('loginForm');
        form.onsubmit = async (e) => {
            e.preventDefault();

            const email = document.getElementById('email').value;
            const password = document.getElementById('password').value;
            const remember = document.getElementById('remember').checked;

            const result = await auth.login(email, password, remember);

            if (result.success) {
                Utils.showNotification('¡Bienvenido!', 'success');
                Components.renderNavbar();
                router.navigate('/');
            } else {
                Utils.showNotification(result.error || 'Error en login', 'error');
            }
        };
    }

    /**
     * Página de registro
     */
    static renderRegister() {
        if (!router.isOnPage('register')) return;

        if (auth.isAuthenticated()) {
            router.navigate('/');
            return;
        }

        const form = document.getElementById('registerForm');
        form.onsubmit = async (e) => {
            e.preventDefault();

            const password = document.getElementById('regPassword').value;
            const confirmPassword = document.getElementById('confirmPassword').value;

            if (password !== confirmPassword) {
                Utils.showNotification('Las contraseñas no coinciden', 'error');
                return;
            }
            const first_name = document.getElementById('firstName').value;
            const last_name = document.getElementById('lastName').value;

            const userData = {
                name: first_name + ' ' + last_name,
                first_name,
                last_name,
                last_name: document.getElementById('lastName').value,
                email: document.getElementById('regEmail').value,
                mobile: document.getElementById('regMobile').value,
                password,
                password_confirmation: confirmPassword,
            };

            const result = await auth.register(userData);

            if (result.success) {
                Utils.showNotification('¡Registro exitoso! Iniciando sesión...', 'success');
                Components.renderNavbar();
                setTimeout(() => router.navigate('/'), 1000);
            } else {
                Utils.showNotification(result.error || 'Error en registro', 'error');
            }
        };
    }

    /* =========================================================
       PANEL DE ADMINISTRACIÓN
    ========================================================= */

    /**
     * Página de admin — estructura con tabs
     */
    static async renderAdmin() {
        if (!router.isOnPage('admin')) return;

        if (!auth.isAuthenticated() || !auth.isAdmin()) {
            router.navigate('/');
            return;
        }

        const container = document.getElementById('adminContainer');

        container.innerHTML = `
            <div class="admin-tabs">
                <button class="admin-tab active" data-tab="products" onclick="Pages.switchAdminTab('products')">
                    🛍️ Productos
                </button>
                <button class="admin-tab" data-tab="orders" onclick="Pages.switchAdminTab('orders')">
                    📦 Órdenes
                </button>
            </div>

            <!-- Sección: Productos -->
            <div id="adminTabProducts" class="admin-tab-content active">
                <div class="admin-section">
                    <div class="admin-section-header">
                        <div>
                            <h2>Gestión de Productos</h2>
                            <p class="admin-section-subtitle">Administrá el catálogo de la tienda</p>
                        </div>
                        <button class="btn btn-primary" onclick="app.showProductForm()">
                            + Nuevo Producto
                        </button>
                    </div>
                    <div id="productsList"></div>
                </div>
            </div>

            <!-- Sección: Órdenes -->
            <div id="adminTabOrders" class="admin-tab-content">
                <div class="admin-section">
                    <div class="admin-section-header">
                        <div>
                            <h2>Gestión de Órdenes</h2>
                            <p class="admin-section-subtitle">Revisá y actualizá el estado de los pedidos</p>
                        </div>
                        <div class="admin-orders-filters">
                            <button class="admin-filter-btn active" data-status="all"    onclick="Pages.filterAdminOrders('all')">Todas</button>
                            <button class="admin-filter-btn"        data-status="pending"    onclick="Pages.filterAdminOrders('pending')">Pendientes</button>
                            <button class="admin-filter-btn"        data-status="completed"  onclick="Pages.filterAdminOrders('completed')">Completadas</button>
                        </div>
                    </div>
                    <div id="ordersList"></div>
                </div>
            </div>
        `;

        // Cargar ambas secciones en paralelo
        await Promise.all([
            this.loadAdminProducts(),
            this.loadAdminOrders(),
        ]);
    }

    /**
     * Cambiar tab activo
     */
    static switchAdminTab(tab) {
        // Tabs botones
        document.querySelectorAll('.admin-tab').forEach(btn => {
            btn.classList.toggle('active', btn.dataset.tab === tab);
        });
        // Contenido
        document.querySelectorAll('.admin-tab-content').forEach(el => {
            el.classList.remove('active');
        });
        document.getElementById(`adminTab${tab.charAt(0).toUpperCase() + tab.slice(1)}`).classList.add('active');
    }

    /**
     * Cargar productos para admin (cards)
     */
    static async loadAdminProducts() {
        const container = document.getElementById('productsList');
        Components.showLoading(container);

        try {
            const response = await api.getProducts();
            const products = response.data || response;

            if (!products || products.length === 0) {
                container.innerHTML = `
                    <div class="admin-empty">
                        <p>No hay productos todavía.</p>
                        <button class="btn btn-primary" onclick="app.showProductForm()">Crear primer producto</button>
                    </div>
                `;
                return;
            }

            container.innerHTML = `
                <div class="admin-products-grid">
                    ${products.map(p => Components.renderAdminProductCard(p, [
                        { type: 'secondary', label: '✏️ Editar',   onClick: (p) => `app.showProductForm(${p.id})` },
                        { type: 'danger',    label: '🗑 Eliminar', onClick: (p) => `app.deleteProduct(${p.id})` },
                    ])).join('')}
                </div>
            `;
        } catch (error) {
            Components.showError(container, 'Error al cargar productos');
        }
    }

    /**
     * Cargar todas las órdenes para admin
     */
    static async loadAdminOrders() {
        const container = document.getElementById('ordersList');
        Components.showLoading(container);

        try {
            const response = await api.getAllOrders(); // endpoint admin
            const orders = response.data || response;

            // Guardar en el DOM para poder filtrar sin refetch
            container.dataset.orders = JSON.stringify(orders);

            Pages._renderAdminOrdersList(container, orders);
        } catch (error) {
            Components.showError(container, 'Error al cargar órdenes');
        }
    }

    /**
     * Renderizar lista de órdenes admin (con acciones de estado)
     */
    static _renderAdminOrdersList(container, orders) {
        if (!orders || orders.length === 0) {
            container.innerHTML = `
                <div class="admin-empty">
                    <p>No hay órdenes registradas.</p>
                </div>
            `;
            return;
        }

        container.innerHTML = `
            <div class="admin-orders-list">
                ${orders.map(order => Pages._renderAdminOrderCard(order)).join('')}
            </div>
        `;
    }

    /**
     * Card de orden para el panel admin
     */
    static _renderAdminOrderCard(order) {
        const items = Array.isArray(order.items) ? order.items : JSON.parse(order.items || '[]');
        const Status = order.status ;

        return `
            <div class="admin-order-card" id="admin-order-${order.id}">
                <div class="admin-order-header">
                    <div class="admin-order-meta">
                        <span class="admin-order-id">Orden #${order.id}</span>
                        <span class="order-status ${order.status}">${order.status}</span>
                    </div>
                    <div class="admin-order-info">
                        <span class="admin-order-customer">👤 ${Utils.escapeHTML(String(order.user_name || order.user_id || 'Cliente'))}</span>
                        <span class="admin-order-date">🕐 ${Utils.formatDate(order.created_at)}</span>
                    </div>
                </div>

                <div class="admin-order-items">
                    ${items.map(item => `
                        <div class="admin-order-item">
                            <span class="admin-order-item-name">${Utils.escapeHTML(String(item.titulo || 'Producto'))} <span class="admin-order-item-qty">x${item.cantidad || 1}</span></span>
                            <span class="admin-order-item-price">${Utils.formatMoney(item.price * (item.cantidad || 1))}</span>
                        </div>
                    `).join('')}
                </div>

                <div class="admin-order-footer">
                    <span class="admin-order-total">Total: <strong>${Utils.formatMoney(order.total_price)}</strong></span>
                    <div class="admin-order-actions">
                        ${Status == 'pending' ? `
                            <button class="btn btn-success" onclick="app.updateOrderStatus(${order.id}, 'completed')">
                                ✓ Marcar completada
                            </button>
                        ` : `
                            <button class="btn btn-secondary" onclick="app.updateOrderStatus(${order.id}, 'pending')">
                                ↩ Marcar pendiente
                            </button>
                        `}
                        ${Status == 'cancelled' ?`
                        <button class="btn btn-danger" onclick="app.updateOrderStatus(${order.id}, 'deleted')">
                        🗑 Eliminar
                        </button>
                        `:`
                        <button class="btn btn-danger" onclick="app.updateOrderStatus(${order.id}, 'cancelled')">
                        🚫 Cancelada
                        </button>
                        `}
                    </div>
                </div>
            </div>
        `;
    }

    /**
     * Filtrar órdenes por estado (sin refetch)
     */
    static filterAdminOrders(status) {
        // Actualizar botones de filtro
        document.querySelectorAll('.admin-filter-btn').forEach(btn => {
            btn.classList.toggle('active', btn.dataset.status === status);
        });

        const container = document.getElementById('ordersList');
        let orders = JSON.parse(container.dataset.orders || '[]');

        if (status !== 'all') {
            orders = orders.filter(o => o.status === status);
        }

        Pages._renderAdminOrdersList(container, orders);

        // Re-guardar dataset para filtros subsecuentes
        const full = document.getElementById('ordersList');
        if (full.dataset.orders) {
            // mantener el dataset original intacto usando un atributo separado
        }
    }
}

window.Pages = Pages;