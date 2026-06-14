/**
 * CART MANAGER
 * Gestiona el carrito de compras
 */

class CartManager {
    constructor() {
        this.items = [];
        this.listeners = [];
        this.load();
    }

    /**
     * Obtener clave de almacenamiento
     */
    getStorageKey() {
        const userId = auth.user?.id;
        return userId ? `carrito_user_${userId}` : 'carrito_anonimo';
    }

    /**
     * Cargar carrito desde localStorage
     */
    load() {
        try {
            const key = this.getStorageKey();
            const stored = localStorage.getItem(key);
            this.items = stored ? JSON.parse(stored) : [];
        } catch (error) {
            console.error('Error loading cart:', error);
            this.items = [];
        }
    }

    /**
     * Guardar carrito a localStorage
     */
    save() {
        try {
            const key = this.getStorageKey();
            localStorage.setItem(key, JSON.stringify(this.items));
            this.notify();
        } catch (error) {
            console.error('Error saving cart:', error);
        }
    }

    /**
     * Suscribirse a cambios
     */
    subscribe(listener) {
        this.listeners.push(listener);
    }

    /**
     * Notificar cambios
     */
    notify() {
        this.listeners.forEach(listener => listener());
    }

    /**
     * Agregar item al carrito
     */
    addItem(product, quantity = 1) {
        if (!auth.isAuthenticated()) {
            Utils.showNotification('Debes iniciar sesión', 'warning');
            return false;
        }
        console.log('Agregando al carrito:', product, 'Cantidad:', quantity);

        if (!product?.id || quantity <= 0) {
            Utils.showNotification('Producto inválido', 'error');
            return false;
        }

        const existing = this.items.find(item => item.id === product.id);

        if (existing) {
            existing.cantidad += quantity;
        } else {
            this.items.push({
                ...product,
                cantidad: quantity,
            });
        }

        this.save();
        Utils.showNotification('Producto agregado al carrito', 'success');
        return true;
    }

    /**
     * Remover item del carrito
     */
    removeItem(productId) {
        this.items = this.items.filter(item => item.id !== productId);
        this.save();
        Utils.showNotification('Producto removido', 'info');
    }

    /**
     * Actualizar cantidad
     */
    updateQuantity(productId, quantity) {
        if (quantity <= 0) {
            this.removeItem(productId);
            return;
        }

        const item = this.items.find(i => i.id === productId);
        if (item) {
            item.cantidad = quantity;
            this.save();
        }
    }

    /**
     * Vaciar carrito
     */
    clear() {
        this.items = [];
        this.save();
        Utils.showNotification('Carrito vaciado', 'info');
    }

    /**
     * Obtener cantidad total de items
     */
    getTotalQuantity() {
        return this.items.reduce((acc, item) => acc + (item.cantidad || 0), 0);
    }

    /**
     * Obtener precio total
     */
    getTotalPrice() {
        return this.items.reduce((acc, item) => acc + ((item.price || 0) * (item.cantidad || 0)), 0);
    }

    /**
     * Obtener items
     */
    getItems() {
        return this.items;
    }

    /**
     * Obtener cantidad de items
     */
    getItemCount() {
        return this.items.length;
    }

    /**
     * Verificar si está vacío
     */
    isEmpty() {
        return this.items.length === 0;
    }

    /**
     * Obtener un item
     */
    getItem(productId) {
        return this.items.find(item => item.id === productId);
    }

    /**
     * Sincronizar carrito cuando cambia el usuario
     */
    syncUser() {
        this.load();
        this.notify();
    }
}

// Crear instancia global
const cart = new CartManager();
window.cart = cart;

// Sincronizar carrito cuando cambia autenticación
auth.subscribe(() => {
    cart.syncUser();
});
