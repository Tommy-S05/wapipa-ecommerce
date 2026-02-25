import { Link, usePage } from "@inertiajs/react";
import { useTrans } from "@/composables/trans";
import { PageProps as InertiaPageProps } from '@inertiajs/core';
import { PageProps } from "@/types";

// interface PageProps extends InertiaPageProps {
//     locale: string;
//     locales: Record<string, string>;
// }

export default function LanguageSwitcher() {
    const { locale, locales } = usePage<PageProps>().props;

    return (
        <div className="dropdown dropdown-end">
            {/* Botón principal con el código del idioma seleccionado */}
            <label
                tabIndex={0}
                className="btn btn-ghost flex items-center gap-2"
                title={useTrans("Change language")}
            >
                <span className="text-base font-semibold uppercase text-gray-800">
                    {locale}
                </span>
            </label>

            {/* Menú desplegable */}
            <ul
                tabIndex={0}
                className="menu dropdown-content mt-3 w-52 bg-base-100 shadow rounded-box z-[1]"
            >
                {Object.entries(locales).map(([code, name]) => (
                    <li key={code} className={locale === code ? "bg-neutral text-white" : ""}>
                        <Link
                            // href={'#'}
                            href={
                                locale === code ? "" : `/${code}`
                            }
                            className={`flex items-center gap-2 p-2 rounded-md ${
                                locale === code ? '' : 'hover:bg-gray-200'
                            }`}
                        >
                            {/* Código del idioma como badge */}
                            <span
                                className={`px-2 py-1 text-xs font-semibold uppercase rounded-full ${
                                    locale === code
                                        ? "bg-gray-800 text-white rounded-sm"
                                        : "bg-gray-200 text-gray-800"
                                }`}
                            >
                                {code}
                            </span>
                            {/* Nombre del idioma */}
                            <span className="text-sm">{name}</span>
                        </Link>
                    </li>
                ))}
            </ul>
        </div>
    );
}
