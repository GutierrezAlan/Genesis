/**
 * AUTH MANAGER
 * Gestiona el estado de autenticación
 */

class AuthManager {
    constructor() {
        this.user = Utils.getFromStorage('user');
        this.roles = Utils.getFromStorage('roles') || [];
        this.permissions = Utils.getFromStorage('permissions') || [];
        this.token = Utils.getFromStorage('auth_token');
        this.listeners = [];
    }

    /**
     * Suscribirse a cambios de autenticación
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
     * Login
     */
    async login(email, password, remember = false) {
        try {
            const response = await api.login(email, password, remember);
            
            if (response.status === 'success' || response.data) {
                this.user = response.data.user || response.user;
                this.roles = this.user.roles || [];
                this.permissions = this.user.permissions || [];
                this.token = response.data.token || response.token;
                
                Utils.saveToStorage('user', this.user);
                Utils.saveToStorage('roles', this.roles);
                Utils.saveToStorage('permissions', this.permissions);
                
                this.notify();
                return { success: true };
            }
        } catch (error) {
            console.error('Login error:', error);
            return { success: false, error: error.message };
        }
    }

    /**
     * Register
     */
    async register(userData) {
        try {
            const response = await api.register(userData);
            
            if (response.status === 'success' || response.data) {
                this.user = response.data.user || response.user;
                this.roles = this.user.roles || [];
                this.permissions = this.user.permissions || [];
                this.token = response.data.token || response.token;
                
                Utils.saveToStorage('user', this.user);
                Utils.saveToStorage('roles', this.roles);
                Utils.saveToStorage('permissions', this.permissions);
                
                this.notify();
                return { success: true };
            }
        } catch (error) {
            console.error('Register error:', error);
            return { success: false, error: error.message };
        }
    }

    /**
     * Logout
     */
    async logout() {
        try {
            await api.logoutRequest();
        } catch (error) {
            console.error('Logout error:', error);
        }
        
        this.user = null;
        this.roles = [];
        this.permissions = [];
        this.token = null;
        
        Utils.removeFromStorage('user');
        Utils.removeFromStorage('roles');
        Utils.removeFromStorage('permissions');
        Utils.removeFromStorage('auth_token');
        
        this.notify();
    }

    /**
     * Verificar si está autenticado
     */
    isAuthenticated() {
        return !!this.user && !!this.token;
    }

    /**
     * Verificar si es admin
     */
    isAdmin() {
        return this.roles && this.roles.some(role => role.name === 'admin' || role === 'admin');
    }

    /**
     * Verificar si tiene permiso
     */
    hasPermission(permission) {
        return this.permissions && this.permissions.some(p => p.name === permission || p === permission);
    }

    /**
     * Verificar si tiene rol
     */
    hasRole(role) {
        return this.roles && this.roles.some(r => r.name === role || r === role);
    }

    /**
     * Obtener usuario actual
     */
    getUser() {
        return this.user;
    }

    /**
     * Obtener nombre del usuario
     */
    getUserName() {
        if (!this.user) return '';
        return this.user.first_name || this.user.name || this.user.email || 'Usuario';
    }

    /**
     * Obtener iniciales del usuario
     */
    getUserInitials() {
        const name = this.getUserName();
        return name.split(' ').map(word => word[0]).join('').toUpperCase();
    }

    /**
     * Obtener ícono del usuario
     */
    getUserIcon() {
        return this.isAdmin() ? '👑' : '👤';
    }
}

// Crear instancia global
const auth = new AuthManager();
window.auth = auth;
