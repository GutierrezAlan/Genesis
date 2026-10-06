/**
 * APP PRINCIPAL
 * Punto de entrada de la aplicación
 */

class App {
    constructor() {
        this.router = router;
        this.pages = Pages;
        this.init();
    }

    /**
     * Inicializar aplicación
     */
    init() {
        console.log('Inicializando aplicación...');

        this.setupRoutes();
        Components.renderNavbar();
        router.navigate();

        auth.subscribe(() => {
            Components.renderNavbar();
        });

        cart.subscribe(() => {
            Components.renderNavbar();
        });

        window.addEventListener('hashchange', () => {
            this.onPageChange();
        });
    }

    /**
     * Configurar rutas
     */
    setupRoutes() {
        router.register('/', {
            page: 'home',
            onEnter: () => this.pages.renderHome(),
        });

        router.register('/item/:id', {
            page: 'productDetail',
            onEnter: (params) => {
                console.log('Esto es el id:', params.id);
                this.pages.renderProductDetail(params);
            },
        });

        router.register('/carrito', {
            page: 'cart',
            onEnter: () => this.pages.renderCart(),
        });

        router.register('/checkout', {
            page: 'checkout',
            onEnter: () => this.pages.renderCheckout(),
        });

        router.register('/mis-ordenes', {
            page: 'orders',
            onEnter: () => this.pages.renderMyOrders(),
        });

        router.register('/login', {
            page: 'login',
            onEnter: () => this.pages.renderLogin(),
        });

        router.register('/register', {
            page: 'register',
            onEnter: () => this.pages.renderRegister(),
        });

        router.register('/admin', {
            page: 'admin',
            onEnter: () => this.pages.renderAdmin(),
        });
    }

    /**
     * Cuando cambia la página
     */
    onPageChange() {
        const page = router.getCurrentPage();
        console.log('Página actual:', page);

        Components.renderNavbar();

        switch (page) {
            case 'home':     this.pages.renderHome();      break;
            case 'cart':     this.pages.renderCart();      break;
            case 'checkout': this.pages.renderCheckout();  break;
            case 'orders':   this.pages.renderMyOrders();  break;
            case 'admin':    this.pages.renderAdmin();     break;
        }
    }

    /**
     * Cerrar sesión
     */
    logout() {
        auth.logout().then(() => {
            this.router.navigate('/');
        }).catch(error => {
            console.error('Error al cerrar sesión:', error);
            Utils.showNotification('Error al cerrar sesión', 'error');
        });
    }

    // ============================================================
    // CARRITO
    // ============================================================

    addToCart(productId) {
        if (!auth.isAuthenticated()) {
            Utils.showNotification('Debes iniciar sesión', 'warning');
            router.navigate('/login');
            return;
        }

        api.getProductDetail(productId).then(response => {
            const product = response.data || response;
            cart.addItem(product, 1);
        }).catch(() => {
            Utils.showNotification('Error al agregar producto', 'error');
        });
    }

    addToCartFromDetail(productId) {
        const quantityInput = document.getElementById('quantityInput');
        const quantity = parseInt(quantityInput.value) || 1;

        if (quantity < 1) {
            Utils.showNotification('Cantidad inválida', 'error');
            return;
        }

        api.getProductDetail(productId).then(response => {
            const product = response.data || response;
            cart.addItem(product, quantity);
            router.navigate('/carrito');
        }).catch(() => {
            Utils.showNotification('Error al agregar producto', 'error');
        });
    }

    removeFromCart(productId) {
        cart.removeItem(productId);
        this.pages.renderCart();
    }

    updateCartQuantity(productId, quantity) {
        quantity = parseInt(quantity);

        if (quantity < 1) {
            this.removeFromCart(productId);
            return;
        }

        cart.updateQuantity(productId, quantity);
        this.pages.renderCart();
    }

    async processOrder() {
        if (cart.isEmpty()) {
            Utils.showNotification('Tu carrito está vacío', 'warning');
            return;
        }

        const items = cart.getItems();
        const orderData = {
            items: items.map(item => ({
                id: item.id,
                titulo: item.titulo,
                description: item.description,
                category: item.category,
                price: item.price,
                cantidad: item.cantidad,
            })),
            total_price: cart.getTotalPrice(),
        };

        try {
            const button = event.target;
            button.disabled = true;
            button.textContent = 'Procesando...';

            const response = await api.createOrder(orderData);

            if (response.status === 'success' || response.data) {
                cart.clear();
                Utils.showNotification('¡Orden creada exitosamente!', 'success');
                setTimeout(() => router.navigate('/mis-ordenes'), 1500);
            }
        } catch (error) {
            Utils.showNotification(error.message || 'Error al procesar orden', 'error');
        }
    }

    // ============================================================
    // ADMIN — PRODUCTOS
    // ============================================================

    async showProductForm(productId = null) {
        const container = document.getElementById('adminContainer');

        const formHTML = (fields) => `
            <div class="admin-section">
                <div class="admin-section-header">
                    <div>
                        <h2>${productId ? 'Editar Producto' : 'Nuevo Producto'}</h2>
                        <p class="admin-section-subtitle">${productId ? 'Modificá los datos del producto' : 'Completá los datos para crear un producto'}</p>
                    </div>
                    <button class="btn btn-secondary" onclick="app.pages.renderAdmin()">← Volver al panel</button>
                </div>
                <div class="admin-form">
                    <form onsubmit="event.preventDefault(); app.saveProduct(${productId || ''})">
                        <div class="form-group">
                            <label>Título</label>
                            <input type="text" name="titulo" value="${fields.titulo || ''}" required placeholder="Ej: Remera básica negra">
                        </div>
                        <div class="form-group">
                            <label>Descripción</label>
                            <textarea name="description" required placeholder="Descripción del producto...">${fields.description || ''}</textarea>
                        </div>
                        <div class="form-group">
                            <label>Categoría</label>
                            <input type="text" name="category" value="${fields.category || ''}" required placeholder="Ej: Remera, Pantalón...">
                        </div>
                        <div class="form-group">
                            <label>Precio</label>
                            <input type="number" name="price" value="${fields.price || ''}" step="0.01" required placeholder="0.00">
                        </div>
                        <div class="form-group">
                            <label>Stock</label>
                            <input type="number" name="stock" value="${fields.stock || ''}" required placeholder="0">
                        </div>
                        <div class="form-group">
                            <label>URL de imagen</label>
                            <input type="url" name="image_url" value="${fields.image_url || ''}" placeholder="https://...">
                        </div>
                        <div style="display:flex; gap:10px; margin-top:8px;">
                            <button type="submit" class="btn btn-primary">${productId ? 'Guardar Cambios' : 'Crear Producto'}</button>
                            <button type="button" class="btn btn-secondary" onclick="app.pages.renderAdmin()">Cancelar</button>
                        </div>
                    </form>
                </div>
            </div>
        `;

        if (productId) {
            try {
                const response = await api.getProductDetail(productId);
                const product = response.data || response;
                container.innerHTML = formHTML(product);
            } catch (error) {
                Utils.showNotification('Error al cargar producto', 'error');
            }
        } else {
            container.innerHTML = formHTML({});
        }
    }

    async saveProduct(productId = null) {
        const form = event.target;
        const formData = new FormData(form);
        const productData = Object.fromEntries(formData);

        try {
            if (productId) {
                await api.updateProduct(productId, productData);
                Utils.showNotification('Producto actualizado', 'success');
            } else {
                await api.createProduct(productData);
                Utils.showNotification('Producto creado', 'success');
            }

            this.pages.renderAdmin();
        } catch (error) {
            Utils.showNotification(error.message || 'Error al guardar producto', 'error');
        }
    }

    async deleteProduct(productId) {
        if (!confirm('¿Eliminar este producto? Esta acción no se puede deshacer.')) return;

        try {
            await api.deleteProduct(productId);
            Utils.showNotification('Producto eliminado', 'success');

            // Refrescar solo la grilla de productos sin recargar todo el panel
            await Pages.loadAdminProducts();
        } catch (error) {
            Utils.showNotification(error.message || 'Error al eliminar producto', 'error');
        }
    }

    // ============================================================
    // ADMIN — ÓRDENES
    // ============================================================

    /**
     * Actualizar estado de una orden (pending ↔ completed)
     */
 

    async updateOrderStatus(orderId, newStatus) {
        try {
            const response = await api.updateOrderStatus(Number(orderId), newStatus);
            Utils.showNotification(response.data.message || 'Estado de orden actualizado', 'success');
            await Pages.loadAdminOrders();
        } catch (error) {
            Utils.showNotification(error.message || 'Error al actualizar la orden', 'error');
        }
    }

    /**
     * Eliminar una orden
     */
    async deleteOrder(orderId) {
        if (!confirm('¿Eliminar esta orden? Esta acción no se puede deshacer.')) return;

        try {
            await api.deleteOrder(orderId);
            Utils.showNotification('Orden eliminada', 'success');

            // Refrescar solo la sección de órdenes
            await Pages.loadAdminOrders();
        } catch (error) {
            Utils.showNotification(error.message || 'Error al eliminar la orden', 'error');
        }
    }
}

// Crear instancia de la app
const app = new App();
window.app = app;

console.log('✅ Aplicación lista');