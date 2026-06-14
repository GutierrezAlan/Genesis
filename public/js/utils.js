/**
 * UTILIDADES GLOBALES
 * Funciones helper y utilidades comunes
 */

class Utils {
    /**
     * Realiza una petición HTTP
     */
    static async fetch(url, options = {}) {
        try {
            const response = await fetch(url, options);
            
            if (!response.ok) {
                throw new Error(`HTTP error! status: ${response.status}`);
            }
            
            return await response.json();
        } catch (error) {
            console.error('Fetch error:', error);
            throw error;
        }
    }

    /**
     * Formatea dinero a moneda
     */
    static formatMoney(amount) {
        return new Intl.NumberFormat('es-AR', {
            style: 'currency',
            currency: 'ARS',
            minimumFractionDigits: 2,
        }).format(amount);
    }

    /**
     * Formatea fecha
     */
    static formatDate(date) {
        return new Intl.DateTimeFormat('es-AR', {
            year: 'numeric',
            month: 'long',
            day: 'numeric',
            hour: '2-digit',
            minute: '2-digit',
        }).format(new Date(date));
    }

    /**
     * Valida email
     */
    static isValidEmail(email) {
        const regex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        return regex.test(email);
    }

    /**
     * Muestra notificación
     */
    static showNotification(message, type = 'info', duration = 3000) {
        const notification = document.createElement('div');
        notification.className = `alert alert-${type}`;
        notification.textContent = message;
        notification.style.position = 'fixed';
        notification.style.top = '80px';
        notification.style.right = '20px';
        notification.style.zIndex = '1000';
        notification.style.maxWidth = '400px';

        document.body.appendChild(notification);

        setTimeout(() => {
            notification.remove();
        }, duration);
    }

    /**
     * Guarda en localStorage
     */
    static saveToStorage(key, value) {
        try {
            localStorage.setItem(key, JSON.stringify(value));
        } catch (error) {
            console.error('Error saving to storage:', error);
        }
    }

    /**
     * Obtiene de localStorage
     */
    static getFromStorage(key) {
        try {
            const value = localStorage.getItem(key);
            return value ? JSON.parse(value) : null;
        } catch (error) {
            console.error('Error reading from storage:', error);
            return null;
        }
    }

    /**
     * Elimina de localStorage
     */
    static removeFromStorage(key) {
        try {
            localStorage.removeItem(key);
        } catch (error) {
            console.error('Error removing from storage:', error);
        }
    }

    /**
     * Limpia localStorage
     */
    static clearStorage() {
        try {
            localStorage.clear();
        } catch (error) {
            console.error('Error clearing storage:', error);
        }
    }

    /**
     * Escapa HTML
     */
    static escapeHTML(text) {
        const map = {
            '&': '&amp;',
            '<': '&lt;',
            '>': '&gt;',
            '"': '&quot;',
            "'": '&#039;',
        };
        return text.replace(/[&<>"']/g, m => map[m]);
    }

    /**
     * Debounce
     */
    static debounce(func, wait) {
        let timeout;
        return function executedFunction(...args) {
            const later = () => {
                clearTimeout(timeout);
                func(...args);
            };
            clearTimeout(timeout);
            timeout = setTimeout(later, wait);
        };
    }

    /**
     * Obtiene el valor de un parámetro en la URL
     */
    static getUrlParam(param) {
        const params = new URLSearchParams(window.location.search);
        return params.get(param);
    }

    /**
     * Redirige a una URL
     */
    static redirect(url) {
        window.location.href = url;
    }

    /**
     * Verifica si está en línea
     */
    static isOnline() {
        return navigator.onLine;
    }

    /**
     * Copia texto al portapapeles
     */
    static copyToClipboard(text) {
        navigator.clipboard.writeText(text).then(() => {
            this.showNotification('Copiado al portapapeles', 'success');
        });
    }

    /**
     * Genera un ID único
     */
    static generateId() {
        return '_' + Math.random().toString(36).substr(2, 9);
    }

    /**
     * Valida que un objeto tenga propiedades requeridas
     */
    static validateRequired(obj, required = []) {
        return required.every(key => obj.hasOwnProperty(key) && obj[key] !== null && obj[key] !== '');
    }
}

// Exportar para uso global
window.Utils = Utils;
