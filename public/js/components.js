/**
 * COMPONENTES REUTILIZABLES
 * Componentes UI para el ecommerce
 */

class Components {
    /**
     * Renderizar navbar
     */
    static renderNavbar() {
        const navMenuDesktop = document.getElementById('navMenuDesktop');
        const navMenuList = document.getElementById('navMenuList');

        const menuItems = this.getMenuItems();

        // Desktop menu
        navMenuDesktop.innerHTML = menuItems
            .filter(item => !item.mobileOnly)
            .map(item => `
                <li>
                    ${item.type === 'link' 
                        ? `<a href="#${item.href}" class="nav-link ${item.admin ? 'admin-link' : ''}">${item.label}</a>`
                        : `<button class="nav-link ${item.admin ? 'admin-link' : ''}" onclick="${item.action}">${item.label}</button>`
                    }
                </li>
            `)
            .join('');

        // Mobile menu
        navMenuList.innerHTML = menuItems
            .map(item => `
                <li>
                    ${item.type === 'link' 
                        ? `<a href="#${item.href}" class="nav-link ${item.admin ? 'admin-link' : ''}">${item.label}</a>`
                        : `<button class="nav-link ${item.admin ? 'admin-link' : ''}" onclick="${item.action}">${item.label}</button>`
                    }
                </li>
            `)
            .join('');

        // Agregar información de usuario si está autenticado
        if (auth.isAuthenticated()) {
            const userInfo = `
                <li class="user-info">
                    <span class="user-icon">${auth.getUserIcon()}</span>
                    <span class="user-name-menu">${auth.getUserName()}</span>
                </li>
            `;
            navMenuList.insertAdjacentHTML('afterbegin', userInfo);
        }

        this.setupMenuToggle();
    }

    /**
     * Obtener items del menú
     */
    static getMenuItems() {
        const items = [];

        if (auth.isAuthenticated()) {
            if (auth.isAdmin()) {
                items.push({
                    type: 'link',
                    href: '/admin',
                    label: 'Panel Admin',
                    admin: true,
                });
            } else {
                items.push({
                    type: 'link',
                    href: '/mis-ordenes',
                    label: 'Mis Órdenes',
                });
                
                items.push({
                    type: 'button',
                    label: `🛒 Carrito (${cart.getTotalQuantity()})`,
                    action: 'app.router.navigate("/carrito")',
                    mobileOnly: false,
                });
            }

            items.push({
                type: 'button',
                label: 'Cerrar sesión',
                action: 'app.logout()',
            });
        } else {
            items.push({
                type: 'link',
                href: '/login',
                label: 'Iniciar sesión',
            });
        }

        return items;
    }

    /**
     * Configurar toggle del menú móvil
     */
    static setupMenuToggle() {
        const hamburger = document.getElementById('hamburgerBtn');
        const navMenu = document.getElementById('navMenu');

        hamburger.addEventListener('click', () => {
            hamburger.classList.toggle('active');
            navMenu.classList.toggle('active');
        });

        document.querySelectorAll('.nav-menu-list a, .nav-menu-list button').forEach(link => {
            link.addEventListener('click', () => {
                hamburger.classList.remove('active');
                navMenu.classList.remove('active');
            });
        });
    }

    /**
     * Renderizar card de producto
     */
    static renderProductCard(product) {
        const inStock = product.stock > 0;
        return `
            <div class="product-card" onclick="app.router.navigate('/item/${product.id}');">
                <div class="product-image">
                    ${product.image_url ? `<img src="${product.image_url}" alt="${product.titulo}">` : '📦'}
                </div>
                <div class="product-info">
                    <p class="product-category">${product.category || 'Sin categoría'}</p>
                    <h3 class="product-title">${product.titulo}</h3>
                    <p class="product-price">${product.price}</p>
                    <p class="product-stock ${inStock ? 'in-stock' : 'out-of-stock'}">
                        ${inStock ? `Stock: ${product.stock}` : 'Agotado'}
                    </p>
                    <div class="product-actions">
                        ${inStock 
                            ? `<button class="btn btn-primary btn-sm" onclick="event.stopPropagation(); app.addToCart(${product.id}); return false;">Agregar</button>`
                            : `<button class="btn btn-secondary btn-sm" disabled>No disponible</button>`
                        }
                    </div>
                </div>
            </div>
        `;
    }

    /**
     * Renderizar item del carrito
     */
    static renderCartItem(item) {
        return `
            <div class="carrito-item">
                <div class="item-info">
                    <h3>${Utils.escapeHTML(item.titulo)}</h3>
                    <p class="item-price">Precio unit: ${Utils.formatMoney(item.price)}</p>
                    <div class="quantity-selector">
                        <button onclick="app.updateCartQuantity(${item.id}, ${item.cantidad - 1})">-</button>
                        <input type="number" value="${item.cantidad}" min="1" max="${item.stock || 100}" onchange="app.updateCartQuantity(${item.id}, this.value)">
                        <button onclick="app.updateCartQuantity(${item.id}, ${item.cantidad + 1})">+</button>
                    </div>
                </div>
                <div class="item-total">
                    <p class="total-price">${Utils.formatMoney(item.price * item.cantidad)}</p>
                    <button class="btn-remove" onclick="app.removeFromCart(${item.id})">Eliminar</button>
                </div>
            </div>
        `;
    }

    /**
     * Renderizar order card
     */
    static renderOrderCard(order) {
        const items = Array.isArray(order.items) ? order.items : JSON.parse(order.items || '[]');
        return `
            <div class="order-card">
                <div class="order-header">
                    <span class="order-id">Orden #${order.id}</span>
                    <span class="order-status ${order.status}">${order.status}</span>
                </div>
                <div class="order-items">
                    ${items.map(item => `
                        <div class="order-item">
                            <span>${item.titulo || 'Producto'} x${item.cantidad || 1}</span>
                            <span>${Utils.formatMoney(item.price * (item.cantidad || 1))}</span>
                        </div>
                    `).join('')}
                </div>
                <div class="order-total">
                    <span>Total:</span>
                    <span>${Utils.formatMoney(order.total_price)}</span>
                </div>
                <p style="color: var(--text-muted); font-size: 12px; margin-top: 10px;">
                    Fecha: ${Utils.formatDate(order.created_at)}
                </p>
            </div>
        `;
    }

    /**
     * Renderizar grid de productos para el panel admin (cards)
     */
    static renderAdminProductCard(product, actions = null) {
        const inStock = product.stock > 0;
        return `
            <div class="admin-product-card">
                <div class="admin-product-image">
                    ${product.image_url
                        ? `<img src="${product.image_url}" alt="${Utils.escapeHTML(String(product.titulo || ''))}">`
                        : '<span class="admin-product-placeholder">📦</span>'
                    }
                    <span class="admin-product-stock-badge ${inStock ? 'in-stock' : 'out-of-stock'}">
                        ${inStock ? `Stock: ${product.stock}` : 'Agotado'}
                    </span>
                </div>
                <div class="admin-product-body">
                    <p class="admin-product-category">${Utils.escapeHTML(String(product.category || 'Sin categoría'))}</p>
                    <h3 class="admin-product-title">${Utils.escapeHTML(String(product.titulo || ''))}</h3>
                    <p class="admin-product-price">${Utils.formatMoney ? Utils.formatMoney(product.price) : product.price}</p>
                </div>
                ${actions ? `
                    <div class="admin-product-actions">
                        ${actions.map(action => `
                            <button class="btn btn-${action.type}" onclick="${action.onClick(product)}">
                                ${action.label}
                            </button>
                        `).join('')}
                    </div>
                ` : ''}
            </div>
        `;
    }

    /**
     * Mostrar spinner de carga
     */
    static showLoading(container) {
        container.innerHTML = `
            <center><div class="loading">
                <div class="spinner"></div>
                <p>Cargando...</p>
            </div></center>
        `;
    }

    /**
     * Mostrar error
     */
    static showError(container, message) {
        container.innerHTML = `
            <div class="alert alert-error" style="margin: 20px 0;">
                ${message}
            </div>
        `;
    }

    /**
     * Renderizar formulario
     */
    static renderForm(fields, onSubmit, submitText = 'Enviar') {
        return `
            <form class="auth-form" onsubmit="event.preventDefault(); ${onSubmit}">
                ${fields.map(field => `
                    <div class="form-group">
                        <label for="${field.name}">${field.label}</label>
                        <input 
                            type="${field.type || 'text'}" 
                            id="${field.name}" 
                            name="${field.name}"
                            ${field.required ? 'required' : ''}
                            ${field.placeholder ? `placeholder="${field.placeholder}"` : ''}
                            ${field.value ? `value="${field.value}"` : ''}
                        >
                    </div>
                `).join('')}
                <button type="submit" class="btn btn-primary">${submitText}</button>
            </form>
        `;
    }
}

window.Components = Components;