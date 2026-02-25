import { Product, VariationTypeOption } from "@/types";
import { router, useForm, usePage } from "@inertiajs/react";
import { useEffect, useMemo, useState } from "react";

export default function Show({ product, variationsOptions }: {
    product: Product,
    variationsOptions: number[],
}) {
    console.log(product);

    const form = useForm<{
        options_id: Record<string, number>;
        quantity: number;
        price: number | null;
    }>({
        options_id: {},
        quantity: 1,
        price: product.price, // TODO - populate price on change
    })

    const { url } = usePage();

    const [selectedOptions, setSelectedOptions] = useState<Record<number, VariationTypeOption>>([]);

    const images = useMemo(() => {
        for (let typeId in selectedOptions) {
            const option = selectedOptions[typeId];
            if (option.images.length > 0) return option.images
        }
        return product.images;
    }, [product, selectedOptions]);

    const computedProduct = useMemo(() => {
        const selectedOptionIds = Object.values(selectedOptions).map(option => option.id).sort();

        for (let variation of product.variations) {
            const optionIds = variation.variation_type_option_ids.sort();
            // if (variationOptionsIds.join() === selectedOptionsIds.join()){
            //     return variation;
            // }
            if (arraysAreEqual(selectedOptionIds, optionIds)) {
                return {
                    price: variation.price,
                    quantity: variation.quantity === null ? Number.MAX_VALUE : variation.quantity,
                }
            }
        }
    }, [product, selectedOptions]);

    useEffect(() => {
        for (let type of product.variationTypes) {
            const selectedOptionId: number = variationsOptions[type.id];
            console.log(selectedOptionId, type.options);

            chooseOption(type.id, type.options.find(option =>
                option.id === selectedOptionId), false
            );
        }
    }, []);

    const getOptionIdsMap = (newOptions: object) => {
        return Object.fromEntries(
            Object.entries(newOptions).map(([key, value]) => {
                return [key, Number(value.id)];
            })
        )
    }

    const chooseOption = (
        typeId: number,
        option: VariationTypeOption,
        updateRoute: boolean = true
    ) => {
        setSelectedOptions((prevSelectedOptions) => {
            const newOptions = {
                ...prevSelectedOptions,
                [typeId]: option
            };

            if (updateRoute) {
                router.get(url, {
                    options: getOptionIdsMap(newOptions)
                }, {
                    preserveScroll: true,
                    preserveState: true
                });
            }

            return newOptions;
        });
    }

    // const onQuantityChange = (e: React.ChangeEvent<HTMLSelectElement>) => {
    const onQuantityChange = (e: React.ChangeEvent<HTMLInputElement>) => {
        form.setData('quantity', Number(e.target.value));
    }

    return (
        <div>{product.title}</div>
    );
}
