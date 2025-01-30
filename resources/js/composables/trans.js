import { usePage } from "@inertiajs/react";

/**
 * Traduce una clave de idioma y reemplaza los parámetros dinámicos si existen.
 * @param {string} key - Clave de la traducción.
 * @param {object} params - Parámetros dinámicos (ej: { name: "Juan" }).
 * @returns {string} - Traducción con los parámetros reemplazados.
 */
export function useTrans(key, params = {}) {
    const translations = usePage().props.translations;

    let translation = translations[key] || key;

    Object.keys(params).forEach((param) => {
        const regex = new RegExp(`:${param}`, 'g'); // Replace all occurrences of :param
        translation = translation.replace(regex, params[param]);
    });

    return translation;
}

/**
 * Genera una URL con el prefijo del idioma actual.
 * @param {string} path - Ruta a agregar (ej: "/welcome").
 * @returns {string} - URL con prefijo de idioma (ej: "/es/welcome").
 */
export function useRoute(path = "") {
    const { locale } = usePage().props;
    return `/${locale}${path}`;
}
