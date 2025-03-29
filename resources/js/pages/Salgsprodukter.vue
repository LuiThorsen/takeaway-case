<script setup lang="ts">
import { lightenColor } from '@/composables/utils.ts';
import AppLayout from '@/layouts/AppLayout.vue';
import { baseProductType, productType } from '@/types/productType';
import { Head } from '@inertiajs/vue3';
import axios from 'axios';
import Badge from 'primevue/badge';
import Button from 'primevue/button';
import ColorPicker from 'primevue/colorpicker';
import Column from 'primevue/column';
import ConfirmDialog from 'primevue/confirmdialog';
import DataTable from 'primevue/datatable';
import Dialog from 'primevue/dialog';
import InputNumber from 'primevue/inputnumber';
import InputText from 'primevue/inputtext';
import Textarea from 'primevue/textarea';
import { useConfirm } from 'primevue/useconfirm';
import { onBeforeUnmount, onMounted, ref } from 'vue';

const defaultTagColor = '005c9c';
const screenWidth = ref(window.innerWidth);
const productFormOpen = ref(false);
const formEditing = ref(false);
const formSubmitting = ref(false);
const products = ref<productType[]>();
const changedSomething = ref(false);
const currentEditID = ref<number | null>(null);
const newProduct = ref<baseProductType>({
    name: '',
    description: '',
    price: null,
    tag: '',
    tag_color: defaultTagColor,
    vat_percent: 25,
});
const confirm = useConfirm();
const errorFields = ref({
    name: false,
    description: false,
    price: false,
    vat_percent: false,
    tag: false,
    tag_color: false,
});

const updateScreenWidth = () => {
    screenWidth.value = window.innerWidth;
};

const getProducts = async () => {
    try {
        const response = await axios.get('/api/products');
        products.value = response.data.sort((a: productType, b: productType) => (a.index > b.index ? 1 : a.index < b.index ? -1 : 0));
    } catch (error) {
        console.error('Error fetching products:', error);
    }
};

// Function to call the API and create a new product
const createProduct = async (product: baseProductType) => {
    try {
        await axios.post('/api/products', { ...product, index: products.value?.length });
        await getProducts();
        productFormOpen.value = false;
        formSubmitting.value = false;
        resetProductForm();
    } catch (error) {
        console.error('Error fetching products:', error);
        if (axios.isAxiosError(error) && error.response?.data?.errors) {
            (Object.keys(error.response.data.errors) as Array<keyof typeof errorFields.value>).forEach((key) => {
                errorFields.value[key] = true;
            });
        }
    }
};

// Function to call the API and create a new product
const updateProduct = async (id: number, updatedProduct: baseProductType) => {
    try {
        await axios.put(`/api/products/${id}`, updatedProduct);
        await getProducts();
        productFormOpen.value = false;
        formSubmitting.value = false;
        resetProductForm();
    } catch (error) {
        console.error('Error updating product:', error);
        if (axios.isAxiosError(error) && error.response?.data?.errors) {
            (Object.keys(error.response.data.errors) as Array<keyof typeof errorFields.value>).forEach((key) => {
                errorFields.value[key] = true;
            });
        }
    }
};

// Handler for the form submit event
const submitForm = () => {
    formSubmitting.value = true;
    resetErrorFields();
    const productCopy = { ...newProduct.value };
    if (productCopy.price !== null) productCopy.price *= 100;
    if (!formEditing.value) {
        createProduct(productCopy);
    } else {
        formSubmitting.value = false;
        if (currentEditID.value !== null) updateProduct(currentEditID.value, productCopy);
    }
};

const deleteProduct = async () => {
    try {
        const deletedIndex = products.value?.find((el) => el.id === currentEditID.value)?.index;
        if (!deletedIndex) throw 'Unable to find index for deleted product';

        for (let i = deletedIndex + 1 || Infinity; products.value && i < products.value.length; i++) {
            products.value[i].index--;
            updateProduct(products.value[i].id, products.value[i]);
        }

        await axios.delete(`/api/products/${currentEditID.value}`);
        await getProducts();
        productFormOpen.value = false;
    } catch (error) {
        console.error('Error deleting product:', error);
    }
};

const confirmDelete = () => {
    confirm.require({
        message: 'Er du sikker på at du vil slette dette produkt?',
        header: 'Sletning af produkt',
        rejectLabel: 'Annuller',
        rejectProps: {
            label: 'Annuller',
            severity: 'secondary',
            outlined: true,
        },
        acceptProps: {
            label: 'Slet',
            severity: 'danger',
        },
        accept: () => {
            if (currentEditID.value !== null) deleteProduct();
            getProducts();
        },
        reject: () => {},
    });
};

const onRowReorder = ({ dragIndex, dropIndex, value }: { dragIndex: number; dropIndex: number; value: productType[] }) => {
    value[dropIndex].index = dropIndex;
    updateProduct(value[dropIndex].id, value[dropIndex]);
    if (dragIndex > dropIndex) {
        for (let i = dropIndex + 1; i <= dragIndex; i++) {
            value[i].index++;
            updateProduct(value[i].id, value[i]);
        }
    } else {
        for (let i = dragIndex; i < dropIndex; i++) {
            value[i].index--;
            updateProduct(value[i].id, value[i]);
        }
    }
    products.value = value;
};

function selectProductForEdit(data: productType) {
    resetErrorFields();
    changedSomething.value = false;
    formEditing.value = true;
    productFormOpen.value = true;
    currentEditID.value = data.id;

    newProduct.value.name = data.name;
    newProduct.value.description = data.description;
    newProduct.value.price = data.price !== null ? data.price / 100 : null;
    newProduct.value.vat_percent = data.vat_percent;
    newProduct.value.tag = data.tag;
    newProduct.value.tag_color = data.tag_color;
}

function resetProductForm() {
    newProduct.value.name = '';
    newProduct.value.description = '';
    newProduct.value.price = null;
    newProduct.value.tag = '';
    newProduct.value.tag_color = defaultTagColor;
    newProduct.value.vat_percent = 25;
}

function resetErrorFields() {
    (Object.keys(errorFields.value) as Array<keyof typeof errorFields.value>).forEach((key) => {
        errorFields.value[key] = false;
    });
}

onMounted(() => {
    getProducts();
    window.addEventListener('resize', updateScreenWidth);
});

onBeforeUnmount(() => {
    window.removeEventListener('resize', updateScreenWidth);
});
</script>

<template>
    <Head title="Salgsprodukter" />

    <AppLayout>
        <div class="flex h-full max-w-[100vw] flex-1 flex-col gap-4 rounded-xl p-4 pt-32 md:max-w-[calc(100vw-var(--sidebar-width))] md:pt-4">
            <h1 class="font-bold text-[#7b7b7b]">Salgsprodukter</h1>
            <Button
                class="w-fit self-end font-medium"
                @click="
                    productFormOpen = true;
                    formEditing = false;
                    resetErrorFields();
                "
            >
                <span class="-mb-1 -ml-2 -mr-1 -mt-[0.1rem] text-2xl font-normal leading-none">＋</span> Opret produkt
            </Button>
            <div
                class="border-color-[var(--p-datatable-body-cell-border-color)] rounded-md border-[1px] bg-white px-4 dark:bg-[var(--p-datatable-row-background)]"
            >
                <h2 class="my-4 font-bold">Alle produkter ({{ products?.length }})</h2>
                <DataTable
                    :value="products"
                    tableStyle="min-width: 80%"
                    class="bg-white"
                    scrollable
                    scrollHeight="calc(100vh - 12.67rem)"
                    :reorderableRows="true"
                    @rowReorder="onRowReorder"
                >
                    <Column rowReorder headerStyle="width: 3rem" />
                    <Column field="id" header="Produkt ID" v-if="screenWidth > 1050"></Column>
                    <Column field="name" header="Navn"></Column>
                    <Column field="description" header="Beskrivelse" v-if="screenWidth > 1000 || (screenWidth < 769 && screenWidth > 600)">
                        <template #body="slotProps">
                            {{ slotProps.data.description || '–' }}
                        </template>
                    </Column>
                    <Column field="price" header="Pris">
                        <template #body="slotProps">
                            {{ slotProps.data.price.toString().slice(0, -2) }},{{ slotProps.data.price.toString().slice(-2) }}
                        </template>
                    </Column>
                    <Column field="vat_percent" header="Momssats" v-if="screenWidth > 920">
                        <template #body="slotProps"> {{ slotProps.data.vat_percent }} % </template>
                    </Column>
                    <Column field="tag" header="Tag" v-if="screenWidth > 560">
                        <template #body="slotProps">
                            <span v-if="slotProps.data.tag">
                                <Badge
                                    :value="slotProps.data.tag"
                                    :style="{
                                        backgroundColor: lightenColor(slotProps.data.tag_color),
                                        color: '#' + slotProps.data.tag_color,
                                    }"
                                />
                            </span>
                        </template>
                    </Column>
                    <Column class="w-24 !text-end">
                        <template #body="{ data }">
                            <Button
                                icon="pi"
                                @click="selectProductForEdit(data)"
                                rounded
                                :style="{
                                    backgroundColor: 'transparent',
                                    border: 'none',
                                    position: 'relative',
                                }"
                            >
                                <div
                                    class="absolute left-1/2 top-1/2 size-5 h-2 w-2 -translate-x-1/2 -translate-y-1/2 rotate-45 border-r-2 border-t-2 border-solid border-black dark:border-white"
                                ></div>
                            </Button>
                        </template>
                    </Column>
                </DataTable>
            </div>
        </div>

        <Dialog
            v-model:visible="productFormOpen"
            modal
            dismissable-mask
            :header="formEditing ? 'Rediger produkt' : 'Opret produkt'"
            class="!sm:max-h-[90%] h-[100vh] !max-h-[100vh] w-full max-w-full sm:h-auto sm:w-[400px]"
            v-on:after-hide="formEditing ? resetProductForm() : null"
        >
            <form @submit.prevent="submitForm" method="post" class="flex flex-col gap-2">
                <label class="font-medium" for="name">Navn&VeryThinSpace;<span class="text-red-500">*</span></label>
                <InputText
                    :invalid="errorFields.name"
                    id="name"
                    name="name"
                    v-model="newProduct.name"
                    required
                    :autofocus="formEditing ? false : true"
                    @value-change="changedSomething = true"
                />

                <label class="mt-2 font-medium" for="description">Beskrivelse</label>
                <Textarea
                    :invalid="errorFields.description"
                    id="description"
                    name="description"
                    v-model="newProduct.description"
                    rows="4"
                    style="resize: none"
                    @value-change="changedSomething = true"
                />

                <label class="mt-2 font-medium" for="price">Pris&VeryThinSpace;<span class="text-red-500">*</span></label>
                <InputNumber
                    :invalid="errorFields.price"
                    id="price"
                    name="price"
                    v-model="newProduct.price"
                    required
                    mode="currency"
                    currency="dkk"
                    locale="de-DE"
                    @value-change="changedSomething = true"
                />

                <label class="mt-2 font-medium" for="vat_percent">Momssats %&VeryThinSpace;<span class="text-red-500">*</span></label>
                <InputNumber
                    :invalid="errorFields.vat_percent"
                    id="vat_percent"
                    name="vat_percent"
                    v-model="newProduct.vat_percent"
                    :min="0"
                    :max="100"
                    @value-change="changedSomething = true"
                />

                <label class="mt-2 font-medium" for="tag">Tagnavn</label>
                <InputText :invalid="errorFields.tag" id="tag" name="tag" v-model="newProduct.tag" @value-change="changedSomething = true" />

                <label class="mt-2 font-medium" for="tag_color">Tagfarve</label>
                <div class="flex gap-2">
                    <ColorPicker
                        id="tagColorPicker"
                        name="tagColorPicker"
                        v-model="newProduct.tag_color"
                        format="hex"
                        @value-change="changedSomething = true"
                    />
                    <InputText
                        :invalid="errorFields.tag_color"
                        v-keyfilter.hex
                        id="tag_color"
                        name="tag_color"
                        class="w-full"
                        v-model="newProduct.tag_color"
                        @value-change="changedSomething = true"
                    />
                </div>

                <ConfirmDialog></ConfirmDialog>

                <div class="mt-4 flex justify-end gap-2">
                    <Button
                        v-if="formEditing"
                        type="button"
                        label="Slet"
                        severity="danger"
                        :style="{
                            marginRight: 'auto',
                        }"
                        @click="confirmDelete"
                    ></Button>
                    <Button
                        type="reset"
                        label="Annuller"
                        severity="secondary"
                        @click="
                            productFormOpen = false;
                            resetProductForm();
                        "
                    ></Button>
                    <Button
                        type="submit"
                        :label="formEditing ? 'Gem' : 'Opret'"
                        :disabled="
                            formEditing
                                ? formSubmitting || !changedSomething || !newProduct.name || !newProduct.price || !newProduct.vat_percent
                                : formSubmitting || !newProduct.name || !newProduct.price || !newProduct.vat_percent
                        "
                    ></Button>
                </div>
            </form>
        </Dialog>
    </AppLayout>
</template>
