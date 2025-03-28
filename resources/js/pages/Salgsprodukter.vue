<script setup lang="ts">
import { lightenColor } from '@/composables/utils.ts';
import AppLayout from '@/layouts/AppLayout.vue';
import { productType } from '@/types/productType';
import { Head } from '@inertiajs/vue3';
import Badge from 'primevue/badge';
import Button from 'primevue/button';
import ColorPicker from 'primevue/colorpicker';
import Column from 'primevue/column';
import DataTable from 'primevue/datatable';
import Dialog from 'primevue/dialog';
import InputNumber from 'primevue/inputnumber';
import InputText from 'primevue/inputtext';
import Textarea from 'primevue/textarea';
import { onBeforeUnmount, onMounted, ref } from 'vue';

const screenWidth = ref(window.innerWidth);
const updateScreenWidth = () => {
    screenWidth.value = window.innerWidth;
};

onMounted(() => {
    window.addEventListener('resize', updateScreenWidth);
});

onBeforeUnmount(() => {
    window.removeEventListener('resize', updateScreenWidth);
});

const defaultTagColor = '005c9c';

const products = ref();
products.value = [
    {
        id: 236547,
        index: 0,
        name: 'Turborg Classic 0,5 l',
        description: '',
        price: 6500,
        vatPercent: 25,
        tag: 'Populær',
        tagColor: '005c9c',
    },
    { id: 57896, index: 1, name: 'Coca Cola 0,5 l', description: '', price: 4600, vatPercent: 25, tag: 'Tilbud', tagColor: 'c94f04' },
    {
        id: 45632,
        index: 2,
        name: 'Pizza parma',
        description: 'Ost, tomatsauce, parmaskinke, ruccula, grøn pesto',
        price: 12500,
        vatPercent: 25,
        tag: 'Populær',
        tagColor: '005c9c',
    },
    {
        id: 5,
        index: 3,
        name: 'Turborg Classic 0,5 l',
        description: '',
        price: 6500,
        vatPercent: 25,
        tag: 'Populær',
        tagColor: '005c9c',
    },
    { id: 6, index: 4, name: 'Coca Cola 0,5 l', description: '', price: 4600, vatPercent: 25, tag: '' },
    {
        id: 7,
        index: 5,
        name: 'Pizza parma',
        description: 'Ost, tomatsauce, parmaskinke, ruccula, grøn pesto',
        price: 12500,
        vatPercent: 25,
        tag: 'Populær, Tilbud',
        tagColor: 'c94f04',
    },
];

const onRowReorder = ({ dragIndex, dropIndex, value }: { dragIndex: number; dropIndex: number; value: productType[] }) => {
    console.log('dragIndex:', dragIndex, '\ndropIndex:', dropIndex, '\nvalue:', value);
    value[dropIndex].index = dropIndex;
    if (dragIndex > dropIndex) {
        for (let i = dropIndex + 1; i <= dragIndex; i++) {
            value[i].index++;
        }
    } else {
        for (let i = dragIndex; i < dropIndex; i++) {
            value[i].index--;
        }
    }
    products.value = value;
};

const createFormOpen = ref(false);
const createFormState = ref({
    name: '',
    description: '',
    price: null,
    tag: '',
    tagColor: defaultTagColor,
    vatPercent: 25,
});

function resetCreateForm() {
    createFormState.value.name = '';
    createFormState.value.description = '';
    createFormState.value.price = null;
    createFormState.value.tag = '';
    createFormState.value.tagColor = defaultTagColor;
    createFormState.value.vatPercent = 25;
}

function createProduct() {
    const output = {
        name: createFormState.value.name,
        description: createFormState.value.description,
        price: createFormState.value.price ? createFormState.value.price * 100 : null,
        tag: createFormState.value.tag,
        tagColor: createFormState.value.tagColor,
        vatPercent: +createFormState.value.vatPercent,
        index: products.value.length,
    };
    products.value.push(output);
    resetCreateForm();
}

const editFormOpen = ref(false);

function selectProductForEdit(data: productType) {
    console.log(data);
    editFormOpen.value = true;
}
</script>

<template>
    <Head title="Salgsprodukter" />

    <AppLayout>
        <div class="flex h-full flex-1 flex-col gap-4 rounded-xl p-4">
            <h1 class="font-bold text-[#7b7b7b]">Salgsprodukter</h1>
            <Button class="w-fit self-end font-medium" @click="createFormOpen = true">
                <span class="-mb-1 -mt-[0.1rem] text-3xl font-thin leading-none">+</span> Opret produkt
            </Button>
            <div
                class="border-color-[var(--p-datatable-body-cell-border-color)] rounded-md border-[1px] bg-white px-4 dark:bg-[var(--p-datatable-row-background)]"
            >
                <h2 class="my-4 font-bold">Alle produkter ({{ products.length }})</h2>
                <DataTable
                    :value="products"
                    tableStyle="min-width: 80%"
                    class="-mb-px bg-white"
                    scrollable
                    scrollHeight="calc(100vh - 12.25rem)"
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
                    <Column field="vatPercent" header="Momssats" v-if="screenWidth > 920">
                        <template #body="slotProps"> {{ slotProps.data.vatPercent }} % </template>
                    </Column>
                    <Column field="tag" header="Tag" v-if="screenWidth > 560">
                        <template #body="slotProps">
                            <span v-if="slotProps.data.tag">
                                <Badge
                                    :value="slotProps.data.tag"
                                    :style="{
                                        backgroundColor: lightenColor(slotProps.data.tagColor),
                                        color: '#' + slotProps.data.tagColor,
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

        <Dialog v-model:visible="createFormOpen" modal dismissable-mask header="Opret produkt" class="w-[400px] max-w-full">
            <form @submit.prevent="createProduct" method="post" class="flex flex-col gap-2">
                <label class="font-medium" for="name">Navn&VeryThinSpace;<span class="text-red-500">*</span></label>
                <InputText id="name" name="name" v-model="createFormState.name" required />

                <label class="mt-2 font-medium" for="description">Beskrivelse</label>
                <Textarea id="description" name="description" v-model="createFormState.description" rows="4" style="resize: none" />

                <label class="mt-2 font-medium" for="price">Pris&VeryThinSpace;<span class="text-red-500">*</span></label>
                <InputNumber id="price" name="price" v-model="createFormState.price" required mode="currency" currency="dkk" locale="de-DE" />

                <label class="mt-2 font-medium" for="vatPercent">Momssats %<span class="text-red-500">*</span></label>
                <InputNumber id="vatPercent" name="vatPercent" v-model="createFormState.vatPercent" :min="0" :max="100" />

                <label class="mt-2 font-medium" for="tag">Tagnavn</label>
                <InputText id="tag" name="tag" v-model="createFormState.tag" />

                <label class="mt-2 font-medium" for="tagColor">Tagfarve</label>
                <div class="flex gap-2">
                    <ColorPicker id="tagColorPicker" name="tagColorPicker" v-model="createFormState.tagColor" format="hex" />
                    <InputText id="tagColor" name="tagColor" class="w-full" v-model="createFormState.tagColor" />
                </div>

                <div class="mt-4 flex justify-end gap-2">
                    <Button
                        type="reset"
                        label="Annuller"
                        severity="secondary"
                        @click="
                            createFormOpen = false;
                            resetCreateForm();
                        "
                    ></Button>
                    <Button
                        type="submit"
                        label="Opret"
                        @click="createFormOpen = false"
                        :disabled="!createFormState.name || !createFormState.price || !createFormState.vatPercent"
                    ></Button>
                </div>
            </form>
        </Dialog>
    </AppLayout>
</template>
