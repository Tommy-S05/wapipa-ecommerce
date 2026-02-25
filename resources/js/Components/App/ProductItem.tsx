import { PageProps, Product } from "@/types";
import { Link, usePage } from "@inertiajs/react";
import { useTrans } from "@/composables/trans";
import CurrencyFormatter from "@/Components/Core/CurrencyFormatter";


export default function ProductItem({ product }: { product: Product }) {
    const { locale } = usePage<PageProps>().props;

    return (
        <div className={'card bg-base-100 shadow-xl'}>
            <Link
                href={route('products.show', product.slug )}
                // href={'#'}
            >
                <figure className={'px-10 pt-10'}>
                    <img
                        src={product.image}
                        alt={product.title}
                        className={'aspect-square object-cover'}
                    />
                </figure>
            </Link>

            <div className={'card-body'}>
                <h2 className={'card-title'}>{product.title}</h2>
                <p>
                    by <Link href={'#'} className={'hover:underline'}>{product.user.name}</Link>&nbsp;
                    in <Link href={'#'} className={'hover:underline'}>{product.department.name}</Link>
                </p>
                <div className={'card-actions items-center justify-between mt-3'}>
                    <button className={'btn btn-primary'}>
                        {useTrans('Add to cart')}
                    </button>
                    <span className={'text-2xl'}>
                        <CurrencyFormatter amount={product.price} locale={locale}/>
                    </span>
                </div>
            </div>
        </div>
    );
}
