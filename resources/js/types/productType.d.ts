export type baseProductType = {
    description: string;
    name: string;
    price: number | null;
    tag: string;
    tag_color: string;
    vat_percent: number;
};

export type productType = baseProductType & {
    id: number;
    index: number;
};
