import { Image } from "@/types";

export default function Carousel({ images }: { images: Image[] }) {
    return (
        <>
            <div className={'flex items-start gap-8'}>
                <div className={'flex flex-col items-center gap-2 py-2'}>
                    {images.map((image, index) => (
                        <a href={'#item' + index} key={image.id}
                           className={'border-2 hover:border-blue-500'}
                        >
                            <img
                                src={image.thumb}
                                alt={''}
                                className={'w-[50px]'}
                            />
                        </a>
                    ))}
                </div>
                <div className={'carousel w-full'}>
                    {images.map((image, index) => (
                        <div id={'item' + index} key={image.id} className={'carousel-item w-full'}>
                            <img
                                src={image.large}
                                alt={''}
                                className={'w-full'}
                            />
                        </div>
                    ))}
                </div>
            </div>
        </>
    );
}
