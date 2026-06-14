/**
 * ROUTER
 * Gestiona navegación entre páginas
 */

class Router {
    constructor() {
        this.currentPage = null;
        this.routes = {};
        this.setupHashListener();
    }

    /**
     * Registrar una ruta
     */
    register(path, config) {
        this.routes[path] = config;
    }

    /**
     * Escuchar cambios de hash
     */
    setupHashListener() {
        window.addEventListener('hashchange', () => this.navigate());
        // Navegar a página inicial si no hay hash
        if (!window.location.hash) {
            this.navigate('/');
        } else {
            this.navigate();
        }
    }

    /**
     * Navegar a una ruta
     */
    navigate(path = null) {
        if (path) {
            window.location.hash = '#' + path;
            return;
        }

        // Obtener path del hash
        const hash = window.location.hash.slice(2) || '/';
        const [pathname, ...queryParts] = hash.split('?');
        const query = queryParts.join('?');

        // Extraer parámetros (ej: /item/:id -> /item/123)
        const params = this.extractParams(pathname);
        
        // Buscar ruta coincidente
        const route = this.findRoute(pathname);

        if (route) {
            this.renderPage(route.page, params, query);
            
            // Ejecutar callback si existe
            if (route.onEnter) {
                route.onEnter(params, query);
            }
        } else {
            this.renderPage('notFound');
        }

        // Scroll al top
        window.scrollTo(0, 0);
    }

    /**
     * Encontrar ruta coincidente
     */
    findRoute(pathname) {
        // Búsqueda exacta
        if (this.routes[pathname]) {
            return this.routes[pathname];
        }

        // Búsqueda con parámetros
        for (const [routePath, routeConfig] of Object.entries(this.routes)) {
            if (this.pathMatches(pathname, routePath)) {
                return routeConfig;
            }
        }

        return null;
    }

    /**
     * Verificar si path coincide con pattern
     */
    pathMatches(pathname, pattern) {
        const pathParts = pathname.split('/').filter(p => p);
        const patternParts = pattern.split('/').filter(p => p);

        if (pathParts.length !== patternParts.length) {
            return false;
        }

        return patternParts.every((part, i) => {
            return part.startsWith(':') || part === pathParts[i];
        });
    }

    /**
     * Extraer parámetros de la ruta
     */
    extractParams(pathname) {
        const params = {};
        const pathParts = pathname.split('/').filter(p => p);

        for (const [routePath] of Object.entries(this.routes)) {
            if (this.pathMatches(pathname, routePath)) {
                const patternParts = routePath.split('/').filter(p => p);
                
                patternParts.forEach((part, i) => {
                    if (part.startsWith(':')) {
                        const paramName = part.slice(1);
                        params[paramName] = pathParts[i];
                    }
                });
                break;
            }
        }

        return params;
    }

    /**
     * Renderizar página
     */
    renderPage(pageId, params = {}, query = '') {
        // Ocultar todas las páginas
        document.querySelectorAll('.page').forEach(page => {
            page.classList.remove('active');
            page.classList.add('hidden');
        });

        // Mostrar página solicitada
        const page = document.getElementById(pageId + 'Page');
        if (page) {
            page.classList.remove('hidden');
            page.classList.add('active');
        }

        this.currentPage = pageId;
    }

    /**
     * Obtener página actual
     */
    getCurrentPage() {
        return this.currentPage;
    }

    /**
     * Verificar si está en página
     */
    isOnPage(pageId) {
        return this.currentPage === pageId;
    }
}

// Crear instancia global
const router = new Router();
window.router = router;
