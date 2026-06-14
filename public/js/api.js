/**
 * API CLIENT
 * Gestiona todas las llamadas a la API del backend
 */

class APIClient {
    constructor() {
        this.baseURL = 'http://127.0.0.1:8000/api';
        this.token = Utils.getFromStorage('auth_token');
    }

    /**
     * Realizar petición genérica
     */
    async request(endpoint, options = {}) {
        const url = `${this.baseURL}${endpoint}`;
        const headers = {
            'Content-Type': 'application/json',
            ...options.headers,
        };

        // Agregar token si existe
        if (this.token) {
            headers['Authorization'] = `Bearer ${this.token}`;
        }

        try {
            const response = await fetch(url, {
                ...options,
                headers,
            });

            const data = await response.json();

            if (!response.ok) {
                // Si es 401, limpiar auth y redirigir
                if (response.status === 401) {
                    this.logout();
                    window.location.href = '/#/login';
                }
                throw new Error(data.message || `HTTP error! status: ${response.status}`);
            }

            return data;
        } catch (error) {
            console.error('API Error:', error);
            throw error;
        }
    }

    /**
     * GET request
     */
    async get(endpoint) {
        return this.request(endpoint, { method: 'GET' });
    }

    /**
     * POST request
     */
    async post(endpoint, data) {
        return this.request(endpoint, {
            method: 'POST',
            body: JSON.stringify(data),
        });
    }

    /**
     * PUT request
     */
    async put(endpoint, data) {
        return this.request(endpoint, {
            method: 'PUT',
            body: JSON.stringify(data),
        });
    }

    /**
     * DELETE request
     */
    async delete(endpoint) {
        return this.request(endpoint, { method: 'DELETE' });
    }

    /**
     * Establece el token
     */
    setToken(token) {
        this.token = token;
        Utils.saveToStorage('auth_token', token);
    }

    /**
     * Limpia el token
     */
    clearToken() {
        this.token = null;
        Utils.removeFromStorage('auth_token');
    }

    /**
     * Logout
     */
    logout() {
        this.clearToken();
        Utils.removeFromStorage('user');
        Utils.removeFromStorage('roles');
        Utils.removeFromStorage('permissions');
    }

    // ============ AUTH ENDPOINTS ============

    /**
     * Login
     */
    async login(email, password, remember = false) {
        const response = await this.post('/login', {
            email,
            password,
            remember,
        });
        
        if (response.data?.token) {
            this.setToken(response.data.token);
            Utils.saveToStorage('user', response.data.user);
            Utils.saveToStorage('roles', response.data.user.roles || []);
            Utils.saveToStorage('permissions', response.data.user.permissions || []);
        }
        
        return response;
    }

    /**
     * Register
     */
    async register(userData) {
        console.log("Registrando usuario:", userData);
        const response = await this.post('/register', userData);
        
        if (response.data?.token) {
            this.setToken(response.data.token);
            Utils.saveToStorage('user', response.data.user);
            Utils.saveToStorage('roles', response.data.user.roles || []);
            Utils.saveToStorage('permissions', response.data.user.permissions || []);
        }
        
        return response;
    }

    /**
     * Get current user
     */
    async getCurrentUser() {
        return this.get('/me');
    }

    /**
     * Logout
     */
    async logoutRequest() {
        await this.post('/logout', {});
        this.logout();
    }

    // ============ PRODUCTOS ENDPOINTS ============

    /**
     * Obtener todos los productos
     */
    async getProducts() {
        console.log("Esto es un get producto:",this.get('/productos'));
        return this.get('/productos');
    }

    /**
     * Obtener un producto específico
     */
    async getProductDetail(id) {
            console.log("Consultando producto:", id);
        return this.get(`/productos/${id}`);
    }

    /**
     * Crear producto (admin)
     */
    async createProduct(productData) {
        return this.post('/productos', productData);
    }

    /**
     * Actualizar producto (admin)
     */
    async updateProduct(id, productData) {
        return this.put(`/productos/${id}`, productData);
    }

    /**
     * Eliminar producto (admin)
     */
    async deleteProduct(id) {
        return this.delete(`/productos/${id}`);
    }

    // ============ CATEGORÍAS ENDPOINTS ============

    /**
     * Obtener todas las categorías
     */
    async getCategories() {
        return this.get('/categorias');
    }

    /**
     * Crear categoría (admin)
     */
    async createCategory(categoryData) {
        return this.post('/categorias', categoryData);
    }

    /**
     * Actualizar categoría (admin)
     */
    async updateCategory(id, categoryData) {
        return this.put(`/categorias/${id}`, categoryData);
    }

    /**
     * Eliminar categoría (admin)
     */
    async deleteCategory(id) {
        return this.delete(`/categorias/${id}`);
    }

    // ============ ÓRDENES ENDPOINTS ============

    /**
     * Crear orden
     */
    async createOrder(orderData) {
        return this.post('/ordenes', orderData);
    }

    /**
     * Obtener órdenes del usuario
     */
    async getUserOrders() {
        return this.get('/ordenes');
    }

    /**
     * Obtener todas las órdenes (admin)
     */
    async getAllOrders() {
        return this.get('/ordenes');
    }

    /**
     * Obtener una orden específica
     */
    async getOrder(id) {
        return this.get(`/ordenes/${id}`);
    }


    /**
     * Actualizar estado de orden (admin)
     */
    async updateOrderStatus(id, status) {
        console.log(`Actualizando orden ${id} a estado: ${status}`);
        
        return this.put(`/ordenes/${id}/status`, {status});
        
    }

    // ============ IMÁGENES ENDPOINTS ============

    /**
     * Subir imagen
     */
    async uploadImage(file) {
        const formData = new FormData();
        formData.append('image', file);

        return fetch(`${this.baseURL}/imagenes`, {
            method: 'POST',
            headers: {
                'Authorization': `Bearer ${this.token}`,
            },
            body: formData,
        }).then(res => res.json());
    }

    /**
     * Eliminar imagen
     */
    async deleteImage(id) {
        return this.delete(`/imagenes/${id}`);
    }
}

// Crear instancia global
const api = new APIClient();
window.api = api;
